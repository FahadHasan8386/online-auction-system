<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'buyer') header("Location: buyer/dashboard.php");
    elseif ($_SESSION['role'] == 'seller') header("Location: seller/dashboard.php");
    else header("Location: admin/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Auction System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login to Auction System</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">Invalid email or password</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">Registration successful! Please login.</div>
        <?php endif; ?>
        <?php
            // If redirected from logout, ensure fields are empty; otherwise preserve email on failed login
            $savedEmail = '';
            if (!isset($_GET['logout']) && isset($_GET['email'])) {
                $savedEmail = htmlspecialchars($_GET['email']);
            }
        ?>

        <form id="loginForm" method="POST" action="../controllers/auth_controller.php?action=login" autocomplete="email">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="email" required value="<?php echo $savedEmail; ?>" autocomplete="off" >
                <span class="error" id="emailError"></span>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" id="password" required autocomplete="off">
                <span class="error" id="passwordError"></span>
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
    
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            let valid = true;
            
            if (!email) {
                document.getElementById('emailError').textContent = 'Email is required';
                valid = false;
            } else if (!email.includes('@')) {
                document.getElementById('emailError').textContent = 'Invalid email format';
                valid = false;
            } else {
                document.getElementById('emailError').textContent = '';
            }
            
            if (!password) {
                document.getElementById('passwordError').textContent = 'Password is required';
                valid = false;
            } else {
                document.getElementById('passwordError').textContent = '';
            }
            
            if (!valid) e.preventDefault();
        });
    </script>
    <script>
        // If arrived via logout flag, clear any autofilled values (works around browser autofill)
        (function(){
            var fromLogout = <?php echo isset($_GET['logout']) ? 'true' : 'false'; ?>;
            if (fromLogout) {
                setTimeout(function(){
                    var e = document.getElementById('email');
                    var p = document.getElementById('password');
                    if (e) e.value = '';
                    if (p) p.value = '';
                }, 50);
            }
        })();
    </script>
</body>
</html>