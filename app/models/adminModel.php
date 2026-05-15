<?php
// app/models/User.php

require_once '../../app/core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function login($email, $password) {
        $sql = "SELECT id, name, email, password_hash, role
                FROM users WHERE email = ? AND is_active = 1";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && password_verify($password, $row['password_hash'])) {
            return $row;
        }

        return null;
    }
}