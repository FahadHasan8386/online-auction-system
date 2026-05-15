<?php
// Admin Model
// Location: OnlineAuctionSystem/models/adminModel.php

require_once '../config/database.php';

// Get dashboard statistics
function getDashboardStats($conn) {
    $stats = [
        'total_users' => 0,
        'total_listings' => 0,
        'total_bids' => 0,
        'platform_revenue' => 0
    ];
    
    // Total users
    $sql = "SELECT COUNT(*) as count FROM users";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $stats['total_users'] = $row['count'];
    }
    
    return $stats;
}

// Get all users
function getAllUsers($conn) {
    $sql = "SELECT * FROM users ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}

// Get seller requests
function getSellerRequests($conn) {
    $sql = "SELECT * FROM users WHERE role = 'seller' AND seller_verified = 0";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}
?>
