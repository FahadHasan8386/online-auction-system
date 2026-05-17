<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
requireRole('seller');

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $listing = getListingById($conn, $id);
    if ($listing && $listing['seller_id'] == $_SESSION['user_id'] && !hasBids($conn, $id)) {
        deleteListing($conn, $id);
    }
    header("Location: manage_listings.php");
    exit();
}

$listings = getSellerListings($conn, $_SESSION['user_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<h1>Manage My Listings</h1>
<?php if (isset($_GET['created'])): ?>
    <div class="alert alert-success">Auction created successfully!</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">Auction updated successfully!</div>
<?php endif; ?>

<table class="admin-table">
    <tr><th>Title</th><th>Current Bid</th><th>Ends</th><th>Bids</th><th>Status</th><th>Actions</th></tr>
    <?php foreach ($listings as $listing): ?>
        <tr>
            <td><?php echo htmlspecialchars($listing['title']); ?></td>
            <td>$<?php echo number_format($listing['current_bid'], 2); ?></td>
            <td><?php echo date('M d, H:i', strtotime($listing['end_datetime'])); ?></td>
            <td><?php echo $listing['bid_count']; ?></td>
            <td><?php echo $listing['status']; ?></td>
            <td>
                <a href="edit_listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-sm">Edit</a>
                <?php if ($listing['bid_count'] == 0): ?>
                    <a href="manage_listings.php?delete=<?php echo $listing['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../partials/footer.php'; ?>