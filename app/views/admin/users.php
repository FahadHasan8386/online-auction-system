<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
requireRole('admin');

$users = getAllUsers($conn);
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $user = getUserById($conn, $id);
    if ($user && $user['id'] != $_SESSION['user_id']) {
        $newStatus = $user['status'] ? 0 : 1;
        toggleUserStatus($conn, $id, $newStatus);
    }
    header("Location: users.php");
    exit();
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<h1>Manage Users</h1>
<table class="admin-table">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Action</th></tr>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['name']); ?></td>
            <td><?php echo $u['email']; ?></td>
            <td><?php echo $u['role']; ?></td>
            <td><?php echo $u['status'] ? 'Active' : 'Deactivated'; ?></td>
            <td>
                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                    <a href="users.php?toggle=<?php echo $u['id']; ?>" class="btn btn-sm"><?php echo $u['status'] ? 'Deactivate' : 'Activate'; ?></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../partials/footer.php'; ?>