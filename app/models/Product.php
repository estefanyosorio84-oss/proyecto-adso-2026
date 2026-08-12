<?php
class Product
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllPublished()
    {
        $this->db->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status IN ('Publicado', 'Con Existencias') ORDER BY p.id DESC");
        return $this->db->resultSet();
    }
    public function getAllAdmin()
    {
        $this->db->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
        return $this->db->resultSet();
    }
    public function getFeatured()
    {
        $this->db->query("SELECT * FROM products WHERE status IN ('Publicado', 'Con Existencias') ORDER BY sales_count DESC LIMIT 4");
        return $this->db->resultSet();
    }
    public function getById($id)
    {
        $this->db->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data)
    {
        $this->db->query("INSERT INTO products (name, sku, category_id, price, stock, short_desc, long_desc, main_image, gallery) VALUES (:name, :sku, :cat, :price, :stock, :sd, :ld, :img, :gal)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':cat', $data['category_id']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':sd', $data['short_desc']);
        $this->db->bind(':ld', $data['long_desc']);
        $this->db->bind(':img', $data['main_image']);
        $this->db->bind(':gal', $data['gallery'] ?? null);
        return $this->db->execute();
    }
    public function update($data)
    {
        $this->db->query("UPDATE products SET name=:name, sku=:sku, category_id=:cat, price=:price, discount_percent=:dp, stock=:stock, short_desc=:sd, long_desc=:ld, status=:st, main_image=:img, gallery=:gal WHERE id=:id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':cat', $data['category_id']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':dp', $data['discount_percent']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':sd', $data['short_desc']);
        $this->db->bind(':ld', $data['long_desc']);
        $this->db->bind(':st', $data['status']);
        $this->db->bind(':img', $data['main_image']);
        $this->db->bind(':gal', $data['gallery'] ?? null);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }
    public function delete($id)
    {
        $this->db->query("DELETE FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    public function deductStock($id, $qty)
    {
        $this->db->query("UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty");
        $this->db->bind(':id', $id);
        $this->db->bind(':qty', $qty);
        return $this->db->execute();
    }

    public function getOffers()
    {
        $this->db->query("SELECT * FROM offers WHERE status='active' AND end_date >= NOW()");
        return $this->db->resultSet();
    }

    public function createOffer($d)
    {
        $this->db->query("INSERT INTO offers (name, slug, title, short_desc, discount_percent, banner_image, start_date, end_date, status) VALUES (:n, :s, :t, :sd, :dp, :bi, :sd2, :ed, 'active')");
        $this->db->bind(':n', $d['name']);
        $this->db->bind(':s', strtolower(str_replace(' ', '-', $d['name'])));
        $this->db->bind(':t', $d['title']);
        $this->db->bind(':sd', $d['short_desc']);
        $this->db->bind(':dp', $d['discount']);
        $this->db->bind(':bi', $d['banner']);
        $this->db->bind(':sd2', $d['start']);
        $this->db->bind(':ed', $d['end']);
        return $this->db->execute();
    }
    public function updateOffer($d)
    {
        $this->db->query("UPDATE offers SET name=:n, title=:t, short_desc=:sd, discount_percent=:dp, start_date=:sd2, end_date=:ed, banner_image=:bi WHERE id=:id");
        $this->db->bind(':n', $d['name']);
        $this->db->bind(':t', $d['title']);
        $this->db->bind(':sd', $d['short_desc']);
        $this->db->bind(':dp', $d['discount']);
        $this->db->bind(':sd2', $d['start']);
        $this->db->bind(':ed', $d['end']);
        $this->db->bind(':bi', $d['banner']);
        $this->db->bind(':id', $d['id']);
        return $this->db->execute();
    }
    public function deleteOffer($id)
    {
        $this->db->query("DELETE FROM offers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getVariations()
    {
        $this->db->query("SELECT v.*, p.name as p_name FROM product_variations v JOIN products p ON v.product_id = p.id");
        return $this->db->resultSet();
    }
    public function getProductVariations($product_id)
    {
        $this->db->query("SELECT * FROM product_variations WHERE product_id = :pid");
        $this->db->bind(':pid', $product_id);
        return $this->db->resultSet();
    }

    public function createVariation($d)
    {
        $this->db->query("INSERT INTO product_variations (product_id, name, sku, price, stock) VALUES (:pid, :n, :s, :p, :st)");
        $this->db->bind(':pid', $d['product_id']);
        $this->db->bind(':n', $d['name']);
        $this->db->bind(':s', $d['sku']);
        $this->db->bind(':p', $d['price']);
        $this->db->bind(':st', $d['stock']);
        return $this->db->execute();
    }
    public function updateVariation($d)
    {
        $this->db->query("UPDATE product_variations SET name=:n, sku=:s, price=:p, stock=:st WHERE id=:id");
        $this->db->bind(':n', $d['name']);
        $this->db->bind(':s', $d['sku']);
        $this->db->bind(':p', $d['price']);
        $this->db->bind(':st', $d['stock']);
        $this->db->bind(':id', $d['id']);
        return $this->db->execute();
    }
    public function deleteVariation($id)
    {
        $this->db->query("DELETE FROM product_variations WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Reseñas
    public function getReviews($product_id)
    {
        $this->db->query("SELECT r.*, u.first_name, u.last_name FROM product_reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = :pid ORDER BY r.created_at DESC");
        $this->db->bind(':pid', $product_id);
        return $this->db->resultSet();
    }
    public function addReview($data)
    {
        $this->db->query("INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (:pid, :uid, :r, :c)");
        $this->db->bind(':pid', $data['product_id']);
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':r', $data['rating']);
        $this->db->bind(':c', $data['comment']);
        return $this->db->execute();
    }
}
