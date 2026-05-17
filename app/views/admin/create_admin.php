<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../includes/functions.php';
requireRole('admin');

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if (empty($name) || empty($email) || empty($password)) {
        $message = 'All fields required';
    } elseif (!validateEmail($email)) {
        $message = 'Invalid email';
    } elseif (strlen($password) < 6) {
        $message = 'Password min 6 characters';
    } elseif ($password != $confirm) {
        $message = 'Passwords do not match';
    } elseif (getUserByEmail($conn, $email)) {
        $message = 'Email already exists';
    } else {
        if (createUser($conn, $name, $email, '0000000000', $password, 'admin')) {
            $message = 'Admin created successfully';
        } else {
            $message = 'Creation failed';
        }
    }
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<h1>Create New Admin</h1>
<?php if ($message): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>
<form method="POST">
    <div class="form-group"><label>Name</label><input type="text" name="name" required></div>
    <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
    <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
    <div class="form-group"><label>Confirm Password</label><input type="password" name="confirm_password" required></div>
    <button type="submit" class="btn btn-primary">Create Admin</button>
</form>

<?php include '../partials/footer.php'; ?>