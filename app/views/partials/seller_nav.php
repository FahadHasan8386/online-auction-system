<nav class="navbar">
    <div class="container nav-inner">
        <div class="navbar-brand-group">
            <a href="dashboard.php" class="navbar-brand">Seller Dashboard</a>
        </div>
        <div class="navbar-menu">
            <a href="create_listing.php">Create Auction</a>
            <a href="manage_listings.php">Manage Listings</a>
            <a href="bids.php">Bid Monitoring</a>
            <a href="results.php">Auction Results</a>
            <a href="profile.php">Profile</a>
            <a href="../../logout.php" class="btn btn-outline btn-sm">Logout</a>
        </div>
        <span class="user-greeting">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Seller)</span>
    </div>
</nav>