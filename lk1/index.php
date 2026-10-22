<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("XSLS");

use Lab\Helpers\UsersHelpers as UH;
use Bitrix\Main\UserTable;

?>

    <style>
        /* Базовые настройки */
        :root {
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-secondary: #6b7280;
            --accent-red: #d92d20;
            --accent-green: #b7e27b;
            --border-color: #e5e7eb;
            --radius: 12px;
            --font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.5;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        /* Шапка */
        .top-header {
            background: white;
            padding: 15px 0;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .top-header .logo {
            font-size: 20px;
            font-weight: bold;
            color: var(--text-secondary);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }

        /* Сетка макета */
        .layout {
            display: grid;
            grid-template-columns: 320px 1fr; /* Левая колонка 320px, правая - остальное */
            gap: 24px;
            padding-bottom: 40px;
        }

        /* Карточки (общий стиль) */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 18px;
            font-weight: 600;
        }

        h3 {
            font-size: 16px;
            font-weight: 600;
        }

        h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* Кнопки */
        .btn {
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
            padding: 8px 16px;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: var(--accent-red);
            color: white;
        }

        .btn-primary:hover {
            background-color: #b91c1c;
        }

        .btn-outline {
            background: white;
            border: 1px solid var(--border-color);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: #f9fafb;
        }

        .btn-small {
            font-size: 12px;
            padding: 6px 10px;
        }

        /* Иконки */
        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: var(--text-secondary);
        }

        /* --- ЛЕВАЯ КОЛОНКА --- */
        .profile-card {
            text-align: center;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .role-badge {
            border: 1px solid var(--border-color);
            padding: 4px 10px;
            border-radius: 12px;
            color: var(--text-secondary);
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--text-secondary);
        }

        .dot {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
        }

        .avatar-large img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }

        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .arrow {
            color: var(--text-secondary);
            cursor: pointer;
        }

        /* Пустые состояния (Empty states) */
        .empty-state {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .empty-state p {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle.red {
            background-color: var(--accent-red);
            color: white;
        }

        .icon-circle svg {
            width: 24px;
            height: 24px;
        }

        /* Теги (Компетенции) */
        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            border: 1px solid var(--accent-red);
            color: var(--accent-red);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
        }

        /* Благодарности */
        .badges-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .badge-item {
            aspect-ratio: 1;
            border: 1px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            filter: grayscale(100%);
            opacity: 0.6;
        }

        /* --- ПРАВАЯ КОЛОНКА --- */
        .main-header-card {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .role-text {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Информация */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-item .label {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .info-item .value {
            font-size: 14px;
        }

        .link {
            color: var(--accent-red);
        }

        .card-footer-link {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
        }

        .card-footer-link a {
            color: var(--accent-red);
            font-size: 13px;
        }

        /* Дерево */
        .org-tree ul {
            padding-left: 20px;
            border-left: 1px solid var(--border-color);
            margin-left: 10px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .org-tree li {
            position: relative;
            padding: 5px 0;
            font-size: 14px;
            color: var(--text-main);
        }

        .active-node {
            color: var(--accent-red);
            font-weight: 500;
        }

        .manager-info {
            margin-top: 20px;
        }

        .manager-card {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
        }

        .avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        .manager-name {
            font-weight: 600;
            font-size: 14px;
        }

        .manager-pos {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .link-small {
            color: var(--accent-red);
            font-size: 12px;
            display: inline-block;
            margin-top: 5px;
        }

        /* Сервисы (Зеленая зона) */
        .services-section {
            background-color: var(--accent-green);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 24px;
        }

        .services-header h2 {
            margin-bottom: 15px;
        }

        .tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .tab {
            font-size: 14px;
            cursor: pointer;
            color: rgba(0, 0, 0, 0.6);
        }

        .tab.active {
            color: black;
            font-weight: 600;
            border-bottom: 2px solid black;
            padding-bottom: 4px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .service-card {
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .service-card.white {
            background: white;
        }

        .img-placeholder {
            background: #f3f4f6;
            height: 80px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .dms-info p {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .service-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
            padding-top: 15px;
        }

        /* Заявки (Таблица) */
        .tabs-sub {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .sub-tab {
            padding-bottom: 10px;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .sub-tab.active {
            color: black;
            border-bottom: 2px solid black;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .requests-table th {
            text-align: left;
            color: var(--text-secondary);
            font-weight: normal;
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .requests-table td {
            padding: 15px 10px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .process-visual {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .avatar-tiny {
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }

        .process-status {
            font-size: 12px;
            color: var(--text-secondary);
            width: 100%;
            margin-top: 4px;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-dot.yellow {
            background-color: #fbbf24;
        }

        .date-icon {
            color: var(--text-secondary);
            margin-right: 5px;
        }

        /* Пагинация */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .page-btn {
            border: 1px solid var(--border-color);
            background: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            margin: 0 2px;
        }

        .page-btn.active {
            background: var(--accent-red);
            color: white;
            border-color: var(--accent-red);
        }

        .per-page {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Обо мне */
        .empty-state.horizontal {
            display: flex;
            flex-direction: row;
            text-align: left;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .empty-state.horizontal p {
            margin: 0 20px;
            flex: 1;
        }


        /* --- АДАПТИВНОСТЬ --- */

        /* Планшеты (горизонтальные) */
        @media (max-width: 1024px) {
            .layout {
                grid-template-columns: 1fr; /* Одна колонка */
            }

            .sidebar {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .sidebar .card {
                margin-bottom: 0;
            }

            .profile-card {
                grid-column: 1 / -1;
            }

            /* Профиль на всю ширину */
        }

        /* Мобильные устройства */
        @media (max-width: 768px) {
            .top-header .container {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .mobile-menu-btn {
                display: block;
            }

            .sidebar {
                grid-template-columns: 1fr;
            }

            .main-header-card {
                flex-direction: column;
                gap: 15px;
            }

            .main-header-card .btn {
                width: 100%;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .empty-state.horizontal {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .empty-state.horizontal p {
                margin: 0;
            }

            .requests-table thead {
                display: none;
            }

            /* Скрываем заголовки таблиц */
            .requests-table tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                padding: 10px;
            }

            .requests-table td {
                display: flex;
                justify-content: space-between;
                padding: 5px 0;
                border: none;
            }

            .requests-table td::before {
                content: attr(data-label); /* Можно добавить data-label в HTML для мобильной версии */
                font-weight: bold;
                color: var(--text-secondary);
            }
        }
    </style>


    <!-- Шапка сайта (упрощенно) -->
    <header class="top-header">
        <div class="container">
            <div class="logo">Личный кабинет</div>
            <!-- Иконка меню для мобильных -->
            <button class="mobile-menu-btn">☰</button>
        </div>
    </header>

    <div class="container layout">

        <!-- ЛЕВАЯ КОЛОНКА (ASIDE) -->
        <aside class="sidebar">

            <!-- Карточка профиля -->
            <div class="card profile-card">
                <div class="profile-header">
                    <span class="role-badge">Администратор</span>
                    <span class="status-indicator"><span class="dot"></span> В сети</span>
                </div>
                <div class="avatar-large">
                    <img src="https://via.placeholder.com/150" alt="Фото профиля">
                </div>
            </div>

            <!-- Замещающие -->
            <div class="card">
                <div class="card-header-flex">
                    <h3>Замещающие</h3>
                    <span class="arrow">›</span>
                </div>
                <div class="empty-state">
                    <div class="icon-circle red">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                    </div>
                    <p>У вас пока нет замещающих. Добавьте замещающих на необходимый период и эта информация будет
                        доступна вашим коллегам.</p>
                    <button class="btn btn-primary">Добавить +</button>
                </div>
            </div>

            <!-- Компетенции -->
            <div class="card">
                <div class="card-header-flex">
                    <h3>Компетенции</h3>
                    <button class="icon-btn">✎</button>
                </div>
                <div class="tags">
                    <span class="tag">SQL</span>
                    <span class="tag">figma</span>
                    <span class="tag">php</span>
                    <span class="tag">jquery</span>
                    <span class="tag">bitrix24</span>
                    <span class="tag">bitrix</span>
                    <span class="tag">js</span>
                    <span class="tag">верстка</span>
                    <span class="tag">html</span>
                    <span class="tag">css</span>
                    <span class="tag">фотошоп</span>
                </div>
            </div>

            <!-- Благодарности -->
            <div class="card">
                <div class="card-header-flex">
                    <h3>Благодарности</h3>
                </div>
                <div class="badges-grid">
                    <!-- Заглушки для иконок -->
                    <div class="badge-item">🏆</div>
                    <div class="badge-item">🎯</div>
                    <div class="badge-item">🚀</div>
                    <div class="badge-item">💡</div>
                    <div class="badge-item">🤝</div>
                    <div class="badge-item">⭐</div>
                    <div class="badge-item">📈</div>
                    <div class="badge-item">🏅</div>
                </div>
            </div>

            <!-- Мои интересы -->
            <div class="card">
                <div class="card-header-flex">
                    <h3>Мои интересы</h3>
                </div>
                <div class="empty-state">
                    <div class="icon-circle red">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18V5l12-2v13"></path>
                            <circle cx="6" cy="18" r="3"></circle>
                            <circle cx="18" cy="16" r="3"></circle>
                        </svg>
                    </div>
                    <p>Создавайте интересы или присоединяйтесь к уже существующим. Найдите друзей, разделяющих ваши
                        увлечения.</p>
                    <button class="btn btn-primary">Добавить +</button>
                </div>
            </div>

        </aside>

        <!-- ПРАВАЯ КОЛОНКА (MAIN) -->
        <main class="main-content">

            <!-- Заголовок профиля -->
            <div class="card main-header-card">
                <div class="name-block">
                    <h1>Соколов Алексей Владимирович</h1>
                    <span class="role-text">Куратор проектов</span>
                </div>
                <button class="btn btn-outline">Указать Статус</button>
            </div>

            <!-- Контактная информация -->
            <div class="card">
                <div class="card-header-flex">
                    <h2>Контактная информация</h2>
                    <button class="icon-btn">✎</button>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Подразделение</span>
                        <span class="value">Управление развития корпоративной культуры и мотивации персонала</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Город</span>
                        <span class="value">Брянск г., Брянск г.</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Контактный e-mail</span>
                        <span class="value link">SokolovAV26@mos.ru</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Рабочий телефон</span>
                        <span class="value">+7 (980) 331-57-25</span>
                    </div>
                </div>
                <div class="card-footer-link">
                    <a href="#">Подробная информация ▾</a>
                </div>
            </div>

            <!-- Организационная структура -->
            <div class="card">
                <div class="card-header-flex">
                    <h2>Организационная структура <span class="arrow">›</span></h2>
                </div>
                <ul class="org-tree">
                    <li>Генеральный директор
                        <ul>
                            <li>Дирекция по персоналу
                                <ul>
                                    <li class="active-node">Управление развития корпоративной культуры и мотивации
                                        персонала
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="manager-info">
                    <span class="label">Руководитель</span>
                    <div class="manager-card">
                        <img src="https://via.placeholder.com/40" alt="Руководитель" class="avatar-small">
                        <div>
                            <div class="manager-name">Дарина Киреева</div>
                            <div class="manager-pos">Начальник управления</div>
                        </div>
                    </div>
                    <a href="#" class="link-small">Показать команду ▾</a>
                </div>
            </div>

            <!-- Мои сервисы (Зеленая плашка) -->
            <div class="services-section">
                <div class="services-header">
                    <h2>Мои сервисы <span class="arrow">›</span></h2>
                </div>
                <div class="tabs">
                    <span class="tab active">Основное</span>
                    <span class="tab">Избранное</span>
                </div>
                <div class="services-grid">
                    <!-- Карточка 1 -->
                    <div class="service-card white">
                        <h4>Отпуска</h4>
                        <div class="img-placeholder"></div>
                    </div>
                    <!-- Карточка 2 -->
                    <div class="service-card white">
                        <h4>Полис ДМС</h4>
                        <div class="dms-info">
                            <p>1869943-900/26-42</p>
                            <p>Компания: РЕСО Гарантия</p>
                            <p>Срок действия: 31.03.2027</p>
                            <p>Программа: Стандарт + (Регионы)</p>
                        </div>
                        <div class="service-actions">
                            <button class="btn btn-small btn-outline">Мои клиники 🏥</button>
                            <button class="btn btn-small btn-outline">Приложение ▾</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Мои заявки -->
            <div class="card">
                <div class="card-header-flex">
                    <h2>Мои заявки</h2>
                    <button class="btn btn-primary">Добавить +</button>
                </div>

                <div class="tabs-sub">
                    <span class="sub-tab active">Активные</span>
                    <span class="sub-tab">Закрыты</span>
                </div>

                <div class="table-responsive">
                    <table class="requests-table">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Заявка</th>
                            <th>Процесс</th>
                            <th>Статус</th>
                            <th>Срок</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Строка 1 -->
                        <tr>
                            <td>604</td>
                            <td>Заявка на обучение</td>
                            <td>
                                <div class="process-visual">
                                    <img src="https://via.placeholder.com/30" class="avatar-tiny">
                                    <span>→</span>
                                    <img src="https://via.placeholder.com/30" class="avatar-tiny">
                                    <div class="process-status">На согласовании</div>
                                </div>
                            </td>
                            <td><span class="status-dot yellow"></span> Согласование</td>
                            <td><span class="date-icon">📅</span> 26.03.2026</td>
                        </tr>
                        <!-- Строка 2 -->
                        <tr>
                            <td>603</td>
                            <td>Заявка на обучение</td>
                            <td>
                                <div class="process-visual">
                                    <img src="https://via.placeholder.com/30" class="avatar-tiny">
                                    <span>→</span>
                                    <img src="https://via.placeholder.com/30" class="avatar-tiny">
                                    <div class="process-status">На согласовании</div>
                                </div>
                            </td>
                            <td><span class="status-dot yellow"></span> Согласование</td>
                            <td><span class="date-icon">📅</span> 26.03.2026</td>
                        </tr>
                        <!-- Строка 3 -->
                        <tr>
                            <td>602</td>
                            <td>Заявка на обучение</td>
                            <td>
                                <div class="process-visual">
                                    <img src="https://via.placeholder.com/30" class="avatar-tiny">
                                    <span>→</span>
                                    <img src="https://via.placeholder.com/30" class="avatar-tiny">
                                    <div class="process-status">На согласовании</div>
                                </div>
                            </td>
                            <td><span class="status-dot yellow"></span> Согласование</td>
                            <td><span class="date-icon">📅</span> 26.03.2026</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Пагинация -->
                <div class="pagination">
                    <div class="pages">
                        <button class="page-btn">‹</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">›</button>
                    </div>
                    <div class="per-page">
                        На странице: <select>
                            <option>5</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Обо мне -->
            <div class="card">
                <h2>Обо мне</h2>
                <div class="empty-state horizontal">
                    <div class="icon-circle red">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <p>Делитесь интересными историями из жизни, или просто расскажите о себе, загружайте фотографии
                        памятных моментов.</p>
                    <button class="btn btn-primary">Рассказать о себе</button>
                </div>
            </div>

        </main>
    </div>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>