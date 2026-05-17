<?php
require_once '../includes/session.php';
require_once '../config/database.php';
require_once '../models/BidModel.php';
require_once '../models/ListingModel.php';
header('Content-Type: application/json');

if (!isLoggedIn() || !isBuyer()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $listing_id = $_POST['listing_id'];
    $bid_amount = $_POST['bid_amount'];
    $buyer_id = $_SESSION['user_id'];
    
    $listing = getListingById($conn, $listing_id);
    
    if (!$listing || $listing['status'] != 'active' || strtotime($listing['end_datetime']) < time()) {
        echo json_encode(['success' => false, 'message' => 'Auction is no longer active']);
        exit();
    }
    
    $current_bid = $listing['current_bid'];
    if ($bid_amount <= $current_bid) {
        echo json_encode(['success' => false, 'message' => 'Bid must be higher than current bid']);
        exit();
    }
    
    if ($listing['seller_id'] == $buyer_id) {
        echo json_encode(['success' => false, 'message' => 'You cannot bid on your own listing']);
        exit();
    }
    
    if (placeBid($conn, $listing_id, $buyer_id, $bid_amount)) {
        echo json_encode(['success' => true, 'message' => 'Bid placed successfully!', 'new_bid' => $bid_amount]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to place bid']);
    }
}
?>