<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['city'] = $user['city'];
        header("Location: /ws/index.php");
        exit;
    } else {
        $error = "НЕВЕРНЫЙ ЛОГИН ИЛИ ПАРОЛЬ";
    }
}

include '../includes/header.php';
?>

<main class="auth-container">
    <h1 style="font-size: clamp(60px, 10vw, 120px); font-weight: 900; text-transform: uppercase; margin-bottom: 40px; line-height: 0.8;">ВХОД</h1>
    
    <?php if ($error): ?>
        <div style="background: red; color: white; padding: 20px; font-weight: 900; margin-bottom: 20px; text-transform: uppercase;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="brutal-form">
        <input type="email" name="email" placeholder="EMAIL" required>
        <input type="password" name="password" placeholder="ПАРОЛЬ" required>
        <button type="submit" class="btn-buy" style="margin-top: 20px; font-size: 24px; padding: 25px;">ВОЙТИ В СИСТЕМУ</button>
    </form>
    
    <a href="register.php" style="display: block; margin-top: 30px; font-weight: 900; text-transform: uppercase; color: var(--blue); text-decoration: none; border-bottom: 2px solid var(--blue); width: fit-content;">
        Нет аккаунта? Регистрация
    </a>
</main>

<?php include 'includes/footer.php'; ?>