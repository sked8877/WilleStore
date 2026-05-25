<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') die("Access Denied");

$target_user_id = (int)$_GET['user_id'];

// Получаем данные клиента
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$target_user_id]);
$client = $stmt->fetch();

include '../includes/header.php';
?>

<main style="padding: 40px;">
    <a href="support.php" style="font-weight: 900; text-transform: uppercase; color: #000; text-decoration: none;">← НАЗАД К СПИСКУ</a>
    
    <h1 style="font-size: 40px; font-weight: 900; text-transform: uppercase; margin-top: 20px; margin-bottom: 30px;">
        ЧАТ С: <?php echo htmlspecialchars($client['full_name']); ?> (<?php echo $client['email']; ?>)
    </h1>

    <div class="contact-chat-container" style="border: var(--border); max-width: 900px;">
        <div id="chat-window" class="chat-messages-area" data-user-id="<?php echo $target_user_id; ?>">
            <!-- Сюда грузятся сообщения через AJAX -->
        </div>
        <form id="admin-chat-form" class="chat-input-row">
            <input type="text" id="chat-input" placeholder="ВАШ ОТВЕТ..." required>
            <button type="submit">ОТПРАВИТЬ</button>
        </form>
    </div>
</main>

<script>
// Скрипт специально для админа
document.addEventListener('DOMContentLoaded', function() {
    const chatWin = document.getElementById('chat-window');
    const chatForm = document.getElementById('admin-chat-form');
    const chatInput = document.getElementById('chat-input');
    const userId = chatWin.getAttribute('data-user-id');

    function loadMessages() {
        fetch('/ws/ajax/admin_chat_handler.php?user_id=' + userId)
            .then(res => res.text())
            .then(html => {
                chatWin.innerHTML = html;
                chatWin.scrollTop = chatWin.scrollHeight;
            });
    }

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('message', chatInput.value);
        formData.append('user_id', userId);

        fetch('/ws/ajax/admin_chat_handler.php', {
            method: 'POST',
            body: formData
        }).then(() => {
            chatInput.value = '';
            loadMessages();
        });
    });

    setInterval(loadMessages, 3000);
    loadMessages();
});
</script>

<?php include '../includes/footer.php'; ?>