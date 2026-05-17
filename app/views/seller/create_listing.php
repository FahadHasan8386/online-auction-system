<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/CategoryModel.php';
require_once '../../includes/functions.php';
requireRole('seller');

$categories = getAllCategories($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $starting_price = (float)$_POST['starting_price'];
    $end_datetime = $_POST['end_datetime'];
    $image = '';

    // Validate
    $errors = [];
    if (empty($title)) $errors[] = 'Title required';
    if ($starting_price <= 0) $errors[] = 'Starting price must be > 0';
    if (strtotime($end_datetime) <= time()) $errors[] = 'End date must be in future';
    if (!empty($_FILES['image']['name'])) {
        $img = uploadFile($_FILES['image'], '../../uploads/listings/');
        if ($img) $image = $img;
        else $errors[] = 'Image upload failed';
    }

    if (empty($errors)) {
        if (createListing($conn, $_SESSION['user_id'], $category_id, $title, $description, $starting_price, $end_datetime, $image)) {
            header("Location: manage_listings.php?created=1");
            exit();
        } else {
            $message = 'Failed to create listing';
        }
    } else {
        $message = implode(', ', $errors);
    }
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<h1>Create New Auction</h1>
<?php if ($message): ?>
    <div class="alert alert-error"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="5" required></textarea>
    </div>
    <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Starting Price ($)</label>
        <input type="number" step="0.01" name="starting_price" required>
    </div>
    <div class="form-group">
        <label>Auction End Date/Time</label>
        <input type="datetime-local" name="end_datetime" required>
    </div>
    <div class="form-group">
        <label>Product Image</label>
        <input type="file" name="image" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Create Auction</button>
</form>

<?php include '../partials/footer.php'; ?>