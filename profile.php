<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_auth();

// Обработка смены города
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_city'])) {
    $new_city = $_POST['city'];
    $stmt = $pdo->prepare("UPDATE users SET city = ? WHERE id = ?");
    $stmt->execute([$new_city, $_SESSION['user_id']]);
    $_SESSION['city'] = $new_city;
}

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

include 'includes/header.php';
?>

<main style="display: grid; grid-template-columns: 1fr 1fr; border-bottom: var(--border);">
    <!-- Секция данных -->
    <div style="padding: 60px 40px; border-right: var(--border);">
        <h1 style="font-size: 60px; font-weight: 900; text-transform: uppercase; margin-bottom: 30px;">ПРОФИЛЬ</h1>
        <p style="font-size: 24px; font-weight: 700; margin-bottom: 10px;">ИМЯ: <?php echo htmlspecialchars($user['full_name']); ?></p>
        <p style="font-size: 24px; font-weight: 700; margin-bottom: 40px;">EMAIL: <?php echo htmlspecialchars($user['email']); ?></p>

        <form method="POST" class="brutal-form" style="max-width: 400px;">
            <label style="font-weight: 900; display: block; margin-bottom: 30px;">СМЕНИТЬ ГОРОД:</label>
            <input type="text" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" style="padding: 15px; font-size: 16px;">
            <button type="submit" name="update_city" class="btn-buy" style="padding: 10px; font-size: 14px;">ОБНОВИТЬ</button>
        </form>
    </div>

    <!-- Секция заказов -->
     <div style="padding: 60px 40px; background: var(--gray);">
        <h2 style="font-size: 60px; font-weight: 900; text-transform: uppercase; margin-bottom: 30px;">ИСТОРИЯ</h2>
        
        <?php
        // Получаем заказы пользователя
        $stmt_orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt_orders->execute([$_SESSION['user_id']]);
        $my_orders = $stmt_orders->fetchAll();

        if (empty($my_orders)): ?>
            <div style="border: 2px dashed var(--black); padding: 40px; text-align: center; font-weight: 700;">
                ЗАКАЗОВ ПОКА НЕТ. ПОРА ЧТО-ТО КУПИТЬ.
            </div>
        <?php else: ?>
            <?php foreach ($my_orders as $order): ?>
                <div style="background: #fff; border: var(--border); padding: 20px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; font-weight: 900; text-transform: uppercase; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                        <span>ЗАКАЗ #<?php echo $order['id']; ?></span>
                        <span style="color: var(--blue);"><?php echo $order['status']; ?></span>
                    </div>
                    <div style="font-weight: 700;">
                        СУММА: <?php echo number_format($order['total_amount'], 0, '', ' '); ?> ₽<br>
                        ДАТА: <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>