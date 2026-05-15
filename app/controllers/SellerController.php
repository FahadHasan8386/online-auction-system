<?php
// Seller Controller
// Location: OnlineAuctionSystem/controllers/sellerController.php

require_once '../config/database.php';
require_once '../models/sellerModel.php';

session_start();

// Check if user is logged in and is a seller
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    header('Location: ../views/shared/login.php');
    exit();
}

$seller_id = $_SESSION['user_id'];

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Create new listing
    if ($action == 'create_listing') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $starting_price = floatval($_POST['starting_price']);
        $end_time = $_POST['end_time'];
        
        // Validation and creation logic here
    }
    
    // End auction
    if ($action == 'end_auction') {
        $auction_id = intval($_POST['auction_id']);
        // Implementation here
    }
}
?>
