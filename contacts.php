<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<main>
    <!-- ЗАГОЛОВОК -->
    <div style="padding: 60px 40px; border-bottom: var(--border); background: #fff;">
        <h1 style="font-size: clamp(50px, 10vw, 100px); font-weight: 900; text-transform: uppercase; line-height: 0.8;">
            КОНТАКТЫ
        </h1>
    </div>

    <div class="contact-page-grid">
        <!-- ЛЕВАЯ КОЛОНКА: ТЕКСТОВАЯ ИНФОРМАЦИЯ -->
        <div class="contact-details">
            <section class="contact-block">
                <h3>ОФИСЫ И ШОУРУМЫ</h3>
                <div class="contact-list">
                    <div class="contact-item">
                        <span>МОСКВА</span> 
                        <span>УЛ. ПРЕЧИСТЕНКА, 10</span>
                    </div>
                    <div class="contact-item">
                        <span>САНКТ-ПЕТЕРБУРГ</span> 
                        <span>НЕВСКИЙ ПР., 25</span>
                    </div>
                    <div class="contact-item">
                        <span>ОМСК</span> 
                        <span>УЛ. ЛЕНИНА, 14</span>
                    </div>
                    <div class="contact-item">
                        <span>НОВОСИБИРСК</span> 
                        <span>КРАСНЫЙ ПР., 30</span>
                    </div>
                </div>
            </section>

            <section class="contact-block" style="border-top: var(--border);">
                <h3>СВЯЗЬ С НАМИ</h3>
                <div class="contact-list">
                    <div class="contact-item">
                        <span>ТЕЛЕФОН</span> 
                        <span>8 800 555 35 35</span>
                    </div>
                    <div class="contact-item">
                        <span>TELEGRAM</span> 
                        <span>@WILLESTORE_SUPPORT</span>
                    </div>
                    <div class="contact-item">
                        <span>VKONTAKTE</span> 
                        <span>VK.COM/WILLESTORE</span>
                    </div>
                </div>
            </section>
        </div>

        <!-- ПРАВАЯ КОЛОНКА: ЧАТ ПОДДЕРЖКИ -->
        <div class="contact-chat-container">
            <div class="chat-header">ЧАТ С ТЕХПОДДЕРЖКОЙ</div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div id="chat-window" class="chat-messages-area">
                    <!-- Сообщения подгрузятся через JS -->
                </div>

                <form id="chat-form" class="chat-input-row">
                    <input type="text" id="chat-input" placeholder="ВАШ ВОПРОС..." required>
                    <button type="submit">ОТПРАВИТЬ</button>
                </form>
            <?php else: ?>
                <div class="chat-auth-prompt">
                    <p>ЧАТ ДОСТУПЕН ТОЛЬКО АВТОРИЗОВАННЫМ КЛИЕНТАМ</p>
                    <a href="/ws/auth/login.php" class="btn-buy" style="display: inline-block; width: auto; padding: 15px 40px; margin-top: 20px;">
                        ВОЙТИ
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>