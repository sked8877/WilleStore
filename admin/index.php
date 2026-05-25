<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Защита: только для админов
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("ДОСТУП ЗАПРЕЩЕН. ВЕРНИТЕСЬ НА <a href='/ws/index.php'>ГЛАВНУЮ</a>");
}

// Обработка удаления
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
include '../includes/header.php';
?>

<main style="padding: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h1 style="font-size: 60px; font-weight: 900; text-transform: uppercase;">АДМИН-ПАНЕЛЬ</h1>
        <a href="add_product.php" class="btn-buy" style="width: auto; padding: 15px 30px;">+ ДОБАВИТЬ ТОВАР</a>
    </div>

    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 40px;">
        <!-- Мини-меню -->
        <nav style="border-right: var(--border); padding-right: 20px;">
    <a href="index.php" style="display: block; font-weight: 900; font-size: 20px; margin-bottom: 20px; color: var(--blue);">[ ТОВАРЫ ]</a>
    <a href="categories.php" style="display: block; font-weight: 900; font-size: 20px; margin-bottom: 20px; text-decoration: none; color: black;">[ КАТЕГОРИИ ]</a>
    <a href="orders.php" style="display: block; font-weight: 900; font-size: 20px; text-decoration: none; color: black;">[ ЗАКАЗЫ ]</a>
    <a href="support.php" style="display: block; font-weight: 900; font-size: 20px; color: var(--blue);">[ ПОДДЕРЖКА ]</a>
</nav>

        <!-- Таблица товаров -->
        <section>
            <table style="width: 100%; border-collapse: collapse; border: var(--border);">
                <tr style="background: var(--black); color: var(--white); text-transform: uppercase; font-size: 12px;">
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">НАЗВАНИЕ</th>
                    <th style="padding: 15px; text-align: right;">ЦЕНА</th>
                    <th style="padding: 15px; text-align: right;">ОСТАТОК</th>
                    <th style="padding: 15px; text-align: center;">ДЕЙСТВИЯ</th>
                </tr>
                <?php foreach ($products as $p): ?>
                <tr style="border-bottom: var(--border); font-weight: 700;">
                    <td style="padding: 15px;"><?php echo $p['id']; ?></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($p['title']); ?></td>
                    <td style="padding: 15px; text-align: right;"><?php echo number_format($p['price'], 0, '', ' '); ?> ₽</td>
                    <td style="padding: 15px; text-align: right;"><?php echo $p['stock']; ?> шт.</td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="add_product.php?edit=<?php echo $p['id']; ?>" style="color: var(--blue); text-decoration: none;">ИЗМЕНИТЬ</a> | 
                        <a href="?delete=<?php echo $p['id']; ?>" style="color: red; text-decoration: none;" onclick="return confirm('Удалить?')">УДАЛИТЬ</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>