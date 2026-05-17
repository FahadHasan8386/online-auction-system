<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/CategoryModel.php';
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) addCategory($conn, $name);
    header("Location: categories.php");
    exit();
}
if (isset($_GET['delete'])) {
    deleteCategory($conn, (int)$_GET['delete']);
    header("Location: categories.php");
    exit();
}
$categories = getAllCategories($conn);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<h1>Manage Categories</h1>
<form method="POST" style="margin-bottom:20px;">
    <input type="text" name="name" placeholder="New category name" required>
    <button type="submit" name="add" class="btn btn-primary">Add Category</button>
</form>
<table class="admin-table">
    <tr><th>ID</th><th>Name</th><th>Action</th></tr>
    <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?php echo $cat['id']; ?></td>
            <td><?php echo htmlspecialchars($cat['name']); ?></td>
            <td><a href="categories.php?delete=<?php echo $cat['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete category?')">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<script src="../../assets/js/admin.js"></script>
<?php include '../partials/footer.php'; ?>