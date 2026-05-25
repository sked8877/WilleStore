<?php
session_start();

// Проверка: авторизован ли пользователь
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Редирект, если не авторизован
function require_auth() {
    if (!is_logged_in()) {
        header("Location: /auth/login.php");
        exit;
    }
}
?>