<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/CategoryModel.php';
require_once '../../models/WatchlistModel.php';

requireRole('buyer');
$user_id = $_SESSION['user_id'];

closeExpiredListings($conn);
$categories = getAllCategories($conn);
$activeListings = getActiveListings($conn, 10);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>

<div class="dashboard-content">
    <div class="search-section">
        <h3>Search Auctions</h3>
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
            <!-- AJAX results will appear here -->
        </div>
    </div>
    
    <div class="active-auctions">
        <h3>Active Auctions</h3>
        <div class="listings-grid">
            <?php foreach ($activeListings as $listing): ?>
                <div class="listing-card">
                    <img src="/auction-system/uploads/listings/<?php echo $listing['image'] ?: 'default.jpg'; ?>" alt="<?php echo $listing['title']; ?>">
                    <h4><?php echo htmlspecialchars($listing['title']); ?></h4>
                    <p>Current Bid: $<?php echo number_format($listing['current_bid'], 2); ?></p>
                    <p>Category: <?php echo $listing['category_name']; ?></p>
                    <a href="details.php?id=<?php echo $listing['id']; ?>" class="btn btn-sm">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="../../assets/js/buyer.js"></script>
<?php include '../partials/footer.php'; ?>