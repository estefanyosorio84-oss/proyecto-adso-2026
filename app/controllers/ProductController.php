<?php
class ProductController extends Controller
{
    public function ofertas()
    {
        $db = new Database();
        $db->query("SELECT * FROM offers WHERE status = 'active' AND end_date >= NOW() ORDER BY id DESC");
        $offers = $db->resultSet();

        $data = [
            'title' => 'Ofertas Especiales | Librería Ohio',
            'offers' => $offers
        ];
        $this->view('products/ofertas', $data);
    }

    public function store()
    {
        $db = new Database();
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        $category = isset($_GET['cat']) ? trim($_GET['cat']) : '';
        $offer = isset($_GET['offer']) ? trim($_GET['offer']) : '';
        $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'populares';

        $query = "SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'Publicado'";

        if ($search !== '') $query .= " AND (p.name LIKE :search OR p.short_desc LIKE :search)";
        if ($category !== '') $query .= " AND c.slug = :category";
        if ($offer !== '') $query .= " AND p.offer_id = (SELECT id FROM offers WHERE slug = :offer LIMIT 1)";

        switch ($sort) {
            case 'az':
                $query .= " ORDER BY p.name ASC";
                break;
            case 'za':
                $query .= " ORDER BY p.name DESC";
                break;
            case 'precio_menor':
                $query .= " ORDER BY (p.price - (p.price * (p.discount_percent/100))) ASC";
                break;
            case 'precio_mayor':
                $query .= " ORDER BY (p.price - (p.price * (p.discount_percent/100))) DESC";
                break;
            case 'populares':
            default:
                $query .= " ORDER BY p.sales_count DESC";
                break;
        }
        $db->query($query);

        if ($search !== '') $db->bind(':search', "%$search%");
        if ($category !== '') $db->bind(':category', $category);
        if ($offer !== '') $db->bind(':offer', $offer);

        $products = $db->resultSet();
        $db->query("SELECT * FROM categories WHERE is_main = 1 ORDER BY name ASC");
        $categories = $db->resultSet();

        $current_offer_data = null;
        if ($offer !== '') {
            $db->query("SELECT * FROM offers WHERE slug = :offer");
            $db->bind(':offer', $offer);
            $current_offer_data = $db->single();
        }

        $data = [
            'title' => 'Tienda | Librería Ohio',
            'products' => $products,
            'categories' => $categories,
            'current_cat' => $category,
            'current_sort' => $sort,
            'current_offer_data' => $current_offer_data
        ];
        $this->view('layout/header', $data);
        $this->view('products/store', $data);
        $this->view('layout/footer');
    }

    public function detail($id = null)
    {
        if (!$id) {
            header('Location: ' . URLROOT);
            exit;
        }
        $productModel = $this->model('Product');
        $data = [
            'title' => 'Detalle del Producto',
            'product' => $productModel->getById($id),
            'reviews' => $productModel->getReviews($id),
            'variations' => $productModel->getProductVariations($id)
        ];
        $this->view('layout/header', $data);
        $this->view('products/detail', $data);
        $this->view('layout/footer');
    }

    public function addReview()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
            try {
                $productModel = $this->model('Product');
                if ($productModel->addReview([
                    'product_id' => trim($_POST['product_id']),
                    'user_id' => $_SESSION['user_id'],
                    'rating' => trim($_POST['rating']),
                    'comment' => trim($_POST['comment'])
                ])) {
                    $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Reseña agregada con éxito.'];
                }
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Error al guardar la reseña.'];
            }
            header('Location: ' . URLROOT . '/product/detail/' . $_POST['product_id']);
            exit;
        }
        header('Location: ' . URLROOT);
    }
}
