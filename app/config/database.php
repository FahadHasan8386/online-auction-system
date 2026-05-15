<?php
// Database configuration file
// Location: OnlineAuctionSystem/config/database.php

$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'auction_system';

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8");

// Function for prepared statements
function executeQuery($conn, $sql, $types = "", $params = []) {

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);

        return $stmt;
    }

    return false;
}

// Fetch all rows
function getResult($stmt) {

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch single row
function getSingleResult($stmt) {

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}
?>