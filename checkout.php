<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_auth();

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

$total_sum = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_sum += $item['price'] * $item['quantity'];
}

include 'includes/header.php';
?>

<main style="padding: 40px; max-width: 900px; margin: 0 auto;">
    <h1 style="font-size: 60px; font-weight: 900; text-transform: uppercase; margin-bottom: 40px;">ОПЛАТА ЗАКАЗА</h1>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
        <!-- Левая часть: Форма -->
        <form action="process_payment.php" method="POST" class="brutal-form">
            <label style="font-weight: 800;">ТЕЛЕФОН</label>
            <input type="text" name="phone" placeholder="+7 (___) ___-__-__" required>
            
            <label style="font-weight: 800;">НОМЕР КАРТЫ</label>
            <input type="text" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" required>
            
            <div style="display: flex;">
                <div style="flex: 1;">
                    <label style="font-weight: 800;">ДАТА</label>
                    <input type="text" name="card_date" placeholder="ММ/ГГ" maxlength="5" required>
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: 800;">CVV</label>
                    <input type="password" name="card_cvv" placeholder="***" maxlength="3" required>
                </div>
            </div>

            <button type="submit" class="btn-buy" style="margin-top: 20px; font-size: 24px;">ОПЛАТИТЬ <?php echo number_format($total_sum, 0, '', ' '); ?> ₽</button>
        </form>

        <!-- Правая часть: Чек -->
        <div style="border: 5px solid var(--black); padding: 30px; background: var(--gray);">
            <h2 style="font-weight: 900; text-transform: uppercase; margin-bottom: 20px;">ВАШ ЧЕК:</h2>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <div style="display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 5px;">
                    <span><?php echo $item['title']; ?> (x<?php echo $item['quantity']; ?>)</span>
                    <span><?php echo number_format($item['price'] * $item['quantity'], 0, '', ' '); ?> ₽</span>
                </div>
            <?php endforeach; ?>
            <div style="font-size: 30px; font-weight: 900; margin-top: 20px; text-align: right;">
                ИТОГО: <?php echo number_format($total_sum, 0, '', ' '); ?> ₽
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>