<?php
class HomeController extends Controller
{
    public function index()
    {
        $productModel = $this->model('Product');
        $data = [
            'title' => 'Inicio | Librería Ohio',
            'featured_products' => $productModel->getFeatured(),
            'offers' => $productModel->getOffers()
        ];
        $this->view('layout/header', $data);
        $this->view('home/index', $data);
        $this->view('layout/footer');
    }

    public function ayuda()
    {
        $this->view('layout/header', ['title' => 'Ayuda | Librería Ohio']);
        $this->view('pages/ayuda');
        $this->view('layout/footer');
    }

    public function sendHelp()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database();
            try {
                $db->query("INSERT INTO contact_messages (name, email, subject, message) VALUES (:n, :e, :s, :m)");
                $db->bind(':n', trim($_POST['name']));
                $db->bind(':e', trim($_POST['email']));
                $db->bind(':s', trim($_POST['subject']));
                $db->bind(':m', trim($_POST['message']));
                $db->execute();
                $_SESSION['flash_msg'] = ['type' => 'success', 'text' => 'Su mensaje ha sido enviado. Le responderemos pronto.'];
            } catch (Exception $e) {
                $_SESSION['flash_msg'] = ['type' => 'error', 'text' => 'Ocurrió un error al enviar su mensaje.'];
            }
        }
        header('Location: ' . URLROOT . '/home/ayuda');
        exit;
    }
}
