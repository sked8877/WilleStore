<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Проверка прав (только админ)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("ДОСТУП ЗАПРЕЩЕН. <a href='/ws/auth/login.php'>ВХОД</a>");
}

$edit_id = $_GET['edit'] ?? null;
$product = [
    'category_id' => '', 
    'title' => '', 
    'description' => '', 
    'price' => '', 
    'discount_price' => '', 
    'stock' => '', 
    'image_url' => ''
];

// Загружаем список категорий для выпадающего списка
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Если мы в режиме редактирования
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$edit_id]);
    $product = $stmt->fetch();
}

// ОБРАБОТКА ФОРМЫ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $discount = !empty($_POST['discount_price']) ? (float)$_POST['discount_price'] : null;
    $stock = (int)$_POST['stock'];
    
    $image_name = $product['image_url']; // Оставляем старое фото, если новое не выбрано

    // Обработка загрузки нового фото
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = '../uploads/';
        
        // Создаем папку, если её нет
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '_' . uniqid() . '.' . $file_ext;
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
            die("Ошибка загрузки файла. Проверьте права папки uploads.");
        }
    }

    if ($edit_id) {
        // ОБНОВЛЕНИЕ ТОВАРА
        $sql = "UPDATE products SET category_id=?, title=?, description=?, price=?, discount_price=?, stock=?, image_url=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cat_id, $title, $desc, $price, $discount, $stock, $image_name, $edit_id]);
    } else {
        // СОЗДАНИЕ НОВОГО ТОВАРА
        $sql = "INSERT INTO products (category_id, title, description, price, discount_price, stock, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cat_id, $title, $desc, $price, $discount, $stock, $image_name]);
    }
    
    header("Location: index.php");
    exit;
}

include '../includes/header.php';
?>

<main style="padding: 40px; max-width: 800px; margin: 0 auto;">
    <a href="index.php" style="font-weight: 900; text-transform: uppercase; color: var(--black); text-decoration: none;">← НАЗАД К СПИСКУ</a>
    
    <h1 style="font-size: 40px; font-weight: 900; text-transform: uppercase; margin: 30px 0;">
        <?php echo $edit_id ? 'РЕДАКТИРОВАТЬ' : 'ДОБАВИТЬ'; ?> ТОВАР
    </h1>
    
    <form method="POST" enctype="multipart/form-data" class="brutal-form">
        <!-- КАТЕГОРИЯ -->
        <select name="category_id" required>
            <option value="">-- ВЫБЕРИТЕ КАТЕГОРИЮ --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo ($product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- НАЗВАНИЕ -->
        <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" placeholder="НАЗВАНИЕ ТОВАРА" required>
        
        <!-- ОПИСАНИЕ -->
        <textarea name="description" placeholder="ПОДРОБНОЕ ОПИСАНИЕ" style="height: 150px; border: var(--border); padding: 20px; font-family: inherit; font-size: 18px; margin-bottom: -1px; outline: none;"><?php echo htmlspecialchars($product['description']); ?></textarea>
        
        <!-- ЦЕНЫ И СКЛАД -->
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" placeholder="БАЗОВАЯ ЦЕНА" required>
        <input type="number" step="0.01" name="discount_price" value="<?php echo $product['discount_price']; ?>" placeholder="ЦЕНА СО СКИДКОЙ (НЕ ОБЯЗАТЕЛЬНО)">
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" placeholder="КОЛИЧЕСТВО НА СКЛАДЕ" required>
        
        <!-- ФОТО -->
        <div style="border: var(--border); padding: 20px; background: var(--gray); margin-bottom: 20px;">
            <label style="display: block; font-weight: 900; margin-bottom: 10px; text-transform: uppercase;">Фото товара:</label>
            <?php if ($product['image_url']): ?>
                <img src="/ws/uploads/<?php echo $product['image_url']; ?>" style="width: 100px; margin-bottom: 10px; border: 1px solid #000;">
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>

        <button type="submit" class="btn-buy" style="cursor: pointer;">СОХРАНИТЬ ТОВАР</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>