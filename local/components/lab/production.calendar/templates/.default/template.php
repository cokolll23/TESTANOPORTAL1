<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$daysJson = htmlspecialcharsbx($arResult['DATA_JSON']);
?>

<div
        class="production-calendar"
        data-calendar
        data-days="<?= $daysJson ?>"
        data-min-date="<?= htmlspecialcharsbx($arResult['MIN_DATE']) ?>"
        data-max-date="<?= htmlspecialcharsbx($arResult['MAX_DATE']) ?>"
>
    <div class="production-calendar__header">
        <div>
            <h2 class="production-calendar__title">
                Производственный календарь <?= (int)$arResult['YEAR'] ?>
            </h2>

            <div class="production-calendar__legend">
                <span>
                    <i class="pc-color pc-color--workday"></i>
                    Рабочий день
                </span>

                <span>
                    <i class="pc-color pc-color--weekend"></i>
                    Выходной
                </span>

                <span>
                    <i class="pc-color pc-color--holiday"></i>
                    Праздник
                </span>

                <span>
                    <i class="pc-color pc-color--reduced"></i>
                    Сокращённый
                </span>
            </div>
        </div>

        <div class="production-calendar__selection">
            <div class="pc-date-field">
                <label for="pc-date-from">Начало отпуска</label>
                <input type="date" id="pc-date-from" data-date-from>
            </div>

            <div class="pc-date-field">
                <label for="pc-date-to">Окончание отпуска</label>
                <input type="date" id="pc-date-to" data-date-to>
            </div>
        </div>
    </div>

    <div class="production-calendar__info">
        <div class="pc-info-item">
            <span>Календарных дней</span>
            <strong data-calendar-days>0</strong>
        </div>

        <div class="pc-info-item">
            <span>Рабочих дней</span>
            <strong data-working-days>0</strong>
        </div>

        <div class="pc-info-item">
            <span>Рабочих часов</span>
            <strong data-working-hours>0</strong>
        </div>
    </div>

    <div class="production-calendar__months">
        <?php foreach ($arResult['MONTHS'] as $month): ?>
            <section class="pc-month">
                <div class="pc-month__header">
                    <h3><?= htmlspecialcharsbx($month['title']) ?></h3>

                    <span>
                        <?= (int)$month['workDays'] ?> раб. дней /
                        <?= htmlspecialcharsbx($month['hours']) ?> ч.
                    </span>
                </div>

                <div class="pc-weekdays">
                    <span>Пн</span>
                    <span>Вт</span>
                    <span>Ср</span>
                    <span>Чт</span>
                    <span>Пт</span>
                    <span>Сб</span>
                    <span>Вс</span>
                </div>

                <div class="pc-days">
                    <?php
                    $firstDay = $month['days'][0];
                    $emptyCells = $firstDay['weekday'] - 1;
                    ?>

                    <?php for ($i = 0; $i < $emptyCells; $i++): ?>
                        <span class="pc-day pc-day--empty"></span>
                    <?php endfor; ?>

                    <?php foreach ($month['days'] as $day): ?>
                        <button
                                type="button"
                                class="pc-day pc-day--<?= htmlspecialcharsbx($day['type']) ?>"
                                data-day
                                data-date="<?= htmlspecialcharsbx($day['date']) ?>"
                                title="<?= htmlspecialcharsbx($day['title']) ?>"
                        >
                            <span class="pc-day__number">
                                <?= (int)$day['day'] ?>
                            </span>

                            <?php if ($day['hours'] > 0): ?>
                                <span class="pc-day__hours">
                                    <?= htmlspecialcharsbx($day['hours']) ?> ч.
                                </span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <div class="production-calendar__footer">
        <div class="pc-selected-period" data-selected-period>
            Период не выбран
        </div>

        <button
                type="button"
                class="pc-clear-button"
                data-clear-selection
        >
            Очистить период
        </button>
    </div>
</div>

<!-- Обёртка скрыта, саму форму BX перенесёт в попап -->
<div style="display: none;">
    <form id="workday-form" action="/ajax/workday.php" method="post">
        <div>
            <label for="workday-date">Дата</label>
            <input
                    type="text"
                    id="workday-date"
                    name="date"
                    readonly
            >
        </div>

        <div>
            <label for="workday-comment">Комментарий</label>
            <textarea
                    id="workday-comment"
                    name="comment"
            ></textarea>
        </div>

        <button type="submit">Отправить</button>
    </form>
</div>