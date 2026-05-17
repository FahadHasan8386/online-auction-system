<?php
require_once '../config/database.php';
require_once '../models/ListingModel.php';
require_once '../includes/session.php';
if (!isLoggedIn() || !isBuyer()) {
    echo json_encode([]);
    exit();
}

$category = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

$listings = getActiveListings($conn, null, $category, $search);
echo json_encode($listings);
?>