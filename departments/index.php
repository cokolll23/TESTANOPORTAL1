<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Оргструктура");

use Lab\Helpers\UsersHelpers as UH;
?>
<style>
    .horizontal-scroll-wrapper {
        position: relative;
        width: 100%;
        overflow: visible;
    }

    .scroll-container {
        overflow-x: auto;
        overflow-y: hidden;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .scroll-container::-webkit-scrollbar {
        height: 6px;
    }

    .scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .scroll-content {
        display: flex;
        gap: 20px;
        padding: 10px;
    }

    .scroll-item {
        flex-shrink: 0;
        width: 300px;
        height: 200px;
        background: #f0f0f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Стрелки - всегда по центру окна */
    .scroll-arrow {
        position: fixed;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 80px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 24px;
        z-index: 1000;
        transition: background 0.3s;
    }

    .scroll-arrow:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .scroll-left {
        left: 20px;
    }

    .scroll-right {
        right: 20px;
    }

    /* Показываем стрелки только при наведении на область с контентом */
    .horizontal-scroll-wrapper:hover .scroll-arrow {
        opacity: 1;
    }

    /* Адаптив для мобильных */
    @media (max-width: 768px) {
        .scroll-arrow {
            width: 30px;
            height: 60px;
            font-size: 18px;
        }

        .scroll-left {
            left: 10px;
        }

        .scroll-right {
            right: 10px;
        }
    }
    </style>

    <div class="horizontal-scroll-wrapper">
        <button class="scroll-arrow scroll-left" aria-label="Влево">←</button>
        <div class="scroll-container">
            <div class="scroll-content">
                <img src="departments.jpg">
            </div>
        </div>
        <button class="scroll-arrow scroll-right" aria-label="Вправо">→</button>
    </div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.querySelector('.horizontal-scroll-wrapper');
        const container = document.querySelector('.scroll-container');
        const leftArrow = document.querySelector('.scroll-left');
        const rightArrow = document.querySelector('.scroll-right');

        if (!container || !leftArrow || !rightArrow) return;

        let isHovering = false;

        // Функция для скролла
        function scrollLeft() {
            container.scrollBy({
                left: -300,
                behavior: 'smooth'
            });
        }

        function scrollRight() {
            container.scrollBy({
                left: 300,
                behavior: 'smooth'
            });
        }
        // Добавляем обработчики на стрелки
        leftArrow.addEventListener('mouseover', scrollLeft);
        rightArrow.addEventListener('mouseover', scrollRight);
        leftArrow.addEventListener('click', scrollLeft);
        rightArrow.addEventListener('click', scrollRight);

        // Управление видимостью стрелок при наведении
        wrapper.addEventListener('mouseenter', function() {
            isHovering = true;
            updateArrowsVisibility();
        });

        wrapper.addEventListener('mouseleave', function() {
            isHovering = false;
            updateArrowsVisibility();
        });

        function updateArrowsVisibility() {
            if (!isHovering) {
                leftArrow.style.opacity = '0';
                rightArrow.style.opacity = '0';
                return;
            }

            // Показываем стрелки в зависимости от позиции скролла
            const maxScroll = container.scrollWidth - container.clientWidth;

            leftArrow.style.opacity = container.scrollLeft > 0 ? '0.7' : '0.3';
            rightArrow.style.opacity = container.scrollLeft < maxScroll - 5 ? '0.7' : '0.3';

            // Делаем стрелки неактивными если скролл в крайней позиции
            leftArrow.style.pointerEvents = container.scrollLeft > 0 ? 'auto' : 'none';
            rightArrow.style.pointerEvents = container.scrollLeft < maxScroll - 5 ? 'auto' : 'none';
        }

        // Обновляем состояние стрелок при скролле
        container.addEventListener('scroll', updateArrowsVisibility);
        window.addEventListener('resize', updateArrowsVisibility);

        // Начальная проверка
        setTimeout(updateArrowsVisibility, 100);
    });
</script>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>