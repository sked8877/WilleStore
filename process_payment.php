<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Проверка авторизации (только залогиненные могут платить)
require_auth();

// Если корзина пуста — отправляем на главную
if (empty($_SESSION['cart'])) {
    header("Location: /ws/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $city = $_SESSION['city'];
    $cart = $_SESSION['cart'];
    $total_amount = 0;

    // Считаем итоговую сумму еще раз для безопасности
    foreach ($cart as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    try {
        // НАЧИНАЕМ ТРАНЗАКЦИЮ
        $pdo->beginTransaction();

        // 1. Создаем запись в таблице orders
        // Устанавливаем статус 'paid', так как оплата прошла
        $stmt_order = $pdo->prepare("INSERT INTO orders (user_id, total_amount, delivery_city, status) VALUES (?, ?, ?, 'paid')");
        $stmt_order->execute([$user_id, $total_amount, $city]);
        
        $order_id = $pdo->lastInsertId(); // Получаем номер только что созданного заказа

        // 2. Подготавливаем запросы для товаров и склада
        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
        $stmt_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

        // 3. Проходим циклом по всем товарам в корзине
        foreach ($cart as $product_id => $item) {
            // Сохраняем товар в детали заказа
            $stmt_item->execute([
                $order_id, 
                $product_id, 
                $item['quantity'], 
                $item['price']
            ]);
            
            // УМЕНЬШАЕМ КОЛИЧЕСТВО НА СКЛАДЕ
            $stmt_stock->execute([
                $item['quantity'], 
                $product_id
            ]);
        }

        // ПОДТВЕРЖДАЕМ ТРАНЗАКЦИЮ (сохраняем изменения в БД)
        $pdo->commit();
        
        // Очищаем корзину после успешной оплаты
        unset($_SESSION['cart']);

        // Выводим страницу успеха в нашем стиле
        include 'includes/header.php';
        ?>
        <main style="padding: 100px 40px; text-align: center; flex: 1 0 auto;">
            <div style="border: 10px solid var(--blue); padding: 60px; display: inline-block;">
                <h1 style="font-size: clamp(40px, 10vw, 120px); font-weight: 900; line-height: 0.8; margin-bottom: 20px; color: var(--blue);">ОПЛАЧЕНО</h1>
                <p style="font-size: 24px; font-weight: 800; text-transform: uppercase; margin-bottom: 40px;">
                    ВАШ ЗАКАЗ #<?php echo $order_id; ?> ПРИНЯТ В ОБРАБОТКУ.<br>
                    ТОВАРЫ УЖЕ УПАКОВЫВАЮТСЯ.
                </p>
                <a href="/ws/catalog.php" class="btn-buy" style="display: inline-block; width: auto; padding: 20px 60px; text-decoration: none;">ВЕРНУТЬСЯ В МАГАЗИН</a>
            </div>
        </main>
        <?php
        include 'includes/footer.php';

    } catch (Exception $e) {
        // Если что-то пошло не так — отменяем всё (rollback)
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("ОШИБКА ПРИ ОФОРМЛЕНИИ: " . $e->getMessage());
    }
} else {
    // Если зашли на файл просто так без POST-запроса
    header("Location: /ws/cart.php");
    exit;
}
?>