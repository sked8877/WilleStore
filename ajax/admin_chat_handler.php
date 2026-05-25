<?php
session_start();
require_once '../includes/db.php';

// Только админ имеет доступ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

// ОТПРАВКА ОТВЕТА
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $msg = trim($_POST['message']);
    $user_id = (int)$_POST['user_id'];
    
    if (!empty($msg)) {
        $stmt = $pdo->prepare("INSERT INTO support_messages (user_id, message, is_from_admin) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $msg]);
    }
    exit;
}

// ПОЛУЧЕНИЕ ИСТОРИИ
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];
    
    $stmt = $pdo->prepare("SELECT * FROM support_messages WHERE user_id = ? ORDER BY created_at ASC");
    $stmt->execute([$user_id]);
    $messages = $stmt->fetchAll();

    foreach ($messages as $m) {
        // Если сообщение от админа — красим в синий (или белый), если от юзера — наоборот
        $class = $m['is_from_admin'] ? 'admin' : 'user';
        $sender = $m['is_from_admin'] ? 'ВЫ (SUPPORT)' : 'КЛИЕНТ';
        
        // Используем те же стили .msg что и в контактах
        echo "<div class='msg $class'><strong>$sender:</strong><br>{$m['message']}</div>";
    }
}