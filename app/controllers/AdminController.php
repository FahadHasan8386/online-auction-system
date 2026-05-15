<?php
// Admin Controller
// Location: OnlineAuctionSystem/controllers/adminController.php

require_once '../config/database.php';
require_once '../models/adminModel.php';

session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../views/shared/login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Manage users
    if ($action == 'manage_user') {
        $user_id = intval($_POST['user_id']);
        $status = $_POST['status']; // active/inactive
        // Implementation here
    }
    
    // Approve seller
    if ($action == 'approve_seller') {
        $user_id = intval($_POST['user_id']);
        // Implementation here
    }
    
    // Manage listing
    if ($action == 'manage_listing') {
        $listing_id = intval($_POST['listing_id']);
        $action_type = $_POST['action_type']; // approve/reject/remove
        // Implementation here
    }
}
?>
