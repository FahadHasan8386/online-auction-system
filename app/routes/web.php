<?php
// Public routes
$router->add('GET', '/', 'Home', 'index');
$router->add('GET', '/login', 'Auth', 'login');
$router->add('POST', '/login', 'Auth', 'doLogin');
$router->add('GET', '/register', 'Auth', 'register');
$router->add('POST', '/register', 'Auth', 'doRegister');
$router->add('GET', '/logout', 'Auth', 'logout');

// Buyer routes (will add more)
$router->add('GET', '/buyer/dashboard', 'Buyer', 'dashboard');

// Seller routes
$router->add('GET', '/seller/dashboard', 'Seller', 'dashboard');

// Moderator routes
$router->add('GET', '/moderator/dashboard', 'Moderator', 'dashboard');

// Admin routes
$router->add('GET', '/admin/dashboard', 'Admin', 'dashboard');
?>