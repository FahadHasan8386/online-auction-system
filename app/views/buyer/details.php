<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
require_once '../../models/WatchlistModel.php';
requireRole('buyer');

$listing_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$listing = getListingById($conn, $listing_id);
if (!$listing) {
    header("Location: browse.php");
    exit();
}

$bidHistory = getBidHistory($conn, $listing_id);
$inWatchlist = isInWatchlist($conn, $_SESSION['user_id'], $listing_id);

// Handle watchlist toggle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_watchlist'])) {
    if ($inWatchlist) {
        removeFromWatchlist($conn, $_SESSION['user_id'], $listing_id);
        $inWatchlist = false;
    } else {
        addToWatchlist($conn, $_SESSION['user_id'], $listing_id);
        $inWatchlist = true;
    }
    header("Location: details.php?id=$listing_id");
    exit();
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<div class="auction-details">
    <div class="detail-image">
        <img src="/auction-system/uploads/listings/<?php echo $listing['image'] ?: 'default.jpg'; ?>" alt="<?php echo $listing['title']; ?>">
    </div>
    <div class="detail-info">
        <h1><?php echo htmlspecialchars($listing['title']); ?></h1>
        <p class="category">Category: <?php echo $listing['category_name']; ?></p>
        <p class="seller">Seller: <?php echo htmlspecialchars($listing['seller_name']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($listing['description'])); ?></p>

        <div class="detail-meta">
            <span>
                Starting Price
                <strong>$<?php echo number_format($listing['starting_price'], 2); ?></strong>
            </span>
            <span>
                Current Bid
                <strong>$<span id="currentBid"><?php echo number_format($listing['current_bid'], 2); ?></span></strong>
            </span>
            <span>
                Bids
                <strong><?php echo count($bidHistory); ?></strong>
            </span>
            <span>
                Ends
                <strong><span id="countdown"><?php echo $listing['end_datetime']; ?></span></strong>
            </span>
        </div>

        <?php if ($listing['status'] == 'active' && strtotime($listing['end_datetime']) > time() && $listing['seller_id'] != $_SESSION['user_id']): ?>
            <form id="bidForm" class="bid-section" onsubmit="event.preventDefault(); placeBid(<?php echo $listing_id; ?>, document.getElementById('bidAmount').value);">
                <input type="number" id="bidAmount" step="0.01" min="<?php echo $listing['current_bid'] + 0.01; ?>" placeholder="Enter bid amount">
                <button type="submit" class="btn btn-primary">Place Bid</button>
            </form>
        <?php elseif ($listing['status'] == 'closed'): ?>
            <p class="alert alert-info">This auction has ended.</p>
        <?php endif; ?>
        
        <form method="POST">
            <button type="submit" name="toggle_watchlist" class="btn btn-secondary">
                <?php echo $inWatchlist ? 'Remove from Watchlist' : 'Add to Watchlist'; ?>
            </button>
        </form>
    </div>
</div>

<div class="bid-history-card">
    <h3>Bid History</h3>
    <table class="bid-table">
    <tr><th>Bidder</th><th>Amount</th><th>Time</th></tr>
    <?php foreach ($bidHistory as $bid): ?>
        <tr>
            <td><?php echo htmlspecialchars($bid['buyer_name']); ?></td>
            <td>$<?php echo number_format($bid['bid_amount'], 2); ?></td>
            <td><?php echo date('M d, H:i', strtotime($bid['created_at'])); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</div>

<script>
function placeBid(listingId, amount) {
    if (!amount || amount <= <?php echo $listing['current_bid']; ?>) {
        alert('Bid must be higher than current bid');
        return;
    }
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '../../ajax/place_bid.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        let resp = JSON.parse(this.responseText);
        alert(resp.message);
        if (resp.success) location.reload();
    };
    xhr.send('listing_id=' + listingId + '&bid_amount=' + amount);
}

// Countdown timer
function updateCountdown(endTime) {
    let now = new Date().getTime();
    let distance = new Date(endTime).getTime() - now;
    if (distance < 0) {
        document.getElementById('countdown').innerHTML = "Auction ended";
        return;
    }
    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((distance % (1000 * 60)) / 1000);
    document.getElementById('countdown').innerHTML = hours + "h " + minutes + "m " + seconds + "s";
    setTimeout(() => updateCountdown(endTime), 1000);
}
updateCountdown("<?php echo $listing['end_datetime']; ?>");
</script>

<?php include '../partials/footer.php'; ?>