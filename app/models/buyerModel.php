<?php
// Buyer Model
// Location: OnlineAuctionSystem/models/buyerModel.php

require_once '../config/database.php';

// Get buyer dashboard stats
function getBuyerStats($conn, $buyer_id) {
    $stats = [
        'active_bids' => 0,
        'won_auctions' => 0,
        'watchlist_items' => 0
    ];
    
    // Active bids (placeholder)
    $stats['active_bids'] = 0;
    $stats['won_auctions'] = 0;
    $stats['watchlist_items'] = 0;
    
    return $stats;
}

// Get buyer's active bids
function getBuyerBids($conn, $buyer_id) {
    $sql = "SELECT * FROM bids WHERE buyer_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $buyer_id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get buyer's watchlist
function getWatchlist($conn, $buyer_id) {
    $sql = "SELECT * FROM watchlist WHERE buyer_id = ? ORDER BY added_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $buyer_id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
