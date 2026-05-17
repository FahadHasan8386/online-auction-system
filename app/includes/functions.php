<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[0-9]{10,15}$/', $phone);
}

function uploadFile($file, $targetDir) {
    // Basic validation
    if (!isset($file) || !isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $fileName = time() . '_' . basename($file['name']);

    // Resolve target directory relative to the caller file when a relative path is provided
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $callerDir = isset($backtrace[1]['file']) ? dirname($backtrace[1]['file']) : __DIR__;

    if (preg_match('/^(?:[A-Za-z]:|\\\\|\/)/', $targetDir)) {
        // absolute path provided
        $absTarget = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR;
    } else {
        // relative path: resolve from caller directory
        $absTarget = realpath($callerDir . DIRECTORY_SEPARATOR . $targetDir);
        if ($absTarget === false) {
            // fallback: normalize path without realpath
            $absTarget = $callerDir . DIRECTORY_SEPARATOR . $targetDir;
        }
        $absTarget = rtrim($absTarget, '/\\') . DIRECTORY_SEPARATOR;
    }

    // Ensure target directory exists
    if (!is_dir($absTarget)) {
        if (!mkdir($absTarget, 0755, true)) {
            return false;
        }
    }

    $targetPath = $absTarget . $fileName;

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedTypes) || $file['size'] > 5 * 1024 * 1024) {
        return false;
    }

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return false;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function displayMessage() {
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . '">' . 
             $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}
?>