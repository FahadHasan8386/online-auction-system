<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/WatchlistModel.php';
requireRole('buyer');

$watchlist = getWatchlist($conn, $_SESSION['user_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<h1>My Watchlist</h1>
<div class="listings-grid">
    <?php foreach ($watchlist as $item): ?>
        <div class="listing-card">
            <h4><?php echo htmlspecialchars($item['title']); ?></h4>
            <p>Current Bid: $<?php echo number_format($item['current_bid'], 2); ?></p>
            <a href="details.php?id=<?php echo $item['id']; ?>" class="btn btn-sm">View</a>
            <a href="watchlist.php?remove=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove from watchlist?')">Remove</a>
        </div>
    <?php endforeach; ?>
</div>

<?php
if (isset($_GET['remove'])) {
    removeFromWatchlist($conn, $_SESSION['user_id'], (int)$_GET['remove']);
    header("Location: watchlist.php");
    exit();
}
?>

<?php include '../partials/footer.php'; ?>