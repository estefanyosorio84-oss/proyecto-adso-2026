<?php
class AyudaController extends Controller
{
    public function index()
    {
        $this->view('layout/header', ['title' => 'Ayuda | Papeleria todo arte']);
        $this->view('pages/ayuda');
        $this->view('layout/footer');
    }

    public function send()
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
        header('Location: ' . URLROOT . '/ayuda');
        exit;
    }
}
