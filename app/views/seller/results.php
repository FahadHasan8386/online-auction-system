<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
requireRole('seller');

$listing_id = isset($_GET['listing']) ? (int)$_GET['listing'] : 0;
$listing = getListingById($conn, $listing_id);
if (!$listing || $listing['seller_id'] != $_SESSION['user_id']) {
    header("Location: dashboard.php");
    exit();
}

$bids = getBidHistory($conn, $listing_id);
$winner = null;
if (!empty($bids)) {
    $winner = $bids[0]; // highest bid
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<h1>Auction Results: <?php echo htmlspecialchars($listing['title']); ?></h1>
<p>Status: <?php echo $listing['status']; ?></p>
<p>Starting Price: $<?php echo $listing['starting_price']; ?></p>
<p>Final Bid: $<?php echo number_format($listing['current_bid'], 2); ?></p>

<?php if ($winner): ?>
    <h3>Winning Buyer</h3>
    <p>Name: <?php echo htmlspecialchars($winner['buyer_name']); ?></p>
    <p>Amount: $<?php echo number_format($winner['bid_amount'], 2); ?></p>
    <a href="add_review.php?listing_id=<?php echo $listing_id; ?>&buyer_id=<?php echo $winner['buyer_id']; ?>" class="btn btn-primary">Review Buyer</a>
<?php else: ?>
    <p>No bids placed on this auction.</p>
<?php endif; ?>

<a href="dashboard.php" class="btn">Back to Dashboard</a>

<?php include '../partials/footer.php'; ?>