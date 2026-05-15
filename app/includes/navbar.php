<?php
// Navbar include file
// Location: OnlineAuctionSystem/includes/navbar.php

if (!isset($_SESSION['user_id'])) {
?>
<nav class="navbar">
    <div class="nav-brand">
        <h2>🏆 Online Auction System</h2>
    </div>

    <div class="nav-links">
        <a href="../../index.php">Home</a>
        <a href="../../views/shared/login.php">Login</a>
        <a href="../../views/shared/register.php">Register</a>
    </div>
</nav>

<?php
} else {

    $role = $_SESSION['role'];
?>

<nav class="navbar">

    <div class="nav-brand">
        <h2>🏆 Online Auction System</h2>
    </div>

    <div class="nav-links">

        <?php if ($role == 'buyer') { ?>

            <a href="../../views/buyer/buyerdashboard.php">Dashboard</a>

            <a href="../../views/buyer/browseAuctions.php">Browse Auctions</a>

            <a href="../../views/buyer/watchlist.php">Watchlist</a>

            <a href="../../views/buyer/myBids.php">My Bids</a>

            <a href="../../views/buyer/profile.php">Profile</a>

        <?php } elseif ($role == 'seller') { ?>

            <a href="../../views/seller/sellerDashboard.php">Dashboard</a>

            <a href="../../views/seller/createListing.php">Create Listing</a>

            <a href="../../views/seller/myListings.php">My Listings</a>

            <a href="../../views/seller/endedAuctions.php">Ended Auctions</a>

            <a href="../../views/seller/profile.php">Profile</a>

        <?php } elseif ($role == 'moderator') { ?>

            <a href="../../views/moderator/moderatorDashboard.php">Dashboard</a>

            <a href="../../views/moderator/reviewListings.php">Review Listings</a>

            <a href="../../views/moderator/listingReports.php">Listing Reports</a>

            <a href="../../views/moderator/userReports.php">User Reports</a>

            <a href="../../views/moderator/categories.php">Categories</a>

        <?php } elseif ($role == 'admin') { ?>

            <a href="../../views/admin/adminDashboard.php">Dashboard</a>

            <a href="../../views/admin/manageUsers.php">Manage Users</a>

            <a href="../../views/admin/manageSellers.php">Seller Requests</a>

            <a href="../../views/admin/manageListings.php">Listings</a>

            <a href="../../views/admin/financialReports.php">Financial Reports</a>

            <a href="../../views/admin/platformAnalytics.php">Analytics</a>

        <?php } ?>

        <a href="../../controllers/logoutController.php">Logout</a>

    </div>

    <div class="user-info">
        Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
    </div>

</nav>

<?php } ?>