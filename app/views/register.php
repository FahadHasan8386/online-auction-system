<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Online Auction System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="register-container">
        <h2>Create New Account</h2>
        
        <?php
            $savedName = htmlspecialchars($_GET['name'] ?? '');
            $savedEmail = htmlspecialchars($_GET['email'] ?? '');
            $savedPhone = htmlspecialchars($_GET['phone'] ?? '');
            $savedRole = htmlspecialchars($_GET['role'] ?? 'buyer');
        ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <form id="registerForm" method="POST" action="../controllers/auth_controller.php?action=register">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="name" id="name" required value="<?php echo $savedName; ?>">
                <span class="error" id="nameError"></span>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="email" required value="<?php echo $savedEmail; ?>">
                <span class="error" id="emailError"></span>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" id="phone" required value="<?php echo $savedPhone; ?>">
                <span class="error" id="phoneError"></span>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" id="password" required>
                <span class="error" id="passwordError"></span>
            </div>
            
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span class="error" id="confirmError"></span>
            </div>
            
            <div class="form-group">
                <label>Register as:</label>
                <select name="role" id="role">
                    <option value="buyer" <?php echo $savedRole === 'buyer' ? 'selected' : ''; ?>>Buyer</option>
                    <option value="seller" <?php echo $savedRole === 'seller' ? 'selected' : ''; ?>>Seller</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let name = document.getElementById('name').value;
            let email = document.getElementById('email').value;
            let phone = document.getElementById('phone').value;
            let password = document.getElementById('password').value;
            let confirm = document.getElementById('confirm_password').value;
            let valid = true;
            
            if (!name) {
                document.getElementById('nameError').textContent = 'Name is required';
                valid = false;
            } else {
                document.getElementById('nameError').textContent = '';
            }
            
            if (!email) {
                document.getElementById('emailError').textContent = 'Email is required';
                valid = false;
            } else if (!email.includes('@')) {
                document.getElementById('emailError').textContent = 'Invalid email format';
                valid = false;
            } else {
                document.getElementById('emailError').textContent = '';
            }
            
            if (!phone || phone.length < 10) {
                document.getElementById('phoneError').textContent = 'Valid phone number required (min 10 digits)';
                valid = false;
            } else {
                document.getElementById('phoneError').textContent = '';
            }
            
            if (!password || password.length < 6) {
                document.getElementById('passwordError').textContent = 'Password must be at least 6 characters';
                valid = false;
            } else {
                document.getElementById('passwordError').textContent = '';
            }
            
            if (password !== confirm) {
                document.getElementById('confirmError').textContent = 'Passwords do not match';
                valid = false;
            } else {
                document.getElementById('confirmError').textContent = '';
            }
            
            if (!valid) e.preventDefault();
        });
    </script>
</body>
</html>