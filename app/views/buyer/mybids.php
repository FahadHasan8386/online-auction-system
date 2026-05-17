<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/BidModel.php';
requireRole('buyer');

$myBids = getUserBids($conn, $_SESSION['user_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<h1>My Bidding Activity</h1>

<div class="bid-activity-card">
    <h3>Leading Bids</h3>
    <table class="bid-table">
    <tr><th>Item</th><th>Your Bid</th><th>Highest Bid</th><th>Status</th><th>Action</th></tr>
    <?php foreach ($myBids as $bid): ?>
        <?php 
        $isWinner = ($bid['winner_id'] == $_SESSION['user_id'] && $bid['status'] == 'closed');
        $isLeading = ($bid['highest_bid'] == $bid['bid_amount'] && $bid['status'] == 'active');
        ?>
        <tr>
            <td><?php echo htmlspecialchars($bid['title']); ?></td>
            <td>$<?php echo number_format($bid['bid_amount'], 2); ?></td>
            <td>$<?php echo number_format($bid['highest_bid'], 2); ?></td>
            <td>
                <?php if ($bid['status'] == 'closed'): ?>
                    <?php echo $isWinner ? 'Won' : 'Lost'; ?>
                <?php else: ?>
                    <?php echo $isLeading ? 'Leading' : 'Outbid'; ?>
                <?php endif; ?>
            </td>
            <td><a href="details.php?id=<?php echo $bid['listing_id']; ?>" class="btn btn-sm">View</a></td>
        </tr>
    <?php endforeach; ?>
</table>
</div>

<?php include '../partials/footer.php'; ?>