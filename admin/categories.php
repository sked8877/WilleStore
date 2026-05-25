<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') die("Access Denied");

// Добавление категории
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$name]);
    header("Location: categories.php");
    exit;
}

// Удаление категории
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
include '../includes/header.php';
?>

<main style="padding: 40px;">
    <h1 style="font-size: 60px; font-weight: 900; text-transform: uppercase; margin-bottom: 40px;">КАТЕГОРИИ</h1>

    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 60px;">
        <!-- Навигация админки -->
        <nav style="border-right: var(--border); padding-right: 20px;">
            <a href="index.php" style="display: block; font-weight: 900; font-size: 20px; margin-bottom: 20px; text-decoration: none; color: black;">[ ТОВАРЫ ]</a>
            <a href="categories.php" style="display: block; font-weight: 900; font-size: 20px; margin-bottom: 20px; color: var(--blue);">[ КАТЕГОРИИ ]</a>
            <a href="orders.php" style="display: block; font-weight: 900; font-size: 20px; text-decoration: none; color: black;">[ ЗАКАЗЫ ]</a>
            <a href="support.php" style="display: block; font-weight: 900; font-size: 20px; color: var(--blue);">[ ПОДДЕРЖКА ]</a>
        </nav>

        <section>
            <!-- Форма добавления -->
            <form method="POST" class="brutal-form" style="margin-bottom: 40px; display: flex;">
                <input type="text" name="name" placeholder="НАЗВАНИЕ КАТЕГОРИИ" required style="margin-bottom: 0;">
                <button type="submit" name="add_category" class="btn-buy" style="width: auto; padding: 0 30px;">ДОБАВИТЬ</button>
            </form>

            <!-- Список -->
            <table style="width: 100%; border-collapse: collapse; border: var(--border);">
                <tr style="background: var(--black); color: var(--white); text-transform: uppercase;">
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">НАЗВАНИЕ</th>
                    <th style="padding: 15px; text-align: center;">ДЕЙСТВИЕ</th>
                </tr>
                <?php foreach ($categories as $cat): ?>
                <tr style="border-bottom: var(--border); font-weight: 700;">
                    <td style="padding: 15px;"><?php echo $cat['id']; ?></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($cat['name']); ?></td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="?delete=<?php echo $cat['id']; ?>" style="color: red; text-decoration: none;" onclick="return confirm('Удалить?')">[ УДАЛИТЬ ]</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>