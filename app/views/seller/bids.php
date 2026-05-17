<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
requireRole('seller');

$listings = getSellerListings($conn, $_SESSION['user_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<section class="bid-monitoring-page container">
    <h1>Bid Monitoring</h1>
    <?php if (empty($listings)): ?>
        <div class="alert alert-info">You have no active listings with bids yet.</div>
    <?php endif; ?>
    <div id="liveBids" class="bids-grid">
    <?php foreach ($listings as $listing): ?>
        <div class="bid-card">
            <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
            <p>Highest Bid: <strong>$<span id="high-<?php echo $listing['id']; ?>"><?php echo number_format($listing['current_bid'], 2); ?></span></strong></p>
            <p>Total Bids: <span id="total-<?php echo $listing['id']; ?>"><?php echo $listing['bid_count']; ?></span></p>
                <p>Status: <span class="status-badge status-<?php echo strtolower($listing['status']); ?>"><?php echo htmlspecialchars(ucfirst($listing['status'])); ?></span></p>
            <a href="results.php?listing=<?php echo $listing['id']; ?>" class="btn btn-sm">View Details</a>
        </div>
    <?php endforeach; ?>
    </div>
</section>

<script src="../../assets/js/seller.js"></script>
<?php include '../partials/footer.php'; ?>