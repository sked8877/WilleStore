<?php
ob_start();
session_start();
require_once '../includes/db.php';

$response = ['status' => 'error', 'message' => 'unknown'];

if (isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];

    $stmt = $pdo->prepare("SELECT id, title, price, discount_price, image_url FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $price = $product['discount_price'] ?? $product['price'];

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'title' => $product['title'],
                'price' => (float)$price,
                'image' => $product['image_url'], // Сохраняем имя файла картинки
                'quantity' => 1
            ];
        }

        $count = array_sum(array_column($_SESSION['cart'], 'quantity'));
        $response = ['status' => 'success', 'count' => $count];
    }
}

ob_end_clean();
header('Content-Type: application/json');
echo json_encode($response);
exit;