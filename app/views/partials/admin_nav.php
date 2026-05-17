<nav class="navbar admin-navbar">
    <div class="container nav-inner">
        <div class="navbar-brand-group">
            <a href="dashboard.php" class="navbar-brand">Admin Dashboard</a>
        </div>
        <div class="navbar-menu">
            <a href="users.php">Users</a>
            <a href="listings.php">Listings</a>
            <a href="categories.php">Categories</a>
            <a href="create_admin.php">Create Admin</a>
            <a href="profile.php">Profile</a>
            <a href="../../logout.php" class="btn btn-outline btn-sm">Logout</a>
        </div>
        <span class="user-greeting">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Admin)</span>
    </div>
</nav>