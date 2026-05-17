<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/CategoryModel.php';
requireRole('buyer');

closeExpiredListings($conn);
$categories = getAllCategories($conn);
$activeListings = getActiveListings($conn);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<h1>Browse All Auctions</h1>

<div class="search-filters">
    <input type="text" id="searchInput" placeholder="Search by title...">
    <select id="categoryFilter">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <button id="searchBtn" class="btn btn-primary">Search</button>
</div>

<div id="searchResults">
    <div class="listings-grid">
        <?php foreach ($activeListings as $listing): ?>
            <div class="listing-card">
                <img src="/auction-system/uploads/listings/<?php echo $listing['image'] ?: 'default.jpg'; ?>" alt="<?php echo $listing['title']; ?>">
                <h4><?php echo htmlspecialchars($listing['title']); ?></h4>
                <p>Current Bid: $<?php echo number_format($listing['current_bid'], 2); ?></p>
                <p>Ends: <?php echo date('M d, H:i', strtotime($listing['end_datetime'])); ?></p>
                <a href="details.php?id=<?php echo $listing['id']; ?>" class="btn btn-sm">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="../../assets/js/buyer.js"></script>
<?php include '../partials/footer.php'; ?>