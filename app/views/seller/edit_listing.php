<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/CategoryModel.php';
require_once '../../includes/functions.php';
requireRole('seller');

$listing_id = (int)$_GET['id'];
$listing = getListingById($conn, $listing_id);
if (!$listing || $listing['seller_id'] != $_SESSION['user_id'] || hasBids($conn, $listing_id)) {
    header("Location: manage_listings.php");
    exit();
}

$categories = getAllCategories($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $end_datetime = $_POST['end_datetime'];

    if (strtotime($end_datetime) <= time()) {
        $message = 'End date must be in future';
    } else {
        if (updateListing($conn, $listing_id, $title, $description, $category_id, $end_datetime)) {
            header("Location: manage_listings.php?updated=1");
            exit();
        } else {
            $message = 'Update failed';
        }
    }
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<h1>Edit Listing</h1>
<?php if ($message): ?>
    <div class="alert alert-error"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($listing['title']); ?>" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="5" required><?php echo htmlspecialchars($listing['description']); ?></textarea>
    </div>
    <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $listing['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Auction End Date/Time</label>
        <input type="datetime-local" name="end_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($listing['end_datetime'])); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="manage_listings.php" class="btn">Cancel</a>
</form>

<?php include '../partials/footer.php'; ?>