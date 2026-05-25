
function addToCart(productId, btnElement) {
    const formData = new FormData();
    formData.append('product_id', productId);

    // Добавляем лог в консоль для отладки (F12 -> Console)
    console.log("Добавление товара ID:", productId);

    fetch('/ws/ajax/add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // 1. Обновляем счетчик в шапке
            const cartLinks = document.querySelectorAll('.cart-link');
            cartLinks.forEach(link => {
                link.innerText = `КОРЗИНА (${data.count})`;
            });
            
            // 2. Анимация кнопки
            const originalText = btnElement.innerText;
            btnElement.innerText = 'ДОБАВЛЕНО!';
            btnElement.style.backgroundColor = '#007BFF'; // Наш ярко-синий
            
            setTimeout(() => {
                btnElement.innerText = originalText;
                btnElement.style.backgroundColor = '';
            }, 1000);
        } else {
            alert("Ошибка: " + data.message);
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        alert("Произошла ошибка при добавлении в корзину");
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('openMenu');
    const closeBtn = document.getElementById('closeMenu');
    const menu = document.getElementById('menuOverlay');

    // Открыть меню
    if (openBtn) {
        openBtn.addEventListener('click', function() {
            menu.classList.add('active');
            document.body.style.overflow = 'hidden'; // Запрещаем скролл страницы
        });
    }

    // Закрыть меню
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            menu.classList.remove('active');
            document.body.style.overflow = ''; // Возвращаем скролл
        });
    }

    // Закрывать при клике на любую ссылку в меню
    const links = document.querySelectorAll('.overlay-nav a');
    links.forEach(link => {
        link.addEventListener('click', () => {
            menu.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
});

if (document.getElementById('chat-window')) {
    const chatWin = document.getElementById('chat-window');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');

    // Функция загрузки сообщений
    function loadMessages() {
        fetch('/ws/ajax/chat_handler.php')
            .then(res => res.text())
            .then(html => {
                chatWin.innerHTML = html;
                chatWin.scrollTop = chatWin.scrollHeight;
            });
    }

    // Отправка
    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('message', chatInput.value);

        fetch('/ws/ajax/chat_handler.php', {
            method: 'POST',
            body: formData
        }).then(() => {
            chatInput.value = '';
            loadMessages();
        });
    });

    // Обновляем каждые 3 секунды
    setInterval(loadMessages, 3000);
    loadMessages();
}