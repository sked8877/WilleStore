<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}

include 'includes/header.php';
$cart = $_SESSION['cart'] ?? [];
$total_sum = 0;
?>

<main>
    <div style="padding: 60px 40px; border-bottom: var(--border);">
        <h1 style="font-size: clamp(50px, 10vw, 100px); font-weight: 900; text-transform: uppercase; line-height: 0.8;">
            КОРЗИНА
        </h1>
    </div>

    <?php if (empty($cart)): ?>
        <div style="padding: 100px 40px; text-align: center;">
            <p style="font-size: 24px; font-weight: 800; text-transform: uppercase; margin-bottom: 30px;">Тут пока ничего нет.</p>
            <a href="catalog.php" class="btn-buy" style="display: inline-block; width: auto; padding: 20px 60px; text-decoration: none;">ПЕРЕЙТИ В КАТАЛОГ</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 120px; text-align: center;">ФОТО</th>
                    <th>ТОВАР</th>
                    <th style="text-align: center;">КОЛЛИЧЕСТВО</th>
                    <th style="text-align: right;">ЦЕНА</th>
                    <th style="text-align: right;">СУММА</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $id => $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_sum += $subtotal;
                    $img = (!empty($item['image'])) ? $item['image'] : 'default.jpg';
                ?>
                    <tr>
                        <td style="text-align: center;">
                            <img src="uploads/<?php echo $img; ?>" 
                                 class="cart-thumb" 
                                 style="width: 80px; height: 80px; object-fit: contain; border: 1px solid #000; background: #fff;"
                                 onerror="this.src='https://via.placeholder.com/80x80?text=W'">
                        </td>
                        <td style="font-weight: 900; text-transform: uppercase;">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </td>
                        <td style="text-align: center;"><?php echo $item['quantity']; ?></td>
                        <td style="text-align: right; white-space: nowrap;">
                            <?php echo number_format($item['price'], 0, '', ' '); ?> ₽
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <?php echo number_format($subtotal, 0, '', ' '); ?> ₽
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-total-wrapper">
            <div class="cart-total-card">
                <span class="total-label">ИТОГО К ОПЛАТЕ:</span>
                <div class="total-amount">
                    <?php echo number_format($total_sum, 0, '', ' '); ?> ₽
                </div>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="checkout.php" method="POST">
                    <button type="submit" class="btn-buy" style="font-size: 22px;">ОФОРМИТЬ ЗАКАЗ</button>
                </form>
            <?php else: ?>
                <a href="auth/login.php" class="btn-buy" style="font-size: 20px; display: block; text-decoration: none;">
                    ВОЙДИТЕ, ЧТОБЫ КУПИТЬ
                </a>
            <?php endif; ?>

            <a href="?clear=1" style="color: red; font-weight: 800; text-transform: uppercase; text-decoration: none; font-size: 11px; margin-top: 10px; opacity: 0.5;">
                [ ОЧИСТИТЬ КОРЗИНУ ]
            </a>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>