<?php
// ajax/notif_count.php — Returns unread notification count for logged-in user

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['count' => 0]);
    exit;
}

$uid  = currentUser()['id'];
$db   = getDB();
$stmt = $db->prepare('SELECT COUNT(*) as cnt FROM notifications WHERE user_id=? AND is_read=0');
$stmt->bind_param('i', $uid);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo json_encode(['count' => (int)($row['cnt'] ?? 0)]);
