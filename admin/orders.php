<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') die("Access Denied");

// ОБРАБОТКА ОБНОВЛЕНИЯ СТАТУСА
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    header("Location: orders.php");
    exit;
}

$orders = $pdo->query("SELECT orders.*, users.email FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC")->fetchAll();
include '../includes/header.php';
?>

<main style="padding: 40px;">
    <h1 style="font-size: 60px; font-weight: 900; text-transform: uppercase; margin-bottom: 40px;">ЗАКАЗЫ</h1>
    
    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 40px;">
        <nav style="border-right: var(--border); padding-right: 20px;">
            <a href="index.php" style="display: block; font-weight: 900; font-size: 20px; margin-bottom: 20px; text-decoration: none; color: black;">[ ТОВАРЫ ]</a>
            <a href="categories.php" style="display: block; font-weight: 900; font-size: 20px; margin-bottom: 20px; text-decoration: none; color: black;">[ КАТЕГОРИИ ]</a>
            <a href="orders.php" style="display: block; font-weight: 900; font-size: 20px; color: var(--blue);">[ ЗАКАЗЫ ]</a>
            <a href="support.php" style="display: block; font-weight: 900; font-size: 20px; color: var(--blue);">[ ПОДДЕРЖКА ]</a>
        </nav>

        <section>
            <table style="width: 100%; border-collapse: collapse; border: var(--border);">
                <tr style="background: var(--black); color: var(--white); text-transform: uppercase; font-size: 12px;">
                    <th style="padding: 15px;">№</th>
                    <th style="padding: 15px;">КЛИЕНТ</th>
                    <th style="padding: 15px;">СУММА</th>
                    <th style="padding: 15px; text-align: center;">СТАТУС (ИЗМЕНИТЬ)</th>
                </tr>
                <?php foreach ($orders as $o): ?>
                <tr style="border-bottom: var(--border); font-weight: 700;">
                    <td style="padding: 15px;">#<?php echo $o['id']; ?></td>
                    <td style="padding: 15px;"><?php echo $o['email']; ?></td>
                    <td style="padding: 15px;"><?php echo number_format($o['total_amount'], 0, '', ' '); ?> ₽</td>
                    <td style="padding: 15px; text-align: center;">
                        <form method="POST" style="display: flex; gap: 5px; justify-content: center;">
                            <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                            <select name="status" style="padding: 5px; border: 1px solid #000; font-weight: 700; text-transform: uppercase;">
                                <option value="pending" <?php if($o['status'] == 'pending') echo 'selected'; ?>>PENDING</option>
                                <option value="paid" <?php if($o['status'] == 'paid') echo 'selected'; ?>>PAID</option>
                                <option value="shipped" <?php if($o['status'] == 'shipped') echo 'selected'; ?>>SHIPPED</option>
                                <option value="completed" <?php if($o['status'] == 'completed') echo 'selected'; ?>>COMPLETED</option>
                                <option value="cancelled" <?php if($o['status'] == 'cancelled') echo 'selected'; ?>>CANCELLED</option>
                            </select>
                            <button type="submit" name="update_status" style="background: var(--blue); color: #fff; border: none; padding: 5px 10px; cursor: pointer; font-size: 10px;">OK</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>