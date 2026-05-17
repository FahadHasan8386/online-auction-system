<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
requireRole('seller');

closeExpiredListings($conn);
$listings = getSellerListings($conn, $_SESSION['user_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<h1>Seller Dashboard</h1>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>

<h3>Your Auction Listings</h3>
<div class="listings-grid" id="seller-dashboard">
    <?php foreach ($listings as $listing): ?>
        <div class="listing-card">
            <h4><?php echo htmlspecialchars($listing['title']); ?></h4>
            <p>Current Bid: $<span id="bid-<?php echo $listing['id']; ?>"><?php echo number_format($listing['current_bid'], 2); ?></span></p>
            <p><span id="count-<?php echo $listing['id']; ?>"><?php echo $listing['bid_count']; ?></span> bids</p>
            <p>Status: <?php echo $listing['status']; ?></p>
            <a href="edit_listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-sm">Edit</a>
            <a href="manage_listings.php?delete=<?php echo $listing['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this listing?')">Delete</a>
        </div>
    <?php endforeach; ?>
</div>

<script src="../../assets/js/seller.js"></script>
<?php include '../partials/footer.php'; ?>