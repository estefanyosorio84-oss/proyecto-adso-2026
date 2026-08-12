<?php
class AjaxController extends Controller
{
    public function search()
    {
        $db = new Database();
        $q = "%" . trim($_GET['q']) . "%";
        $db->query("SELECT id, name, price, main_image FROM products WHERE name LIKE :q LIMIT 5");
        $db->bind(':q', $q);
        echo json_encode($db->resultSet());
    }
}
