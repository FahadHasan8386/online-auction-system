<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
requireRole('admin');

$totalUsers = getTotalUsers($conn);
$totalSellers = count(getAllUsers($conn, 'seller'));
$totalBuyers = count(getAllUsers($conn, 'buyer'));
$totalListings = getTotalListings($conn);
$totalBids = getTotalBids($conn);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<section class="admin-dashboard-page">
    <div class="dashboard-hero">
        <div class="dashboard-hero-text">
            <p class="dashboard-subtitle">Administrator Dashboard</p>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <p class="dashboard-description">See your auction platform performance at a glance and manage users, listings, categories, and admins from one place.</p>
        </div>
        
    </div>

    <div class="dashboard-stats">
        <div class="dashboard-card">
            <div class="stat-value"><?php echo $totalUsers; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="dashboard-card">
            <div class="stat-value"><?php echo $totalSellers; ?></div>
            <div class="stat-label">Total Sellers</div>
        </div>
        <div class="dashboard-card">
            <div class="stat-value"><?php echo $totalBuyers; ?></div>
            <div class="stat-label">Total Buyers</div>
        </div>
        <div class="dashboard-card">
            <div class="stat-value"><?php echo $totalListings; ?></div>
            <div class="stat-label">Total Listings</div>
        </div>
        <div class="dashboard-card">
            <div class="stat-value"><?php echo $totalBids; ?></div>
            <div class="stat-label">Total Bids</div>
        </div>
    </div>
</section>

<?php include '../partials/footer.php'; ?>