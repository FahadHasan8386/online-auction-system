<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ReviewModel.php';
require_once '../../models/ListingModel.php';
requireRole('buyer');

$listing_id = isset($_GET['listing_id']) ? (int)$_GET['listing_id'] : 0;
$listing = getListingById($conn, $listing_id);
if (!$listing || $listing['status'] != 'closed') {
    header("Location: mybids.php");
    exit();
}

$canReview = canReview($conn, $listing_id, $_SESSION['user_id'], $listing['seller_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $canReview) {
    $rating = (int)$_POST['rating'];
    $review_text = trim($_POST['review_text']);
    if ($rating >= 1 && $rating <= 5 && !empty($review_text)) {
        addReview($conn, $listing_id, $_SESSION['user_id'], $listing['seller_id'], $rating, $review_text);
        header("Location: mybids.php?reviewed=1");
        exit();
    }
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<h1>Write a Review for Seller: <?php echo htmlspecialchars($listing['seller_name']); ?></h1>
<p>Auction: <?php echo htmlspecialchars($listing['title']); ?></p>

<form method="POST">
    <div class="form-group">
        <label>Rating (1-5)</label>
        <select name="rating" required>
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Good</option>
            <option value="3">3 - Average</option>
            <option value="2">2 - Poor</option>
            <option value="1">1 - Very Poor</option>
        </select>
    </div>
    <div class="form-group">
        <label>Your Review</label>
        <textarea name="review_text" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit Review</button>
</form>

<?php include '../partials/footer.php'; ?>