<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/online-auction-system/public/">AuctionSystem</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="navbar-nav ms-auto">
                <span class="nav-link text-white">Welcome, <?php echo $_SESSION['user_name']; ?></span>
                <a class="nav-link" href="/online-auction-system/public/logout">Logout</a>
            </div>
        <?php endif; ?>
    </div>
</nav>
<div class="container mt-4">