<?php
class Order
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function createOrder($data)
    {
        $this->db->query("INSERT INTO orders (order_code, client_id, coupon_code, total_amount, discount_amount, billing_address, shipping_address, client_data_snapshot) VALUES (:code, :cid, :coupon, :total, :disc, :billing, :shipping, :snap)");
        $this->db->bind(':code', $data['order_code']);
        $this->db->bind(':cid', $data['client_id']);
        $this->db->bind(':coupon', $data['coupon_code'] ?? null);
        $this->db->bind(':total', $data['total_amount']);
        $this->db->bind(':disc', $data['discount_amount']);
        $this->db->bind(':billing', $data['billing_address']);
        $this->db->bind(':shipping', $data['shipping_address']);
        $this->db->bind(':snap', json_encode($data['client_data_snapshot']));
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getCouponByCode($code)
    {
        $this->db->query("SELECT * FROM coupons WHERE code = :code AND status = 'active' AND start_date <= NOW() AND end_date >= NOW()");
        $this->db->bind(':code', strtoupper(trim($code)));
        return $this->db->single();
    }

    public function createOrderItem($oid, $pid, $name, $qty, $price)
    {
        $this->db->query("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price) VALUES (:oid, :pid, :name, :qty, :price)");
        $this->db->bind(':oid', $oid);
        $this->db->bind(':pid', $pid);
        $this->db->bind(':name', $name);
        $this->db->bind(':qty', $qty);
        $this->db->bind(':price', $price);
        return $this->db->execute();
    }

    public function getAllAdmin()
    {
        $this->db->query("SELECT o.*, u.email FROM orders o LEFT JOIN users u ON o.client_id = u.id ORDER BY o.id DESC");
        return $this->db->resultSet();
    }

    public function updateStatus($id, $status)
    {
        $this->db->query("UPDATE orders SET status = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    public function getCoupons()
    {
        $this->db->query("SELECT * FROM coupons");
        return $this->db->resultSet();
    }

    public function createCoupon($d)
    {
        $this->db->query("INSERT INTO coupons (code, discount_percent, start_date, end_date) VALUES (:c, :dp, :sd, :ed)");
        $this->db->bind(':c', $d['code']);
        $this->db->bind(':dp', $d['discount']);
        $this->db->bind(':sd', $d['start']);
        $this->db->bind(':ed', $d['end']);
        return $this->db->execute();
    }

    public function updateCoupon($d)
    {
        $this->db->query("UPDATE coupons SET code=:c, discount_percent=:dp, start_date=:sd, end_date=:ed WHERE id=:id");
        $this->db->bind(':c', $d['code']);
        $this->db->bind(':dp', $d['discount']);
        $this->db->bind(':sd', $d['start']);
        $this->db->bind(':ed', $d['end']);
        $this->db->bind(':id', $d['id']);
        return $this->db->execute();
    }

    public function deleteCoupon($id)
    {
        $this->db->query("DELETE FROM coupons WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
