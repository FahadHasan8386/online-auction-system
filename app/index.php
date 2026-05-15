<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload classes
spl_autoload_function(function($className) {
    $paths = [
        __DIR__ . '/../app/controllers/' . $className . '.php',
        __DIR__ . '/../app/models/' . $className . '.php',
        __DIR__ . '/../app/core/' . $className . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];
$request_uri = str_replace('/online-auction-system/public', '', $request_uri);
$request_uri = parse_url($request_uri, PHP_URL_PATH);
$request_uri = trim($request_uri, '/');

// Simple routing
switch($request_uri) {
    case 'login':
        // Handle login form display
        require_once __DIR__ . '/../app/views/layouts/header.php';
        require_once __DIR__ . '/../app/views/auth/login.php';
        require_once __DIR__ . '/../app/views/layouts/footer.php';
        break;
        
    case 'api/login':
        // Handle AJAX login
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $auth = new AuthController();
        $auth->doLogin();
        break;
        
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/AuthController.php';
            $auth = new AuthController();
            $auth->register();
        } else {
            require_once __DIR__ . '/../app/views/layouts/header.php';
            require_once __DIR__ . '/../app/views/auth/register.php';
            require_once __DIR__ . '/../app/views/layouts/footer.php';
        }
        break;
        
    case 'logout':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $auth = new AuthController();
        $auth->logout();
        break;
        
    case 'buyer/dashboard':
        require_once __DIR__ . '/../app/controllers/BuyerController.php';
        $controller = new BuyerController();
        $controller->dashboard();
        break;
        
    case 'seller/dashboard':
        require_once __DIR__ . '/../app/controllers/SellerController.php';
        $controller = new SellerController();
        $controller->dashboard();
        break;
        
    case 'moderator/dashboard':
        require_once __DIR__ . '/../app/controllers/ModeratorController.php';
        $controller = new ModeratorController();
        $controller->dashboard();
        break;
        
    case 'admin/dashboard':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->dashboard();
        break;
        
    case '':
    case 'home':
        // Home page
        require_once __DIR__ . '/../app/views/layouts/header.php';
        echo '<div class="text-center mt-5">';
        echo '<h1>Welcome to Online Auction System</h1>';
        echo '<p class="lead">Buy, Sell, and Bid on amazing items!</p>';
        if (!isset($_SESSION['user_id'])) {
            echo '<a href="/online-auction-system/public/login" class="btn btn-primary mx-2">Login</a>';
            echo '<a href="/online-auction-system/public/register" class="btn btn-success mx-2">Register</a>';
        } else {
            echo '<p>Welcome ' . htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['name'] ?? 'User') . '!</p>';
            echo '<a href="/online-auction-system/public/logout" class="btn btn-danger">Logout</a>';
        }
        echo '</div>';
        require_once __DIR__ . '/../app/views/layouts/footer.php';
        break;
        
    default:
        // 404 - Page not found
        http_response_code(404);
        require_once __DIR__ . '/../app/views/layouts/header.php';
        echo '<div class="alert alert-danger text-center mt-5">';
        echo '<h2>404 - Page Not Found</h2>';
        echo '<p>The page you are looking for does not exist.</p>';
        echo '<a href="/online-auction-system/public/" class="btn btn-primary">Go Home</a>';
        echo '</div>';
        require_once __DIR__ . '/../app/views/layouts/footer.php';
}
?>