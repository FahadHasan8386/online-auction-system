<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../includes/functions.php';
requireRole('seller');

$user = getUserById($conn, $_SESSION['user_id']);
$message = '';
// Same update logic as buyer profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = sanitize($_POST['name']);
        $phone = sanitize($_POST['phone']);
        if (updateUserProfile($conn, $_SESSION['user_id'], $name, $phone)) {
            $_SESSION['user_name'] = $name;
            $message = 'Profile updated';
        }
    } elseif (isset($_POST['change_password'])) {
        $old = $_POST['old_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        if (password_verify($old, $user['password'])) {
            if ($new == $confirm && strlen($new) >= 6) {
                updatePassword($conn, $_SESSION['user_id'], $new);
                $message = 'Password changed';
            } else { $message = 'Password mismatch'; }
        } else { $message = 'Old password incorrect'; }
    } elseif (isset($_FILES['profile_pic'])) {
        $filename = uploadFile($_FILES['profile_pic'], '../../uploads/profiles/');
        if ($filename && updateProfilePic($conn, $_SESSION['user_id'], $filename)) {
            $message = 'Picture updated';
            $user['profile_pic'] = $filename;
        }
    }
    $user = getUserById($conn, $_SESSION['user_id']);
}
?>
<?php include '../partials/header.php'; ?>

<?php include '../partials/seller_nav.php'; ?>
<h1>My Profile</h1>
<div class="profile-page">
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <div class="profile-card">
        <div class="profile-pic">
            <img src="/auction-system/uploads/profiles/<?php echo $user['profile_pic'] ?: 'default.png'; ?>" width="150">
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_pic" accept="image/*">
                <button type="submit" class="btn btn-sm">Upload</button>
            </form>
        </div>
    </div>

    <div class="profile-card profile-form">
        <form method="POST">
            <h3>Update Profile</h3>
            <div class="form-group"><label>Name</label><input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required></div>
    <div class="form-group"><label>Email</label><input type="email" value="<?php echo $user['email']; ?>" disabled></div>
    <div class="form-group"><label>Phone</label><input type="text" name="phone" value="<?php echo $user['phone']; ?>" required></div>
    <button type="submit" name="update_profile" class="btn btn-primary">Update</button>
        </form>
    </div>

    <div class="profile-card profile-form">
        <form method="POST">
            <h3>Change Password</h3>
            <div class="form-group"><label>Old Password</label><input type="password" name="old_password" required></div>
    <div class="form-group"><label>New Password (min 6)</label><input type="password" name="new_password" required></div>
    <div class="form-group"><label>Confirm</label><input type="password" name="confirm_password" required></div>
    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</div>
<?php include '../partials/footer.php'; ?>
