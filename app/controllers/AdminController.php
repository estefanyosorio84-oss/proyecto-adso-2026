<?php
class AdminController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Administrador', 'Vendedor'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
    }

    public function index()
    {
        $view = isset($_GET['view']) ? $_GET['view'] : 'dashboard';
        if ($_SESSION['role'] === 'Vendedor' && in_array($view, ['categories', 'offers', 'coupons', 'users', 'variations', 'messages'])) {
            $view = 'dashboard';
        }
        $uModel = $this->model('User');
        $pModel = $this->model('Product');
        $cModel = $this->model('Category');
        $oModel = $this->model('Order');

        $data = [
            'title' => 'Panel de Administración',
            'view' => $view,
            'role' => $_SESSION['role'],
            'users' => $uModel->getAll(),
            'products' => $pModel->getAllAdmin(),
            'categories' => $cModel->getAll(),
            'orders' => $oModel->getAllAdmin(),
            'offers' => $pModel->getOffers(),
            'coupons' => $oModel->getCoupons(),
            'variations' => $pModel->getVariations(),
            'messages' => $uModel->getContactMessages()
        ];
        $this->view('admin/dashboard', $data);
    }

    private function checkAdmin()
    {
        if ($_SESSION['role'] !== 'Administrador') {
            $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Acceso denegado. Se requieren permisos de Administrador.'];
            header('Location: ' . URLROOT . '/admin');
            exit;
        }
    }

    private function handleImageUpload($fileInputName, $urlInputName, $currentImage = '/img/default-product.png')
    {
        $final_image = $currentImage;
        if (!empty($_POST[$urlInputName])) {
            $final_image = filter_var($_POST[$urlInputName], FILTER_SANITIZE_URL);
        }
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $allowed_ext = ['png', 'jpg', 'jpeg', 'webp', 'avif'];
            $max_size = 2 * 1024 * 1024;
            $filename = $_FILES[$fileInputName]['name'];
            $filesize = $_FILES[$fileInputName]['size'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_ext) || $filesize > $max_size) {
                throw new Exception('Imagen principal inválida o excede 2MB.');
            }
            $new_filename = uniqid('img_', true) . '.' . $ext;
            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], dirname(dirname(dirname(__FILE__))) . '/public/uploads/' . $new_filename)) {
                $final_image = '/uploads/' . $new_filename;
            } else {
                throw new Exception('Error al guardar la imagen principal.');
            }
        }
        return $final_image;
    }

    private function handleMultipleImagesUpload($fileInputName, $existingGallery = [])
    {
        $uploadedImages = $existingGallery;
        if (isset($_FILES[$fileInputName]) && is_array($_FILES[$fileInputName]['name'])) {
            $allowed_ext = ['png', 'jpg', 'jpeg', 'webp', 'avif'];
            $totalFiles = count($_FILES[$fileInputName]['name']);
            for ($i = 0; $i < $totalFiles; $i++) {
                if ($_FILES[$fileInputName]['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES[$fileInputName]['name'][$i], PATHINFO_EXTENSION));
                    if (in_array($ext, $allowed_ext) && $_FILES[$fileInputName]['size'][$i] <= 2 * 1024 * 1024) {
                        $new_filename = uniqid('gal_', true) . '.' . $ext;
                        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'][$i], dirname(dirname(dirname(__FILE__))) . '/public/uploads/' . $new_filename)) {
                            $uploadedImages[] = '/uploads/' . $new_filename;
                        }
                    }
                }
            }
        }
        return json_encode($uploadedImages);
    }

    // MÉTODOS CREAR Y ACTUALIZAR CON VALIDACIONES
    public function addProduct()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if (empty($_POST['name']) || empty($_POST['sku']) || empty($_POST['price'])) throw new Exception("Faltan campos obligatorios en el producto.");
                $imagePath = $this->handleImageUpload('main_image_file', 'main_image_url');
                $_POST['main_image'] = $imagePath;
                $_POST['gallery'] = $this->handleMultipleImagesUpload('gallery_files');

                if ($this->model('Product')->create($_POST)) {
                    $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Producto creado correctamente.'];
                }
            } catch (PDOException $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error BD: Compruebe que el SKU no esté duplicado.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => $e->getMessage()];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=products');
        exit;
    }

    public function updateProduct()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if (empty($_POST['id']) || empty($_POST['name']) || empty($_POST['sku'])) throw new Exception("Faltan campos obligatorios para actualizar.");
                $imagePath = $this->handleImageUpload('main_image_file', 'main_image_url', trim($_POST['current_main_image']));
                $_POST['main_image'] = $imagePath;
                $existingGallery = empty($_POST['current_gallery']) ? [] : json_decode($_POST['current_gallery'], true);
                $_POST['gallery'] = $this->handleMultipleImagesUpload('gallery_files', $existingGallery);

                if ($this->model('Product')->update($_POST)) {
                    $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Producto actualizado correctamente.'];
                }
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => $e->getMessage()];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=products');
        exit;
    }

    public function addVariation()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if (empty($_POST['product_id']) || empty($_POST['sku'])) throw new Exception("Faltan campos en la variación.");
                if ($this->model('Product')->createVariation($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Variación creada.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al crear variación (Verifique SKU duplicado).'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=variations');
        exit;
    }

    public function updateVariation()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->model('Product')->updateVariation($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Variación actualizada.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al actualizar variación.'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=variations');
        exit;
    }

    public function addOffer()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $imagePath = $this->handleImageUpload('banner_image_file', 'banner_image_url');
                $_POST['banner'] = $imagePath;
                if ($this->model('Product')->createOffer($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Oferta activada.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al guardar la oferta.'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=offers');
        exit;
    }

    public function updateOffer()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $imagePath = $this->handleImageUpload('banner_image_file', 'banner_image_url', trim($_POST['current_banner']));
                $_POST['banner'] = $imagePath;
                if ($this->model('Product')->updateOffer($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Oferta actualizada.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al actualizar la oferta.'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=offers');
        exit;
    }

    public function addCategory()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->model('Category')->create($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Categoría creada.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al crear la categoría.'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=categories');
        exit;
    }

    public function updateCategory()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->model('Category')->update($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Categoría actualizada.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al actualizar la categoría.'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=categories');
        exit;
    }

    public function addCoupon()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->model('Order')->createCoupon($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Cupón generado.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error. El código del cupón puede estar duplicado.'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=coupons');
        exit;
    }

    public function updateCoupon()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->model('Order')->updateCoupon($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Cupón actualizado.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al actualizar cupón.'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=coupons');
        exit;
    }

    public function addUser()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->model('User')->register($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Usuario registrado exitosamente.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al registrar usuario. Verifique el correo o documento duplicado.'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=users');
        exit;
    }

    public function updateUser()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->model('User')->updateUser($_POST)) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Usuario actualizado.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al actualizar usuario.'];
            }
        }
        header('Location: ' . URLROOT . '/admin?view=users');
        exit;
    }

    // ELIMINAR Y ACTUALIZAR ESTADOS
    public function deleteEntity($type, $id)
    {
        $this->checkAdmin();
        $success = false;
        try {
            if ($type == 'user') $success = $this->model('User')->delete($id);
            if ($type == 'product') $success = $this->model('Product')->delete($id);
            if ($type == 'category') $success = $this->model('Category')->delete($id);
            if ($type == 'offer') $success = $this->model('Product')->deleteOffer($id);
            if ($type == 'coupon') $success = $this->model('Order')->deleteCoupon($id);
            if ($type == 'variation') $success = $this->model('Product')->deleteVariation($id);

            if ($success) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Registro eliminado permanentemente.'];
        } catch (Exception $e) {
            $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'No se puede eliminar por dependencia en base de datos.'];
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function updateOrderStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Order')->updateStatus($_POST['order_id'], $_POST['status'])) $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Estado del pedido actualizado.'];
        }
        header('Location: ' . URLROOT . '/admin?view=orders');
        exit;
    }

    public function readMessage($id)
    {
        $this->checkAdmin();
        $this->model('User')->updateMessageStatus($id, 'Read');
        $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Mensaje marcado como leído.'];
        header('Location: ' . URLROOT . '/admin?view=messages');
        exit;
    }
}
