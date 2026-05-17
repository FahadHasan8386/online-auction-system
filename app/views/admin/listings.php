<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
requireRole('admin');

if (isset($_GET['delete'])) {
    deleteListing($conn, (int)$_GET['delete']);
    header("Location: listings.php");
    exit();
}
$listings = getAllListings($conn);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<h1>All Listings</h1>
<table class="admin-table">
    <tr><th>ID</th><th>Title</th><th>Seller</th><th>Category</th><th>Current Bid</th><th>Status</th><th>Action</th></tr>
    <?php foreach ($listings as $l): ?>
        <tr>
            <td><?php echo $l['id']; ?></td>
            <td><?php echo htmlspecialchars($l['title']); ?></td>
            <td><?php echo htmlspecialchars($l['seller_name']); ?></td>
            <td><?php echo $l['category_name']; ?></td>
            <td>$<?php echo number_format($l['current_bid'], 2); ?></td>
            <td><?php echo $l['status']; ?></td>
            <td><a href="listings.php?delete=<?php echo $l['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this listing?')">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../partials/footer.php'; ?>