<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Логика фильтрации
$cat_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Загрузка категорий для кнопок
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Загрузка товаров
if ($cat_id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY id DESC");
    $stmt->execute([$cat_id]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();

include 'includes/header.php';
?>

<main>
    <!-- ШАПКА КАТАЛОГА -->
    <div style="padding: 60px 40px; border-bottom: var(--border); background: #fff;">
        <h1 style="font-size: 80px; font-weight: 900; text-transform: uppercase; line-height: 0.8; margin-bottom: 40px;">
            <?php echo $cat_id ? 'КАТЕГОРИЯ' : 'КАТАЛОГ ТЕХНИКИ'; ?>
        </h1>
        
        <!-- Кнопки категорий -->
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="catalog.php" class="btn-buy" style="width: auto; padding: 12px 30px; text-decoration: none; background: <?php echo !$cat_id ? 'var(--blue)' : 'var(--black)'; ?>;">
                ВСЁ СРАЗУ
            </a>
            
            <?php foreach ($categories as $cat): ?>
                <a href="catalog.php?category=<?php echo $cat['id']; ?>" 
                   class="btn-buy" 
                   style="width: auto; padding: 12px 30px; text-decoration: none; background: <?php echo ($cat_id == $cat['id']) ? 'var(--blue)' : 'var(--black)'; ?>;">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- СЕТКА ТОВАРОВ -->
    <div class="product-grid">
        <?php if (empty($products)): ?>
            <div style="padding: 100px; text-align: center; font-weight: 900; font-size: 24px; grid-column: 1 / -1; text-transform: uppercase;">
                Товаров в этой категории пока нет.
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <!-- Ссылка на товар через картинку -->
                    <a href="product.php?id=<?php echo $product['id']; ?>">
                        <img src="uploads/<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                    </a>
                    
                    <div class="product-info">
                        <!-- Ссылка на товар через заголовок -->
                        <a href="product.php?id=<?php echo $product['id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="product-title"><?php echo htmlspecialchars($product['title']); ?></div>
                        </a>
                        
                        <div class="product-price">
                            <?php if (!empty($product['discount_price']) && $product['discount_price'] > 0): ?>
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
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>