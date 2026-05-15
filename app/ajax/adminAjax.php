<?php
// ajax/live_auction.php — Returns live bid data and current price for a listing

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    echo json_encode(['error' => 'Missing listing id']);
    exit;
}

$db = getDB();

// Listing info
$stmt = $db->prepare(
    'SELECT id, title, current_bid, end_datetime, status FROM listings WHERE id=? AND status="active"'
);
$stmt->bind_param('i', $id);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$listing) {
    echo json_encode(['error' => 'Listing not found or not active']);
    exit;
}

// Top 10 bids
$stmt = $db->prepare(
    'SELECT b.amount, b.is_auto_bid, b.created_at, u.name AS buyer_name
     FROM bids b JOIN users u ON u.id=b.buyer_id
     WHERE b.listing_id=? ORDER BY b.amount DESC LIMIT 10'
);
$stmt->bind_param('i', $id);
$stmt->execute();
$bids = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Bid count
$stmt = $db->prepare('SELECT COUNT(*) as cnt FROM bids WHERE listing_id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$cnt = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

echo json_encode([
    'listing_id'  => $listing['id'],
    'current_bid' => $listing['current_bid'],
    'end_datetime'=> $listing['end_datetime'],
    'status'      => $listing['status'],
    'bid_count'   => (int)$cnt,
    'bids'        => $bids,
]);
