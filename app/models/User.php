<?php
class User
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function login($email, $password)
    {
        // Bloqueo explícito aquí si el status no es active
        $this->db->query("SELECT * FROM users WHERE email = :email AND status = 'active'");
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if ($row && password_verify($password, $row->password)) {
            return $row;
        }
        return false;
    }

    public function register($data)
    {
        $this->db->query("INSERT INTO users (email, username, password, first_name, last_name, cedula, phone, role, status) VALUES (:email, :username, :password, :first_name, :last_name, :cedula, :phone, :role, 'active')");
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':cedula', $data['cedula']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':role', isset($data['role']) ? $data['role'] : 'Cliente');
        return $this->db->execute();
    }

    public function updateUser($data)
    {
        $this->db->query("UPDATE users SET first_name=:f, last_name=:l, email=:e, role=:r, status=:st WHERE id=:id");
        $this->db->bind(':f', $data['first_name']);
        $this->db->bind(':l', $data['last_name']);
        $this->db->bind(':e', $data['email']);
        $this->db->bind(':r', $data['role']);
        $this->db->bind(':st', $data['status']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function getAll()
    {
        $this->db->query("SELECT id, email, username, first_name, last_name, role, status, created_at FROM users");
        return $this->db->resultSet();
    }

    public function delete($id)
    {
        // Prevenir que un Administrador sea borrado por error
        $this->db->query("DELETE FROM users WHERE id = :id AND role != 'Administrador'");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Mensajes de Contacto
    public function getContactMessages()
    {
        $this->db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function updateMessageStatus($id, $status)
    {
        $this->db->query("UPDATE contact_messages SET status=:st WHERE id=:id");
        $this->db->bind(':st', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
