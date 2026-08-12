<?php
class Category
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getMainCategories()
    {
        $this->db->query("SELECT * FROM categories WHERE is_main = 1 ORDER BY name ASC LIMIT 6");
        return $this->db->resultSet();
    }

    public function create($data)
    {
        $this->db->query("INSERT INTO categories (name, slug, icon, is_main) VALUES (:name, :slug, :icon, :is_main)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', strtolower(str_replace(' ', '-', $data['name'])));
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':is_main', $data['is_main']);
        return $this->db->execute();
    }

    public function update($data)
    {
        $this->db->query("UPDATE categories SET name=:name, icon=:icon, is_main=:is_main WHERE id=:id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':is_main', $data['is_main']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
