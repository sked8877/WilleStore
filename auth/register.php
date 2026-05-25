<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    
    if ($check->fetch()) {
        $error = "ЭТОТ EMAIL УЖЕ ЗАНЯТ";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (email, full_name, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$email, $full_name, $password])) {
            header("Location: login.php");
            exit;
        }
    }
}

include '../includes/header.php'; 
?>

<main class="auth-container">
    <h1 style="font-size: clamp(60px, 10vw, 100px); font-weight: 900; text-transform: uppercase; margin-bottom: 40px; line-height: 0.8;">РЕГИСТРАЦИЯ</h1>
    
    <?php if ($error): ?>
        <div style="background: red; color: white; padding: 20px; font-weight: 900; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="brutal-form">
        <input type="text" name="full_name" placeholder="ПОЛНОЕ ИМЯ" required>
        <input type="email" name="email" placeholder="EMAIL" required>
        <input type="password" name="password" placeholder="ПАРОЛЬ" required>
        <button type="submit" class="btn-buy" style="margin-top: 20px; font-size: 24px; padding: 25px;">СОЗДАТЬ АККАУНТ</button>
    </form>
    <a href="login.php" style="display: block; margin-top: 30px; font-weight: 900; text-transform: uppercase; color: var(--blue); text-decoration: none; border-bottom: 2px solid var(--blue); width: fit-content;">
        Уже есть аккаунт? Войти
    </a>
</main>

<?php include '../includes/footer.php'; ?>