<?php
// Seller Model
// Location: OnlineAuctionSystem/models/sellerModel.php

require_once '../config/database.php';

// Get seller dashboard stats
function getSellerStats($conn, $seller_id) {
    $stats = [
        'active_listings' => 0,
        'ended_auctions' => 0,
        'total_revenue' => 0
    ];
    
    // Active listings count
    $sql = "SELECT COUNT(*) as count FROM auctions WHERE seller_id = ? AND status = 'active'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $seller_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $stats['active_listings'] = $row['count'] ?? 0;
    
    return $stats;
}

// Get seller's active listings
function getSellerListings($conn, $seller_id) {
    $sql = "SELECT * FROM auctions WHERE seller_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $seller_id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Create new auction listing
function createListing($conn, $seller_id, $title, $description, $starting_price, $end_time) {
    $sql = "INSERT INTO auctions (seller_id, title, description, starting_price, current_price, end_time, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issdds", $seller_id, $title, $description, $starting_price, $starting_price, $end_time);
    
    return mysqli_stmt_execute($stmt);
}
?>
