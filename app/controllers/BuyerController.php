<?php
// Buyer Controller
// Location: OnlineAuctionSystem/controllers/buyerController.php

require_once '../config/database.php';
require_once '../models/buyerModel.php';

session_start();

// Check if user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../views/shared/login.php');
    exit();
}

$buyer_id = $_SESSION['user_id'];

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Add to watchlist
    if ($action == 'add_to_watchlist') {
        $auction_id = intval($_POST['auction_id']);
        // Implementation here
    }
    
    // Place bid
    if ($action == 'place_bid') {
        $auction_id = intval($_POST['auction_id']);
        $bid_amount = floatval($_POST['bid_amount']);
        // Implementation here
    }
}
?>
