<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') die("Access Denied");

// Получаем список уникальных пользователей, которые писали в поддержку
$stmt = $pdo->query("
    SELECT DISTINCT u.id, u.email, u.full_name 
    FROM support_messages m 
    JOIN users u ON m.user_id = u.id 
    ORDER BY (SELECT MAX(created_at) FROM support_messages WHERE user_id = u.id) DESC
");
$chats = $stmt->fetchAll();

include '../includes/header.php';
?>

<main style="padding: 40px;">
    <h1 style="font-size: 60px; font-weight: 900; text-transform: uppercase; margin-bottom: 40px;">ПОДДЕРЖКА</h1>

    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 40px;">
        <!-- ТА ЖЕ НАВИГАЦИЯ АДМИНКИ -->
        <nav style="border-right: var(--border); padding-right: 20px;">
            <a href="index.php" style="display: block; font-weight: 900; font-size: 20px; margin-bottom: 20px; text-decoration: none; color: black;">[ ТОВАРЫ ]</a>
            <a href="categories.php" style="display: block; font-weight: 900; font-size: 20px; margin-bottom: 20px; text-decoration: none; color: black;">[ КАТЕГОРИИ ]</a>
            <a href="orders.php" style="display: block; font-weight: 900; font-size: 20px; margin-bottom: 20px; text-decoration: none; color: black;">[ ЗАКАЗЫ ]</a>
            <a href="support.php" style="display: block; font-weight: 900; font-size: 20px; color: var(--blue);">[ ПОДДЕРЖКА ]</a>
        </nav>

        <section>
            <table style="width: 100%; border-collapse: collapse; border: var(--border);">
                <tr style="background: var(--black); color: #fff; text-transform: uppercase;">
                    <th style="padding: 15px;">ПОЛЬЗОВАТЕЛЬ</th>
                    <th style="padding: 15px;">EMAIL</th>
                    <th style="padding: 15px; text-align: center;">ДЕЙСТВИЕ</th>
                </tr>
                <?php foreach ($chats as $chat): ?>
                <tr style="border-bottom: var(--border); font-weight: 700;">
                    <td style="padding: 15px;"><?php echo htmlspecialchars($chat['full_name']); ?></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($chat['email']); ?></td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="chat_view.php?user_id=<?php echo $chat['id']; ?>" class="btn-buy" style="padding: 10px 20px; font-size: 12px; display: inline-block; width: auto;">ОТКРЫТЬ ЧАТ</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>