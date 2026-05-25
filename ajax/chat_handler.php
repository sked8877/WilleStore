<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) exit;

$user_id = $_SESSION['user_id'];

// ОТПРАВКА
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $msg = trim($_POST['message']);
    if (!empty($msg)) {
        $stmt = $pdo->prepare("INSERT INTO support_messages (user_id, message) VALUES (?, ?)");
        $stmt->execute([$user_id, $msg]);
    }
    exit;
}

// ПОЛУЧЕНИЕ
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM support_messages WHERE user_id = ? ORDER BY created_at ASC");
    $stmt->execute([$user_id]);
    $messages = $stmt->fetchAll();

    foreach ($messages as $m) {
        $class = $m['is_from_admin'] ? 'admin' : 'user';
        $sender = $m['is_from_admin'] ? 'SUPPORT' : 'YOU';
        echo "<div class='msg $class'><strong>$sender:</strong><br>{$m['message']}</div>";
    }
}