<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Активация ключа - AisuNet</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .center-box {
            background-color: #222;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(0,0,0,0.8);
            width: 400px;
            text-align: center;
        }
        h1 {
            margin-bottom: 25px;
            font-weight: 700;
        }
        .input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            align-items: center;
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 14px 10px;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
        }
        button, .get-btn {
            background-color: #2ecc71;
            border: none;
            border-radius: 8px;
            padding: 14px 20px;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover, .get-btn:hover {
            background-color: #27ae60;
        }
        button:disabled {
            background-color: #555;
            cursor: not-allowed;
        }
        .message {
            font-size: 1rem;
            margin-top: 10px;
            min-height: 24px;
        }
        .message.error {
            color: #e74c3c;
        }
        .message.success {
            color: #2ecc71;
        }
        .btn-block {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn-block button, .btn-block .back-btn {
            width: 100%;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-block button.activate-btn {
            background-color: #2ecc71;
            color: white;
        }
        .btn-block button.activate-btn:hover {
            background-color: #27ae60;
        }
        .btn-block .back-btn {
            background-color: #8e44ad;
            color: white;
            text-decoration: none;
        }
        .btn-block .back-btn:hover {
            background-color: #732d91;
        }
        .back-btn.hidden {
            display: none;
        }
    </style>
</head>
<body>

<div class="center-box">
    <h1 id="header_title">Введите ключ активации</h1>
    <div class="input-group" id="input_group">
        <input type="text" id="activation_key" placeholder="Ваш ключ" />
        <button type="button" class="get-btn" id="get_btn" onclick="window.open('https://t.me/AisuNet_bot', '_blank')">GET</button>
    </div>
    <div class="btn-block">
        <button id="activate_btn" class="activate-btn">Активировать</button>
        <button id="back_btn_static" class="back-btn" onclick="window.location.href='profile.php'">Назад</button>
    </div>
    <div class="message" id="message"></div>
    <button id="back_btn" class="back-btn hidden" onclick="window.location.href='profile.php'">Вернуться назад</button>
</div>

<script>
    const activateBtn = document.getElementById('activate_btn');
    const backBtnStatic = document.getElementById('back_btn_static');
    const backBtn = document.getElementById('back_btn');
    const keyInput = document.getElementById('activation_key');
    const messageDiv = document.getElementById('message');
    const getBtn = document.getElementById('get_btn');
    const inputGroup = document.getElementById('input_group');
    const headerTitle = document.getElementById('header_title');

    activateBtn.addEventListener('click', () => {
        const key = keyInput.value.trim();
        if (!key) {
            messageDiv.textContent = 'Пожалуйста, введите ключ';
            messageDiv.className = 'message error';
            return;
        }

        activateBtn.disabled = true;
        messageDiv.textContent = 'Проверка...';
        messageDiv.className = 'message';

        fetch('activate_check.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'activation_key=' + encodeURIComponent(key)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.textContent = 'Аккаунт активирован';
                messageDiv.className = 'message success';

                // Скрываем кнопку "Активировать", "GET", поле ввода и заголовок
                activateBtn.style.display = 'none';
                getBtn.style.display = 'none';
                inputGroup.style.display = 'none';
                headerTitle.style.display = 'none';

                backBtnStatic.style.display = 'none';
                backBtn.classList.remove('hidden');
            } else {
                messageDiv.textContent = data.message || 'Неверный ключ';
                messageDiv.className = 'message error';
                activateBtn.disabled = false;
            }
        })
        .catch(() => {
            messageDiv.textContent = 'Ошибка соединения';
            messageDiv.className = 'message error';
            activateBtn.disabled = false;
        });
    });
</script>

</body>
</html>
