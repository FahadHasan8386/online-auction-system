<nav class="navbar">
    <div class="container nav-inner">
        <div class="navbar-brand-group">
            <a href="dashboard.php" class="navbar-brand">Buyer Dashboard</a>
        </div>
        <div class="navbar-menu">
            <a href="browse.php">Browse Auctions</a>
            <a href="watchlist.php">Watchlist</a>
            <a href="mybids.php">My Bids</a>
            <a href="profile.php">Profile</a>
            <a href="../../logout.php" class="btn btn-outline btn-sm">Logout</a>
        </div>
        <span class="user-greeting">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Buyer)</span>
    </div>
</nav>