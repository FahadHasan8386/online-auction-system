<?php
// Logout Controller
// Location: OnlineAuctionSystem/controllers/logoutController.php

session_start();

// Destroy all session data
session_destroy();

// Redirect to login page
header('Location: ../views/shared/login.php?logout=1');
exit();
?>
