<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WilleStore — Премиум реселлер</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ПОЛНОЭКРАННОЕ МЕНЮ (МОБИЛЬНОЕ) -->
<div class="menu-overlay" id="menuOverlay">
    <button class="close-menu" id="closeMenu">✕</button>
    <nav class="overlay-nav">
        <a href="index.php">ГЛАВНАЯ</a>
        <a href="catalog.php">КАТАЛОГ</a>
        <a href="contacts.php">КОНТАКТЫ</a> <!-- НОВАЯ ССЫЛКА -->
        <a href="cart.php">КОРЗИНА</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php">ПРОФИЛЬ</a>
            <a href="auth/logout.php">ВЫХОД</a>
        <?php else: ?>
            <a href="auth/login.php">ВХОД</a>
        <?php endif; ?>
    </nav>
</div>

<div class="container">
    <header>
        <a href="index.php" class="logo-block">
            <img src="assets/img/logo.svg" alt="W" class="logo-img">
        </a>
        
        <div class="city-select">
            📍 <?php echo $_SESSION['city'] ?? 'Омск'; ?>
        </div>
        
        <!-- НАВИГАЦИЯ ДЛЯ ДЕКСКТОПА -->
        <div class="nav-links desktop-only">
            <a href="/ws/catalog.php">КАТАЛОГ</a>
            <a href="/ws/contacts.php">КОНТАКТЫ</a> <!-- НОВАЯ ССЫЛКА -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin/index.php" style="color: var(--blue);">АДМИН</a>
                <?php endif; ?>
                <a href="profile.php">ПРОФИЛЬ</a>
                <a href="auth/logout.php">ВЫХОД</a>
            <?php else: ?>
                <a href="auth/login.php">ВХОД</a>
            <?php endif; ?>
            <a href="cart.php" class="cart-link">КОРЗИНА (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>)</a>
        </div>

        <!-- БУРГЕР -->
        <button class="burger-btn" id="openMenu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>

    <div class="marquee">
        <div class="marquee-content">
            ★ WILLESTORE — PREMIUM RESELLER ★ ТОЛЬКО ОРИГИНАЛЬНАЯ ТЕХНИКА ★ ГАРАНТИЯ 1 ГОД ★ ДОСТАВКА В ДЕНЬ ЗАКАЗА ★ WILLESTORE — PREMIUM RESELLER ★
        </div>
    </div>