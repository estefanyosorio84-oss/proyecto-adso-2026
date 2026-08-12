<?php
class CartController extends Controller
{
    public function index()
    {
        $this->view('layout/header', ['title' => 'Carrito']);
        $this->view('checkout/index');
        $this->view('layout/footer');
    }

    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Debe iniciar sesión.']);
                exit;
            }
            try {
                $orderModel = $this->model('Order');
                $productModel = $this->model('Product');
                $input = json_decode(file_get_contents('php://input'), true);
                $shippingAddress = trim($input['shipping_address'] ?? '');
                $billingData = trim($input['billing_data'] ?? '');
                $couponCode = strtoupper(trim($input['coupon_code'] ?? ''));

                if (!$shippingAddress) throw new Exception('La dirección de envío es obligatoria.');
                if (empty($input['items']) || !is_array($input['items'])) throw new Exception('El carrito no contiene elementos válidos.');

                // LÓGICA DE VALIDACIÓN ESTRICTA EN BACKEND (RE-CÁLCULO)
                $subtotalBackend = 0;
                foreach ($input['items'] as $item) {
                    $dbProduct = $productModel->getById($item['id']);
                    if (!$dbProduct || $dbProduct->stock < $item['qty']) {
                        throw new Exception("Producto {$item['name']} no tiene stock suficiente.");
                    }
                    $precioReal = $dbProduct->price - ($dbProduct->price * ($dbProduct->discount_percent / 100));
                    $subtotalBackend += ($precioReal * $item['qty']);
                }

                $discountAmountReal = 0;
                if (!empty($couponCode)) {
                    $coupon = $orderModel->getCouponByCode($couponCode);
                    if (!$coupon) {
                        throw new Exception('El cupón ingresado no existe o expiró.');
                    }
                    $discountAmountReal = round($subtotalBackend * ($coupon->discount_percent / 100), 2);
                }

                $finalTotalAmount = round($subtotalBackend - $discountAmountReal, 2);
                $orderCode = 'ORD-' . strtoupper(uniqid());

                $orderData = [
                    'order_code' => $orderCode,
                    'client_id' => $_SESSION['user_id'],
                    'coupon_code' => empty($couponCode) ? null : $couponCode,
                    'total_amount' => $finalTotalAmount,
                    'discount_amount' => $discountAmountReal,
                    'billing_address' => $billingData ?: $shippingAddress,
                    'shipping_address' => $shippingAddress,
                    'client_data_snapshot' => ['name' => $_SESSION['first_name']]
                ];

                $orderId = $orderModel->createOrder($orderData);
                if ($orderId) {
                    foreach ($input['items'] as $item) {
                        $dbProduct = $productModel->getById($item['id']);
                        $precioInsert = $dbProduct->price - ($dbProduct->price * ($dbProduct->discount_percent / 100));
                        $orderModel->createOrderItem($orderId, $item['id'], $item['name'], $item['qty'], $precioInsert);
                        $productModel->deductStock($item['id'], $item['qty']);
                    }
                    echo json_encode(['status' => 'success', 'order_code' => $orderCode]);
                    exit;
                }
                throw new Exception('No se pudo generar el pedido en base de datos.');
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
        }
    }

    public function applyCoupon()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $code = strtoupper(trim($input['code'] ?? ''));
        $total = isset($input['total']) ? floatval($input['total']) : 0;

        if (!$code) {
            echo json_encode(['status' => 'error', 'message' => 'Ingrese un código de cupón.']);
            exit;
        }
        if ($total <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'El total del carrito no es válido.']);
            exit;
        }

        $coupon = $this->model('Order')->getCouponByCode($code);
        if (!$coupon) {
            echo json_encode(['status' => 'error', 'message' => 'El cupón no existe, está inactivo o ha expirado.']);
            exit;
        }

        $discountAmount = round($total * ($coupon->discount_percent / 100), 2);
        $finalTotal = round(max(0, $total - $discountAmount), 2);
        echo json_encode([
            'status' => 'success',
            'message' => "Cupón aplicado correctamente. Se descuenta {$coupon->discount_percent}%.",
            'coupon_code' => strtoupper($coupon->code),
            'discount_amount' => $discountAmount,
            'final_total' => $finalTotal,
        ]);
        exit;
    }
}
