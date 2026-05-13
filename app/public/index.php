<?php
session_start();
require_once '../app/config/database.php';
require_once '../app/core/Router.php';

// Autoload controllers (simple version)
spl_autoload_register(function($className) {
    if (file_exists("../app/controllers/$className.php")) {
        require_once "../app/controllers/$className.php";
    }
});

$router = new Router();
require_once '../routes/web.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Remove base path if your project is in a subfolder
$uri = str_replace('/online-auction-system/public/', '', $uri);
if ($uri === '') $uri = '/';

$router->dispatch($method, $uri);
?>