<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Получаем 4 последних товара для блока "Новинки"
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 4");
$new_products = $stmt->fetchAll();

include 'includes/header.php';
?>

<main>
    <!-- 1. HERO SECTION: СТАТУС ПРЕМИУМ-РЕСЕЛЛЕРА -->
    <section style="padding: 100px 40px; border-bottom: var(--border); background: var(--white);">
        <h1 style="font-size: clamp(50px, 12vw, 150px); font-weight: 900; line-height: 0.8; text-transform: uppercase; letter-spacing: -0.05em;">
            WILLESTORE<br><span style="color: var(--blue);">PREMIUM</span><br>RESELLER
        </h1>
        <p style="margin-top: 40px; font-size: 24px; font-weight: 800; max-width: 800px; text-transform: uppercase; line-height: 1.1;">
            Официальный интернет-магазин техники в России. Только оригинальный продукт и профессиональная поддержка.
        </p>
    </section>

    <!-- 2. ГЕОГРАФИЯ ДОСТАВКИ -->
    <section class="city-grid">
        <div class="city-label">СВОЯ ДОСТАВКА:</div>
        <?php 
        $cities = ['МОСКВА', 'САНКТ-ПЕТЕРБУРГ', 'НОВОСИБИРСК', 'ЕКАТЕРИНБУРГ', 'НИЖНИЙ ТАГИЛ', 'УФА', 'ОМСК', 'НИЖНИЙ НОВГОРОД', 'ИРКУТСК'];
        foreach ($cities as $city): ?>
            <div class="city-item"><?php echo $city; ?></div>
        <?php endforeach; ?>
    </section>

    <!-- 3. КАТЕГОРИИ-ГИГАНТЫ -->
    <section class="cat-grid">
        <a href="catalog.php?category=1" class="cat-tile">ТЕЛЕФОНЫ</a>
        <a href="catalog.php?category=2" class="cat-tile">НАУШНИКИ</a>
        <a href="catalog.php?category=3" class="cat-tile">АКСЕССУАРЫ</a>
    </section>

    <!-- 4. БЛОКИ ДОВЕРИЯ -->
    <section class="trust-grid">
        <div class="trust-block">
            <h2>ТЕХНИЧЕСКАЯ ПОДДЕРЖКА</h2>
            <p>Наши специалисты — эксперты в своей области. Мы работаем, чтобы вам было удобно.</p>
        </div>
        <div class="trust-block blue-bg">
            <h2>КАЧЕСТВО БЕЗ КОМПРОМИССОВ</h2>
            <p>Только оригинальный продукт. Мы избавляем клиентов от множества проблем.</p>
        </div>
    </section>

    <!-- 5. ШАПКА СЕКЦИИ НОВИНОК -->
    <div class="section-header">
        <h2>НОВИНКИ</h2>
    </div>

    <section>
        <div class="product-grid">
            <?php foreach ($new_products as $product): ?>
                <div class="product-card">
                    <a href="product.php?id=<?php echo $product['id']; ?>">
                        <img src="uploads/<?php echo $product['image_url']; ?>" alt="<?php echo $product['title']; ?>">
                    </a>
                    
                    <div class="product-info">
                        <a href="product.php?id=<?php echo $product['id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="product-title"><?php echo htmlspecialchars($product['title']); ?></div>
                        </a>
                        
                        <div class="product-price">
                            <?php if ($product['discount_price']): ?>
                                <span class="old-price"><?php echo number_format($product['price'], 0, '', ' '); ?> ₽</span>
                                <span class="current-price"><?php echo number_format($product['discount_price'], 0, '', ' '); ?> ₽</span>
                            <?php else: ?>
                                <span class="current-price"><?php echo number_format($product['price'], 0, '', ' '); ?> ₽</span>
                            <?php endif; ?>
                        </div>
                        
                        <button class="btn-buy" onclick="addToCart(<?php echo $product['id']; ?>, this)">В КОРЗИНУ</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>