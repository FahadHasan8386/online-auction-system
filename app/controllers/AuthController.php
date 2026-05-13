<?php
class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole();
            return;
        }
        require_once '../app/views/auth/login.php';
    }
    
    public function doLogin() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            if (!$user['is_active']) {
                $_SESSION['error'] = "Account is deactivated. Contact admin.";
                header('Location: /online-auction-system/public/login');
                return;
            }
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            
            $this->redirectBasedOnRole();
        } else {
            $_SESSION['error'] = "Invalid email or password";
            header('Location: /online-auction-system/public/login');
        }
    }
    
    private function redirectBasedOnRole() {
        $role = $_SESSION['user_role'];
        switch($role) {
            case 'buyer':
                header('Location: /online-auction-system/public/buyer/dashboard');
                break;
            case 'seller':
                header('Location: /online-auction-system/public/seller/dashboard');
                break;
            case 'moderator':
                header('Location: /online-auction-system/public/moderator/dashboard');
                break;
            case 'admin':
                header('Location: /online-auction-system/public/admin/dashboard');
                break;
            default:
                header('Location: /online-auction-system/public/');
        }
    }
    
    public function register() {
        require_once '../app/views/auth/register.php';
    }
    
    public function doRegister() {
        $errors = [];
        
        // Validation
        if (empty($_POST['name'])) $errors[] = "Name is required";
        if (empty($_POST['email'])) $errors[] = "Email is required";
        if (strlen($_POST['password']) < 6) $errors[] = "Password must be at least 6 characters";
        if ($_POST['password'] !== $_POST['confirm_password']) $errors[] = "Passwords do not match";
        
        // Check if email exists
        if ($this->userModel->findByEmail($_POST['email'])) {
            $errors[] = "Email already registered";
        }
        
        if (empty($errors)) {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'phone' => $_POST['phone'] ?? '',
                'bio' => $_POST['bio'] ?? ''
            ];
            
            if ($this->userModel->create($data)) {
                $_SESSION['success'] = "Registration successful! Please login.";
                header('Location: /online-auction-system/public/login');
            } else {
                $_SESSION['error'] = "Registration failed";
                header('Location: /online-auction-system/public/register');
            }
        } else {
            $_SESSION['errors'] = $errors;
            header('Location: /online-auction-system/public/register');
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: /online-auction-system/public/login');
    }
}
?>