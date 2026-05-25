<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Получаем ID товара
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Запрос к БД с присоединением названия категории
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ?
");
$stmt->execute([$id]);
$product = $stmt->fetch();

// Если товар не найден
if (!$product) {
    header("Location: /ws/catalog.php");
    exit;
}

include 'includes/header.php';
?>

<main>
    <!-- Хлебные крошки / Назад -->
    <div style="padding: 20px 40px; border-bottom: var(--border);">
        <a href="catalog.php" style="font-weight: 800; text-transform: uppercase; color: var(--black); text-decoration: none;">
            ← НАЗАД В КАТАЛОГ / <?php echo htmlspecialchars($product['category_name'] ?? 'БЕЗ КАТЕГОРИИ'); ?>
        </a>
    </div>

    <div class="product-detail-grid">
        <!-- ЛЕВАЯ КОЛОНКА: ФОТО -->
        <div class="product-detail-image">
            <img src="uploads/<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
        </div>

        <!-- ПРАВАЯ КОЛОНКА: ИНФО -->
        <div class="product-detail-info">
            <div style="margin-bottom: 40px;">
                <h1 style="font-size: clamp(40px, 6vw, 100px); font-weight: 900; line-height: 0.85; text-transform: uppercase; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($product['title']); ?>
                </h1>
                
                <div class="product-price" style="font-size: 40px;">
                    <?php if ($product['discount_price']): ?>
                        <span class="old-price" style="font-size: 24px;"><?php echo number_format($product['price'], 0, '', ' '); ?> ₽</span>
                        <span class="current-price"><?php echo number_format($product['discount_price'], 0, '', ' '); ?> ₽</span>
                    <?php else: ?>
                        <span class="current-price"><?php echo number_format($product['price'], 0, '', ' '); ?> ₽</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Характеристики / Описание -->
            <div style="border-top: var(--border); padding-top: 30px; margin-bottom: 40px;">
                <p style="font-size: 18px; font-weight: 600; text-transform: uppercase; line-height: 1.4; color: #333;">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>
            </div>

            <!-- Статус наличия -->
            <div style="margin-bottom: 30px; font-weight: 900; text-transform: uppercase;">
                СТАТУС: <?php echo ($product['stock'] > 0) ? '<span style="color: green;">В НАЛИЧИИ ('.$product['stock'].' ШТ)</span>' : '<span style="color: red;">НЕТ В НАЛИЧИИ</span>'; ?>
            </div>

            <!-- Кнопка покупки -->
            <?php if ($product['stock'] > 0): ?>
                <button class="btn-buy" 
                        style="font-size: 24px; padding: 30px;" 
                        onclick="addToCart(<?php echo $product['id']; ?>, this)">
                    ДОБАВИТЬ В КОРЗИНУ
                </button>
            <?php endif; ?>
            
            <div style="margin-top: 40px; padding: 20px; background: var(--gray); font-weight: 700; text-transform: uppercase; font-size: 12px;">
                ГАРАНТИЯ ПРЕМИУМ-РЕСЕЛЛЕРА 1 ГОД <br>
                БЫСТРАЯ ДОСТАВКА В ТЕЧЕНИЕ 2 ЧАСОВ
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>