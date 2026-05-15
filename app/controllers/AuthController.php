<?php
// app/controllers/AuthController.php

require_once '../../app/core/Database.php';
require_once '../../app/models/User.php';
require_once '../../app/helpers/SessionHelper.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function doLogin() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $email    = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $role     = $data['role'] ?? '';

        if (!$email || !$password || !$role) {
            echo json_encode(['success' => false, 'msg' => 'All fields required.']);
            return;
        }

        $user = $this->userModel->login($email, $password);

        if ($user === null) {
            echo json_encode(['success' => false, 'msg' => 'Invalid credentials.']);
            return;
        }

        $dbRole = $user['role'] ?? 'buyer';
        if ($role !== $dbRole) {
            echo json_encode(['success' => false, 'msg' => "Role '$role' not allowed for this account."]);
            return;
        }

        session_regenerate_id();
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['name']      = $user['name'];
        $_SESSION['email']     = $user['email'];
        $_SESSION['role']      = $dbRole;
        $_SESSION['loggedin']  = true;

        $dashUrl = $this->getDashboardUrl($dbRole);
        echo json_encode(['success' => true, 'redirect' => $dashUrl]);
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }

    public function register() {
        $name   = $_POST['name'] ?? '';
        $email  = $_POST['email'] ?? '';
        $phone  = $_POST['phone'] ?? '';
        $bio    = $_POST['bio'] ?? '';
        $pass   = $_POST['password'] ?? '';

        if (!$name || !$email || !$pass) {
            die("All required fields missing.");
        }

        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password_hash, phone, bio, role, seller_verified, is_active, reputation_score, created_at)
                VALUES (?, ?, ?, ?, ?, 'buyer', 0, 1, 100, NOW())";

        $db = new Database();
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $hashed, $phone, $bio);

        if ($stmt->execute()) {
            echo "Registration successful. <a href='/'>Login now</a>";
        } else {
            echo "Error: " . $db->getConnection()->error;
        }
    }

    private function getDashboardUrl($role) {
        $map = [
            'buyer'     => '/buyer/dashboard.php',
            'seller'    => '/seller/dashboard.php',
            'moderator' => '/moderator/dashboard.php',
            'admin'     => '/admin/dashboard.php',
        ];
        return $map[$role] ?? '/buyer/dashboard.php';
    }
}