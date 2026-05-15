<?php
// Main index file
// Location: OnlineAuctionSystem/index.php

session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: views/admin/adminDashboard.php');
        exit();
    } elseif ($_SESSION['role'] == 'buyer') {
        header('Location: views/buyer/buyerdashboard.php');
        exit();
    } elseif ($_SESSION['role'] == 'seller') {
        header('Location: views/seller/sellerDashboard.php');
        exit();
    } elseif ($_SESSION['role'] == 'moderator') {
        header('Location: views/moderator/moderatorDashboard.php');
        exit();
    }
}

header('Location: views/shared/login.php');
exit();