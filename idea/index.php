<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
?>
<style>
    /* ----- Сброс и общие стили ----- */


    .form-card {

        width: 100%;
        background: white;
        padding: 30px 35px;
        border-radius: 28px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    }

    h3 {
        font-size: 22px;
        font-weight: 600;
        margin-top: 0;
        margin-bottom: 10px;
        color: #0b1e33;
    }

    .form-step {
        transition: opacity 0.15s ease;
    }

    .field-row {
        margin: 18px 0 12px;
    }

    .field-row label {
        display: inline-block;
        margin-bottom: 4px;
        font-weight: 500;
        color: #1f2a3a;
    }

    .field-row input[type="text"],
    .field-row textarea,
    .field-row select {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #d9e1ec;
        border-radius: 14px;
        font-size: 15px;
        background: #fafcff;
        transition: 0.2s;
    }

    .field-row textarea {
        min-height: 100px;
        resize: vertical;
    }

    .field-row input:focus,
    .field-row textarea:focus {
        border-color: #2b6fdb;
        outline: none;
        background: white;
        box-shadow: 0 0 0 4px rgba(43, 111, 219, 0.12);
    }

    .required {
        color: #c9353e;
        font-weight: 600;
        margin-left: 2px;
    }

    .checkbox-group .field-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #1f6feb;
        cursor: pointer;
    }

    .radio-group {
        background: #f8faff;
        padding: 16px 18px;
        border-radius: 20px;
        margin: 12px 0 16px;
    }

    .custom-radio {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 0;
    }

    .custom-radio input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #1f6feb;
        cursor: pointer;
    }

    .radio-hint {
        font-size: 14px;
        color: #64748b;
        margin-top: 6px;
    }

    .step-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
        border-top: 1px solid #e9eef4;
        padding-top: 22px;
    }

    button, .btn-submit {
        background: white;
        border: 1.5px solid #d0d9e6;
        padding: 12px 28px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 15px;
        color: #1f2a3a;
        cursor: pointer;
        transition: 0.15s;
        background: #f8faff;
    }

    button:hover:not(:disabled) {
        background: #eaf0fa;
        border-color: #a0b3d0;
    }

    .next-step {
        background: #1f6feb;
        border-color: #1f6feb;
        color: white;
        margin-left: auto;
    }

    .next-step:hover:not(:disabled) {
        background: #f04c56;
        border-color: #f04c56;
    }

    .prev-step {
        background: transparent;
        border-color: transparent;
    }

    .prev-step:hover {
        background: #eef3f9;
        border-color: #ccd7e8;
    }

    .btn-submit {
        background: #0d9e5b;
        border-color: #0d9e5b;
        color: white;
        font-size: 16px;
        padding: 12px 36px;
    }

    .btn-submit:hover {
        background: #0a844c;
        border-color: #0a844c;
    }

    /* ----- Drag & Drop зона (шаг 6) ----- */
    .drop-zone {
        border: 2.5px dashed #b8cbe0;
        border-radius: 24px;
        padding: 30px 20px 24px;
        text-align: center;
        background: #fafdff;
        transition: 0.2s;
        margin: 12px 0 6px;
        cursor: pointer;
    }

    .drop-zone.dragover {
        border-color: #1f6feb;
        background: #edf4fe;
    }

    .drop-zone p {
        margin: 0 0 6px;
        color: #33475b;
        font-weight: 500;
    }

    .drop-zone span {
        font-size: 14px;
        color: #64748b;
    }

    .drop-zone input[type="file"] {
        display: none;
    }

    .file-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 14px;
        margin: 16px 0 6px;
        padding: 0;
        list-style: none;
    }

    .file-list li {
        background: #eef5fe;
        padding: 6px 16px 6px 14px;
        border-radius: 40px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 450;
        border: 1px solid #c7daf0;
    }

    .file-list .remove-file {
        background: transparent;
        border: none;
        font-size: 18px;
        line-height: 1;
        color: #8f9eb2;
        cursor: pointer;
        padding: 0 4px;
    }

    .file-list .remove-file:hover {
        color: #b02a37;
    }

    .file-list .file-size {
        color: #4a5b72;
        font-size: 12px;
        font-weight: 400;
    }

    #fileCount {
        font-size: 14px;
        color: #2b4b74;
        margin-left: 6px;
    }

    .form-footer-note {
        font-size: 13px;
        color: #5d738c;
        margin-top: 14px;
    }

    /* лоадер */
    .loader {
        display: none;
        margin-left: 14px;
        width: 20px;
        height: 20px;
        border: 3px solid #e2e9f2;
        border-top: 3px solid #1f6feb;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .status-msg {
        margin-top: 18px;
        padding: 14px 18px;
        border-radius: 16px;
        background: #eaf6ed;
        border-left: 6px solid #0d9e5b;
        color: #0a4a30;
        display: none;
    }

    .status-msg.error {
        background: #fde8e8;
        border-left-color: #c9353e;
        color: #801f26;
    }
</style>
<style>
    /* Контейнер */
    .multi-step-form {
        max-width: 700px;
        margin: 0 auto;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Индикаторы шагов */
    .steps-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }

    .steps-indicator::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 10%;
        right: 10%;
        height: 2px;
        background: #ddd;
        z-index: 0;
    }

    .step {
        width: 40px;
        height: 40px;
        background: #fff;
        border: 2px solid #ddd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #999;
        position: relative;
        z-index: 1;
        cursor: default;
        transition: 0.3s;
    }

    .step.active {
        border-color: #007aff;
        background: #007aff;
        color: #fff;
        transform: scale(1.1);
    }

    .step.done {
        border-color: #28a745;
        background: #28a745;
        color: #fff;
    }

    /* Шаги формы */
    .form-step {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .form-step h3 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #007aff;
        padding-bottom: 10px;
    }

    /* Поля */
    .field-row {
        margin-bottom: 18px;
    }

    .field-row label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }

    .field-row input,
    .field-row select,
    .field-row textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
        transition: 0.3s;
        box-sizing: border-box;
    }

    .field-row input:focus,
    .field-row select:focus,
    .field-row textarea:focus {
        border-color: #007aff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.2);
    }

    /* Ошибка */
    .error {
        border-color: #dc3545 !important;
        background-color: #fff0f0;
    }

    /* Кнопки */
    .next-step, .prev-step, .btn-submit {
        padding: 10px 25px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
        margin-right: 8px;
    }

    .next-step {
        background: #E30613;
        color: #fff;
    }

    .next-step:hover {
        background: #f04c56;
    }

    .prev-step {
        background: #6c757d;
        color: #fff;
    }

    .prev-step:hover {
        background: #5a6268;
    }

    .btn-submit {
        background: #28a745;
        color: #fff;
        font-weight: bold;
    }

    .btn-submit:hover {
        background: #1e7e34;
    }
</style>
<style>
    /* Drag & Drop зона */
    .drop-zone {
        border: 2px dashed #ccc;
        border-radius: 10px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f9f9f9;
        margin: 20px 0;
        position: relative;
    }

    .drop-zone:hover {
        border-color: #0073b7;
        background: #f0f7fc;
    }

    .drop-zone.dragover {
        border-color: #0073b7;
        background: #e8f4fd;
        transform: scale(1.02);
    }

    .drop-zone-content {
        pointer-events: none;
    }

    .drop-icon {
        font-size: 48px;
        display: block;
        margin-bottom: 10px;
    }

    .browse-link {
        color: #0073b7;
        text-decoration: underline;
        cursor: pointer;
    }

    .drop-zone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Список файлов */
    .file-list {
        margin: 15px 0;
    }

    .file-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        background: #f5f5f5;
        border-radius: 5px;
        margin-bottom: 8px;
        animation: slideIn 0.3s ease;
    }

    .file-item .file-icon {
        font-size: 20px;
        margin-right: 10px;
    }

    .file-item .file-name {
        flex: 1;
        font-size: 14px;
        color: #333;
    }

    .file-item .file-size {
        font-size: 12px;
        color: #666;
        margin: 0 15px;
    }

    .file-item .file-remove {
        background: none;
        border: none;
        color: #e74c3c;
        cursor: pointer;
        font-size: 18px;
        padding: 0 5px;
        transition: color 0.3s;
    }

    .file-item .file-remove:hover {
        color: #c0392b;
    }

    /* Прогресс загрузки */
    .progress-bar-container {
        margin: 15px 0;
        background: #f0f0f0;
        border-radius: 5px;
        overflow: hidden;
        position: relative;
        height: 30px;
    }

    .progress-bar {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #0073b7, #00a0e3);
        transition: width 0.3s ease;
    }

    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 14px;
        color: #333;
        font-weight: bold;
    }

    /* Уведомления */
    #notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }

    .notification-item {
        padding: 15px 20px;
        margin-bottom: 10px;
        border-radius: 5px;
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideInRight 0.5s ease;
    }

    .notification-item.success {
        background: #27ae60;
    }

    .notification-item.error {
        background: #e74c3c;
    }

    .notification-item.info {
        background: #3498db;
    }

    /* Анимации */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .form-card {
        background: white;

        width: 100%;
        padding: 35px 30px;
        border-radius: 28px;
        box-shadow: 0 20px 40px -12px rgba(0, 20, 30, 0.25);
        transition: 0.25s ease;
    }

    h2 {
        font-weight: 600;
        font-size: 26px;
        color: #0b2b3f;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .subhead {
        color: #5b6f7e;
        font-size: 15px;
        border-bottom: 2px solid #e9edf2;
        padding-bottom: 18px;
        margin-bottom: 24px;
    }

    /* Группа радио-кнопок */
    .radio-group {
        margin-bottom: 28px;
    }

    .radio-group h3 {
        font-weight: 500;
        font-size: 18px;
        color: #1a3b4f;
        margin-bottom: 14px;
    }

    /* Кастомные радио-кнопки */
    .custom-radio {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8fafc;
        padding: 12px 18px;
        border-radius: 60px;
        margin-bottom: 10px;
        border: 1.5px solid transparent;
        transition: 0.2s;
        cursor: pointer;
    }

    .custom-radio:hover {
        background: #f1f5f9;
        border-color: #cbd8e6;
    }

    .custom-radio input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #b6c9db;
        border-radius: 50%;
        outline: none;
        transition: 0.15s;
        flex-shrink: 0;
        position: relative;
        background: white;
        cursor: pointer;
    }

    .custom-radio input[type="radio"]:checked {
        border-color: #0f6cbf;
        border-width: 6px;
        background: white;
    }

    .custom-radio input[type="radio"]:focus-visible {
        box-shadow: 0 0 0 3px rgba(15, 108, 191, 0.3);
    }

    .custom-radio label {
        font-size: 16px;
        color: #153243;
        font-weight: 450;
        cursor: pointer;
        flex: 1;
    }

    /* Доп. описание под радио */
    .radio-hint {
        font-size: 13px;
        color: #6c8396;
        margin-left: 32px;
        margin-top: -4px;
        margin-bottom: 14px;
        padding-left: 6px;
    }

    /* Кнопки и поля */
    .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 10px;
        align-items: center;
    }

    .btn {
        background: #eef2f6;
        border: none;
        padding: 12px 28px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 15px;
        color: #1d3b4f;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid transparent;
    }

    .btn-primary {
        background: #0f6cbf;
        color: white;
        box-shadow: 0 6px 14px rgba(15, 108, 191, 0.25);
    }

    .btn-primary:hover {
        background: #0a5aa3;
        transform: scale(1.01);
        box-shadow: 0 10px 20px rgba(15, 108, 191, 0.3);
    }

    .btn-secondary {
        background: transparent;
        border-color: #ced9e6;
    }

    .btn-secondary:hover {
        background: #e4eaf2;
    }

    #result-area {
        margin-top: 25px;
        padding: 16px 20px;
        background: #f2f7fd;
        border-radius: 18px;
        border-left: 6px solid #0f6cbf;
        font-size: 15px;
        color: #1f3b4e;
        display: none;
        word-break: break-word;
    }

    #result-area.show {
        display: block;
    }

    .inline-flex {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px 25px;
        background: #f9fbfd;
        padding: 12px 20px;
        border-radius: 60px;
        margin: 12px 0 8px;
    }

    .inline-flex .custom-radio {
        margin-bottom: 0;
        padding: 4px 12px 4px 8px;
        background: transparent;
        border: none;
        border-radius: 40px;
    }

    .inline-flex .custom-radio:hover {
        background: #eef3f8;
    }

    hr {
        margin: 22px 0 18px;
        border: 0;
        height: 1px;
        background: #dfe7ef;
    }

    small {
        color: #5e7a8f;
    }
</style>
<style>
    /* Стили для drag-and-drop зоны */
    .drop-zone {
        border: 2px dashed #ccc;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafafa;
        position: relative;
        margin: 15px 0;
    }

    .drop-zone:hover,
    .drop-zone.dragover {
        border-color: #1e88e5;
        background: #e3f2fd;
    }

    .drop-zone-content {
        pointer-events: none;
    }

    .drop-icon {
        font-size: 48px;
        display: block;
        margin-bottom: 10px;
    }

    .drop-link {
        color: #1e88e5;
        text-decoration: underline;
        cursor: pointer;
    }

    .drop-hint {
        font-size: 12px;
        color: #999;
        margin-top: 8px;
    }

    #fileInput {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Список файлов */
    .file-list {
        margin: 15px 0;
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        background: #f5f5f5;
        border-radius: 4px;
        margin-bottom: 5px;
    }

    .file-item .file-name {
        flex: 1;
        margin-left: 10px;
    }

    .file-item .file-size {
        color: #666;
        font-size: 12px;
        margin: 0 15px;
    }

    .file-item .remove-file {
        color: #f44336;
        cursor: pointer;
        font-weight: bold;
        padding: 0 5px;
    }

    .file-item .remove-file:hover {
        color: #d32f2f;
    }

    /* Индикатор загрузки */
    #formLoader {
        display: inline-block;
        padding: 10px 20px;
        background: #f5f5f5;
        border-radius: 4px;
        margin-left: 10px;
    }

    /* Сообщения */
    #formMessage {
        margin-top: 15px;
        padding: 10px;
        border-radius: 4px;
    }

    #formMessage.success {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }

    #formMessage.error {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ef9a9a;
    }

    /* Стили для Dropzone */
    .dropzone {
        border: 2px dashed #ccc;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafafa;
        position: relative;
    }

    .dropzone.dragover {
        border-color: #0055cc;
        background: #e8f0fe;
    }

    .dropzone-content {
        pointer-events: none;
    }

    .dropzone-content svg {
        color: #999;
        margin-bottom: 15px;
    }

    .dropzone-content p {
        margin: 5px 0;
        color: #555;
    }

    .browse-link {
        color: #0055cc;
        font-weight: 600;
        cursor: pointer;
    }

    .dropzone-hint {
        font-size: 13px;
        color: #999;
    }

    #fileInput {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Список файлов */
    .file-list {
        margin-top: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .file-item {
        display: inline-flex;
        align-items: center;
        background: #f0f4f9;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        gap: 8px;
        animation: fadeIn 0.3s ease;
    }

    .file-item .remove-file {
        cursor: pointer;
        color: #dc3545;
        font-weight: bold;
        margin-left: 5px;
    }

    .file-item .remove-file:hover {
        color: #a71d2a;
    }

    .file-size {
        color: #888;
        font-size: 12px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
<style>
    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        white-space: nowrap;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    .checkbox-group {
        margin: 0;
        padding: 0;
        border: 0;
    }

    .form-error {
        min-height: 22px;
        margin: 8px 0;
        color: #c62828;
        font-size: 14px;
    }

    .file-drop-zone {
        padding: 32px 20px;
        border: 2px dashed #8c98a4;
        border-radius: 12px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: border-color 0.2s ease,
        background-color 0.2s ease;
    }

    .file-drop-zone:hover,
    .file-drop-zone:focus,
    .file-drop-zone.is-dragover {
        border-color: #1677ff;
        background: #eef6ff;
        outline: none;
    }

    .file-drop-zone__title {
        margin-bottom: 6px;
        font-size: 18px;
        font-weight: 600;
    }

    .file-drop-zone__description {
        margin-bottom: 10px;
        color: #52606d;
    }

    .file-drop-zone__limits {
        color: #7b8794;
        font-size: 13px;
    }

    .selected-files {
        display: grid;
        gap: 8px;
        margin-top: 16px;
    }

    .selected-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 10px 12px;
        border: 1px solid #d8dee4;
        border-radius: 8px;
        background: #fff;
    }

    .selected-file__info {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .selected-file__remove {
        flex: none;
        padding: 5px 10px;
        border: 0;
        color: #c62828;
        background: transparent;
        cursor: pointer;
    }

    .form-message {
        margin: 15px 0;
        font-weight: 600;
    }

    .form-message.is-success {
        color: #237804;
    }

    .form-message.is-error {
        color: #c62828;
    }

    .btn-submit:disabled {
        cursor: wait;
        opacity: 0.65;
    }
</style>
<form
        name="idea_add"
        action="idea_add.php"
        method="POST"
        enctype="multipart/form-data"
        id="msFormSubmit"
        novalidate
>
    <?= bitrix_sessid_post() ?>

    <div class="form-step" data-step="1">
        <h3>Шаг 1: Кому поможет идея?</h3>
        <p>Выберите один или несколько вариантов.</p>

        <fieldset class="checkbox-group" data-required-group="audience">
            <legend class="visually-hidden">Кому поможет идея</legend>

            <div class="field-row">
                <input
                        type="checkbox"
                        name="audience[]"
                        id="audience_department"
                        value="department"
                >
                <label for="audience_department">
                    Сотрудникам моего подразделения
                </label>
            </div>

            <div class="field-row">
                <input
                        type="checkbox"
                        name="audience[]"
                        id="audience_departments"
                        value="departments"
                >
                <label for="audience_departments">
                    Нескольким подразделениям
                </label>
            </div>

            <div class="field-row">
                <input
                        type="checkbox"
                        name="audience[]"
                        id="audience_all"
                        value="all"
                >
                <label for="audience_all">
                    Всем сотрудникам
                </label>
            </div>

            <div class="field-row">
                <input
                        type="checkbox"
                        name="audience[]"
                        id="audience_projects"
                        value="projects"
                >
                <label for="audience_projects">
                    Проектам организации
                </label>
            </div>

            <div class="field-row">
                <input
                        type="checkbox"
                        name="audience[]"
                        id="audience_residents"
                        value="residents"
                >
                <label for="audience_residents">
                    Жителям и туристам
                </label>
            </div>
        </fieldset>

        <div class="form-error" data-error="audience" aria-live="polite"></div>

        <button type="button" class="next-step">Далее →</button>
    </div>

    <!-- ШАГ 2 -->
    <div class="form-step" data-step="2" style="display:none;">
        <h3>Шаг 2: В чем идея?</h3>
        <p>Опишите, что вы предлагаете изменить, улучшить или создать.</p>

        <div class="field-row">
            <label for="idea_description" class="visually-hidden">
                Описание идеи
            </label>

            <textarea
                    name="idea_description"
                    id="idea_description"
                    required
                    maxlength="10000"
                    placeholder="Кратко и понятно опишите суть идеи"
            ></textarea>
        </div>

        <div class="form-error" data-error="idea_description" aria-live="polite"></div>

        <button type="button" class="prev-step">← Назад</button>
        <button type="button" class="next-step">Далее →</button>
    </div>

    <!-- ШАГ 3 -->
    <div class="form-step" data-step="3" style="display:none;">
        <h3>Шаг 3: Какую проблему решает идея?</h3>
        <p>Опишите текущую ситуацию или сложность.</p>

        <div class="field-row">
            <label for="idea_problem" class="visually-hidden">
                Описание проблемы
            </label>

            <textarea
                    name="idea_problem"
                    id="idea_problem"
                    required
                    maxlength="10000"
                    placeholder="Опишите текущую проблему или сложность"
            ></textarea>
        </div>

        <div class="form-error" data-error="idea_problem" aria-live="polite"></div>

        <button type="button" class="prev-step">← Назад</button>
        <button type="button" class="next-step">Далее →</button>
    </div>

    <!-- ШАГ 4 -->
    <div class="form-step" data-step="4" style="display:none;">
        <h3>Шаг 4: Какой результат мы получим?</h3>
        <p>Как изменится работа или что улучшится после реализации идеи?</p>

        <div class="field-row">
            <label for="idea_result" class="visually-hidden">
                Ожидаемый результат
            </label>

            <textarea
                    name="idea_result"
                    id="idea_result"
                    required
                    maxlength="10000"
                    placeholder="Какие преимущества получат сотрудники, процессы или проекты"
            ></textarea>
        </div>

        <div class="form-error" data-error="idea_result" aria-live="polite"></div>

        <button type="button" class="prev-step">← Назад</button>
        <button type="button" class="next-step">Далее →</button>
    </div>
    <div class="form-step" data-step="5" style="display:none;">
        <h3>Шаг 5: Насколько трудно реализовать идею?</h3>
        <p>Оцените примерную сложность реализации.</p>

        <div class="radio-group">
            <div class="custom-radio">
                <input
                        type="radio"
                        name="complexity"
                        id="complexity_fast"
                        value="fast"
                        required
                >
                <label for="complexity_fast">
                    Можно реализовать быстро — до 1 месяца
                </label>
            </div>

            <div class="custom-radio">
                <input
                        type="radio"
                        name="complexity"
                        id="complexity_medium"
                        value="medium"
                >
                <label for="complexity_medium">
                    Потребуется проработка — от 1 до 6 месяцев
                </label>
            </div>

            <div class="custom-radio">
                <input
                        type="radio"
                        name="complexity"
                        id="complexity_long"
                        value="long"
                >
                <label for="complexity_long">
                    Долгосрочная идея — более 6 месяцев
                </label>
            </div>
        </div>

        <div class="form-error" data-error="complexity" aria-live="polite"></div>

        <button type="button" class="prev-step">← Назад</button>
        <button type="button" class="next-step">Далее →</button>
    </div>

    <div class="form-step" data-step="6" style="display:none;">
        <h3>Шаг 6: Есть материалы?</h3>
        <p>Прикрепите файлы или изображения. Это необязательно.</p>

        <div
                class="file-drop-zone"
                id="fileDropZone"
                tabindex="0"
                role="button"
                aria-controls="ideaFiles"
        >
            <input
                    type="file"
                    name="files[]"
                    id="ideaFiles"
                    multiple
                    hidden
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.webp,.zip"
            >

            <div class="file-drop-zone__title">
                Перетащите файлы сюда
            </div>

            <div class="file-drop-zone__description">
                или нажмите, чтобы выбрать
            </div>

            <div class="file-drop-zone__limits">
                Не более 10 файлов, каждый размером до 20 МБ
            </div>
        </div>

        <div id="selectedFiles" class="selected-files"></div>
        <div class="form-error" data-error="files" aria-live="polite"></div>
        <div class="form-message" id="formMessage" aria-live="polite"></div>

        <button type="button" class="prev-step">← Назад</button>

        <button type="submit" name="web_form_submit" class="btn-submit">
            Отправить заявку
        </button>
    </div>
</form>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        'use strict';

        const form = document.getElementById('msFormSubmit');

        if (!form) {
            return;
        }

        const steps = Array.from(form.querySelectorAll('.form-step'));
        const submitButton = form.querySelector('.btn-submit');
        const messageElement = document.getElementById('formMessage');

        const dropZone = document.getElementById('fileDropZone');
        const fileInput = document.getElementById('ideaFiles');
        const filesContainer = document.getElementById('selectedFiles');

        const maxFiles = 10;
        const maxFileSize = 20 * 1024 * 1024;

        const allowedExtensions = [
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'ppt',
            'pptx',
            'txt',
            'jpg',
            'jpeg',
            'png',
            'gif',
            'webp',
            'zip'
        ];

        let currentStep = 0;
        let selectedFiles = [];

        function showStep(index) {
            currentStep = Math.max(0, Math.min(index, steps.length - 1));

            steps.forEach(function (step, stepIndex) {
                const isCurrent = stepIndex === currentStep;

                step.style.display = isCurrent ? '' : 'none';
                step.setAttribute('aria-hidden', isCurrent ? 'false' : 'true');
            });

            const heading = steps[currentStep].querySelector('h3');

            if (heading) {
                heading.setAttribute('tabindex', '-1');
                heading.focus();
            }

            window.scrollTo({
                top: Math.max(
                    0,
                    form.getBoundingClientRect().top + window.scrollY - 30
                ),
                behavior: 'smooth'
            });
        }

        function setError(name, message) {
            const errorElement = form.querySelector(
                '[data-error="' + name + '"]'
            );

            if (errorElement) {
                errorElement.textContent = message || '';
            }
        }

        function clearStepErrors(step) {
            step.querySelectorAll('.form-error').forEach(function (element) {
                element.textContent = '';
            });
        }

        function validateStep(step) {
            clearStepErrors(step);

            const stepNumber = Number(step.dataset.step);
            let valid = true;

            if (stepNumber === 1) {
                const checkedAudience = step.querySelectorAll(
                    'input[name="audience[]"]:checked'
                );

                if (checkedAudience.length === 0) {
                    setError(
                        'audience',
                        'Выберите хотя бы один вариант.'
                    );
                    valid = false;
                }
            }

            if (stepNumber === 2) {
                const field = step.querySelector('[name="idea_description"]');

                if (!field.value.trim()) {
                    setError(
                        'idea_description',
                        'Опишите суть идеи.'
                    );
                    field.focus();
                    valid = false;
                }
            }

            if (stepNumber === 3) {
                const field = step.querySelector('[name="idea_problem"]');

                if (!field.value.trim()) {
                    setError(
                        'idea_problem',
                        'Опишите проблему, которую решает идея.'
                    );
                    field.focus();
                    valid = false;
                }
            }

            if (stepNumber === 4) {
                const field = step.querySelector('[name="idea_result"]');

                if (!field.value.trim()) {
                    setError(
                        'idea_result',
                        'Опишите ожидаемый результат.'
                    );
                    field.focus();
                    valid = false;
                }
            }

            if (stepNumber === 5) {
                const complexity = step.querySelector(
                    'input[name="complexity"]:checked'
                );

                if (!complexity) {
                    setError(
                        'complexity',
                        'Выберите сложность реализации.'
                    );
                    valid = false;
                }
            }

            return valid;
        }

        function validateAllSteps() {
            for (let index = 0; index < steps.length; index++) {
                if (!validateStep(steps[index])) {
                    showStep(index);
                    return false;
                }
            }

            return true;
        }

        form.addEventListener('click', function (event) {
            const nextButton = event.target.closest('.next-step');
            const previousButton = event.target.closest('.prev-step');

            if (nextButton) {
                if (validateStep(steps[currentStep])) {
                    showStep(currentStep + 1);
                }

                return;
            }

            if (previousButton) {
                showStep(currentStep - 1);
            }
        });

        function getFileKey(file) {
            return [
                file.name,
                file.size,
                file.lastModified
            ].join(':');
        }

        function validateFile(file) {
            if (file.size > maxFileSize) {
                return 'Файл «' + file.name + '» превышает 20 МБ.';
            }

            const extension = file.name.includes('.')
                ? file.name.split('.').pop().toLowerCase()
                : '';

            if (!allowedExtensions.includes(extension)) {
                return 'Тип файла «' + file.name + '» не разрешён.';
            }

            return '';
        }

        function addFiles(fileList) {
            setError('files', '');

            const existingKeys = new Set(
                selectedFiles.map(getFileKey)
            );

            for (const file of Array.from(fileList)) {
                if (selectedFiles.length >= maxFiles) {
                    setError(
                        'files',
                        'Можно загрузить не более ' + maxFiles + ' файлов.'
                    );
                    break;
                }

                const validationError = validateFile(file);

                if (validationError) {
                    setError('files', validationError);
                    continue;
                }

                const key = getFileKey(file);

                if (!existingKeys.has(key)) {
                    selectedFiles.push(file);
                    existingKeys.add(key);
                }
            }

            fileInput.value = '';
            renderFiles();
        }

        function renderFiles() {
            filesContainer.replaceChildren();

            if (selectedFiles.length === 0) {
                return;
            }

            selectedFiles.forEach(function (file, index) {
                const item = document.createElement('div');
                item.className = 'selected-file';

                const info = document.createElement('span');
                info.className = 'selected-file__info';
                info.textContent =
                    file.name + ' — ' + formatFileSize(file.size);

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'selected-file__remove';
                removeButton.textContent = 'Удалить';
                removeButton.dataset.fileIndex = String(index);
                removeButton.setAttribute(
                    'aria-label',
                    'Удалить файл ' + file.name
                );

                item.append(info, removeButton);
                filesContainer.append(item);
            });
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) {
                return bytes + ' Б';
            }

            if (bytes < 1024 * 1024) {
                return (bytes / 1024).toFixed(1) + ' КБ';
            }

            return (bytes / 1024 / 1024).toFixed(1) + ' МБ';
        }

        fileInput.addEventListener('change', function () {
            addFiles(fileInput.files);
        });

        filesContainer.addEventListener('click', function (event) {
            const button = event.target.closest('[data-file-index]');

            if (!button) {
                return;
            }

            const index = Number(button.dataset.fileIndex);

            if (Number.isInteger(index)) {
                selectedFiles.splice(index, 1);
                renderFiles();
            }
        });

        dropZone.addEventListener('click', function () {
            fileInput.click();
        });

        dropZone.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                fileInput.click();
            }
        });

        ['dragenter', 'dragover'].forEach(function (eventName) {
            dropZone.addEventListener(eventName, function (event) {
                event.preventDefault();
                dropZone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach(function (eventName) {
            dropZone.addEventListener(eventName, function (event) {
                event.preventDefault();
                dropZone.classList.remove('is-dragover');
            });
        });

        dropZone.addEventListener('drop', function (event) {
            addFiles(event.dataTransfer.files);
        });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            messageElement.textContent = '';
            messageElement.className = 'form-message';

            if (!validateAllSteps()) {
                return;
            }

            const formData = new FormData(form);

            /*
             * Удаляем значение исходного input и добавляем файлы
             * из внутреннего списка drag-and-drop.
             */
            formData.delete('files[]');

            selectedFiles.forEach(function (file) {
                formData.append('files[]', file, file.name);
            });

            submitButton.disabled = true;
            submitButton.textContent = 'Отправка...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const responseText = await response.text();
                let result;

                try {
                    result = JSON.parse(responseText);
                } catch (error) {
                    throw new Error(
                        'Обработчик вернул некорректный ответ.'
                    );
                }

                if (!response.ok || !result.success) {
                    throw new Error(
                        result.message || 'Не удалось отправить идею.'
                    );
                }

                messageElement.classList.add('is-success');

                if (result.url) {
                    const successText = document.createTextNode(
                        result.message + ' '
                    );

                    const link = document.createElement('a');
                    link.href = result.url;
                    link.textContent = 'Открыть идею';

                    messageElement.replaceChildren(successText, link);
                } else {
                    messageElement.textContent = result.message;
                }

                form.reset();
                selectedFiles = [];
                renderFiles();
            } catch (error) {
                messageElement.classList.add('is-error');
                messageElement.textContent =
                    error.message || 'Произошла ошибка отправки.';
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Отправить заявку';
            }
        });

        showStep(0);
    });
</script>
<?php
\Bitrix\Main\UI\Extension::load('ui.text-editor');
?>

<div id="editor-container"></div>
<?
$ID = 468;
$arPost = CBlogPost::GetByID($ID);
if(is_array($arPost))
    pretty_print($arPost);
else
    echo "Сообщение не найдено";
?>
<?
$ID = 115;
$arPost = CBlogPost::GetByID($ID);
if(is_array($arPost))
    pretty_print($arPost);
else
    echo "Сообщение не найдено";
?>
<?
// выберем все опубликованные сообщения всех блогов за апрель 2007 года
// у которых больше двух комментариев
$SORT = Array("DATE_PUBLISH" => "DESC", "NAME" => "ASC");
$arFilter = Array(

);
$dbPosts = CBlogPost::GetList(
        $SORT,
        $arFilter
);
while ($arPost = $dbPosts->Fetch())
{
    $arPosts[] = $arPost;
}
pretty_print($arPosts);
?>
<script>
    BX.ready(function () {
        const container = document.getElementById('editor-container');

        const editor = new BX.UI.TextEditor.TextEditor({
            content: '<p>Начальный текст</p>',
            placeholder: 'Введите текст...',
            autoFocus: false
        });

        editor.renderTo(container);

        // При необходимости сохраняем ссылку на редактор
        window.myTextEditor = editor;
    });
</script>
<?php

use Bitrix\Main\Loader;


if (!Loader::includeModule('iblock')) {
    throw new RuntimeException('Модуль iblock не установлен');
}



// Получаем один элемент, относящийся к текущему пользователю
$res = CIBlockElement::GetList(
    ['ACTIVE_FROM' => 'DESC'],
    [
        'IBLOCK_ID'    => 1,
        'ACTIVE'       => 'Y',
        //'=PROPERTY_USER' => 189,
    ],
    false,
    [],
    [
        'ID',

    ]
);

while ($arItem = $res->fetch()) {
   /* echo "<pre>";
    print_r($arItem);
    echo "</pre>";*/
}?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
