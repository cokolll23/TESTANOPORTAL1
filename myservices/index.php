<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Users Json");
?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>5 красивых кнопок со ссылками</title>

        <style>

            .container {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 2rem 2.5rem;
                max-width: 1000px;
                width: 100%;
                padding: 2rem 1.5rem;
                background: rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(4px);
                border-radius: 4rem 2rem 4rem 2rem;
                box-shadow: 0 20px 40px rgba(0, 10, 30, 0.15), inset 0 1px 2px rgba(255, 255, 255, 0.6);
            }

            /* ----- базовые стили для всех кнопок ----- */
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.9rem 2.2rem;
                font-weight: 600;
                font-size: 1.1rem;
                letter-spacing: 0.3px;
                text-decoration: none;
                border-radius: 60px;
                transition: all 0.25s ease;
                box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
                border: none;
                cursor: pointer;
                min-width: 140px;
                position: relative;
                background: white;
                color: #1a2639;
                border: 1px solid transparent;
            }

            .btn:hover {
                transform: translateY(-5px) scale(1.02);
                box-shadow: 0 16px 28px rgba(0, 0, 0, 0.12);
            }

            .btn:active {
                transform: scale(0.97);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            /* ---------- 1. Неоновая фиолетовая ---------- */




            /* ---------- 3. Стекло (glassmorphism) ---------- */
            .btn-glass {
                background: rgba(255, 255, 255, 0.25);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                color: #1e2b3c;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08), inset 0 1px 2px rgba(255, 255, 255, 0.7);
                font-weight: 600;
                letter-spacing: 0.5px;
            }

            .btn-glass:hover {
                background: rgba(255, 255, 255, 0.45);
                backdrop-filter: blur(12px);
                border-color: #ffffff;
                box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12), inset 0 1px 4px white;
                transform: translateY(-5px) scale(1.02);
                color: #0b1622;
            }

            /* ---------- 4. Тёмная с иконкой ---------- */
            .btn-dark {
                background: #1f2937;
                color: #f0f4ff;
                border: 1px solid #374151;
                box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
                font-weight: 500;
            }

            .btn-dark:hover {
                background: #2d3b4f;
                border-color: #6b7a93;
                box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
                transform: translateY(-5px) scale(1.02);
                color: white;
            }

            .btn-dark span {
                margin-right: 8px;
                font-size: 1.2rem;
            }

            /* ---------- 5. Акцентная с обводкой ---------- */
            .btn-outline {
                background: transparent;
                color: #0f2b4b;
                border: 2px solid #0f2b4b;
                box-shadow: none;
                font-weight: 600;
                letter-spacing: 0.5px;
                transition: all 0.25s ease;
            }

            .btn-outline:hover {
                background: #0f2b4b;
                color: white;
                border-color: #0f2b4b;
                box-shadow: 0 12px 24px rgba(15, 43, 75, 0.25);
                transform: translateY(-5px) scale(1.03);
            }

            .btn-outline:active {
                background: #091b30;
            }

            /* маленькие декоративные штрихи */
            .btn i {
                font-style: normal;
            }

            /* адаптив */
            @media (max-width: 600px) {
                .container {
                    gap: 1.5rem;
                    padding: 1.5rem 1rem;
                    border-radius: 2rem;
                }
                .btn {
                    min-width: 120px;
                    padding: 0.7rem 1.6rem;
                    font-size: 1rem;
                    width: 100%;
                    max-width: 260px;
                }
            }
        </style>

    <div class="container">

        Функция для обработки JSON файла и сравнения с пользователями Битрикс
        <a href="usersJson2" class="btn btn-neon" target="_blank">
            Функция для обработки JSON файла и сравнения с пользователями Битрикс
        </a>

        <!-- 2. Градиент с блеском -->
        <a href="#" class="btn btn-grad" target="_blank">
            Градиент
        </a>

        <!-- 3. Стеклянная (glass) -->
        <a href="#" class="btn btn-glass" target="_blank">
            🌫 Стекло
        </a>

        <!-- 4. Тёмная с иконкой -->
        <a href="#" class="btn btn-dark" target="_blank">
             Тёмная
        </a>

        <!-- 5. Акцентная обводка -->
        <a href="#" class="btn btn-outline" target="_blank">
            Обводка
        </a>

    </div>



<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>