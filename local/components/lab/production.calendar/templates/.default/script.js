(function () {
    'use strict';

    function parseDate(dateString) {
        var parts = dateString.split('-');

        return new Date(Date.UTC(
            parseInt(parts[0], 10),
            parseInt(parts[1], 10) - 1,
            parseInt(parts[2], 10)
        ));
    }

    function formatDate(dateString) {
        var parts = dateString.split('-');

        return parts[2] + '.' + parts[1] + '.' + parts[0];
    }

    function dateToString(date) {
        var year = date.getUTCFullYear();
        var month = String(date.getUTCMonth() + 1).padStart(2, '0');
        var day = String(date.getUTCDate()).padStart(2, '0');

        return year + '-' + month + '-' + day;
    }

    function getDateRange(start, end) {
        var result = [];
        var current = parseDate(start);
        var last = parseDate(end);

        while (current <= last) {
            result.push(dateToString(current));
            current.setUTCDate(current.getUTCDate() + 1);
        }

        return result;
    }

    function initCalendar(root) {
        var days = JSON.parse(root.dataset.days || '{}');
        var minDate = root.dataset.minDate || '';
        var maxDate = root.dataset.maxDate || '';

        var fromInput = root.querySelector('[data-date-from]');
        var toInput = root.querySelector('[data-date-to]');
        var clearButton = root.querySelector('[data-clear-selection]');

        var calendarDaysNode = root.querySelector('[data-calendar-days]');
        var workingDaysNode = root.querySelector('[data-working-days]');
        var workingHoursNode = root.querySelector('[data-working-hours]');
        var selectedPeriodNode = root.querySelector('[data-selected-period]');

        var selectedStart = '';
        var selectedEnd = '';

        function isAllowedDate(date) {
            if (minDate && date < minDate) {
                return false;
            }

            if (maxDate && date > maxDate) {
                return false;
            }

            return true;
        }

        function resetVisualSelection() {
            root.querySelectorAll('[data-day]').forEach(function (node) {
                node.classList.remove(
                    'is-start',
                    'is-end',
                    'is-in-range'
                );
            });
        }

        function updateStatistics() {
            calendarDaysNode.textContent = '0';
            workingDaysNode.textContent = '0';
            workingHoursNode.textContent = '0';
            selectedPeriodNode.textContent = 'Период не выбран';

            if (!selectedStart || !selectedEnd) {
                return;
            }

            var range = getDateRange(selectedStart, selectedEnd);
            var calendarDays = range.length;
            var workingDays = 0;
            var workingHours = 0;

            range.forEach(function (date) {
                var day = days[date];

                if (!day) {
                    return;
                }

                if (parseFloat(day.hours) > 0) {
                    workingDays++;
                    workingHours += parseFloat(day.hours);
                }
            });

            calendarDaysNode.textContent = String(calendarDays);
            workingDaysNode.textContent = String(workingDays);
            workingHoursNode.textContent = String(
                Math.round(workingHours * 100) / 100
            );

            selectedPeriodNode.textContent =
                'Выбран период: ' +
                formatDate(selectedStart) +
                ' — ' +
                formatDate(selectedEnd);
        }

        function updateVisualSelection() {
            resetVisualSelection();

            if (!selectedStart) {
                return;
            }

            var rangeEnd = selectedEnd || selectedStart;
            var range = getDateRange(selectedStart, rangeEnd);

            range.forEach(function (date) {
                var node = root.querySelector(
                    '[data-day][data-date="' + date + '"]'
                );

                if (!node) {
                    return;
                }

                if (date === selectedStart) {
                    node.classList.add('is-start');
                } else if (date === selectedEnd) {
                    node.classList.add('is-end');
                } else {
                    node.classList.add('is-in-range');
                }
            });
        }

        function setSelection(start, end) {
            if (!isAllowedDate(start)) {
                return;
            }

            if (end && !isAllowedDate(end)) {
                return;
            }

            if (end && end < start) {
                var temporary = start;
                start = end;
                end = temporary;
            }

            selectedStart = start;
            selectedEnd = end || '';

            fromInput.value = selectedStart;
            toInput.value = selectedEnd;

            updateVisualSelection();
            updateStatistics();
        }

        root.querySelectorAll('[data-day]').forEach(function (node) {
            node.addEventListener('click', function () {
                var date = node.dataset.date;

                if (!isAllowedDate(date)) {
                    return;
                }

                if (!selectedStart || selectedEnd) {
                    setSelection(date, '');
                    return;
                }

                setSelection(selectedStart, date);
            });
        });

        fromInput.addEventListener('change', function () {
            var value = fromInput.value;

            if (!value) {
                selectedStart = '';
                selectedEnd = '';
                toInput.value = '';

                updateVisualSelection();
                updateStatistics();

                return;
            }

            setSelection(value, selectedEnd || '');
        });

        toInput.addEventListener('change', function () {
            var value = toInput.value;

            if (!value) {
                selectedEnd = '';

                updateVisualSelection();
                updateStatistics();

                return;
            }

            if (!selectedStart) {
                selectedStart = value;
                fromInput.value = value;
                selectedEnd = '';
            } else {
                setSelection(selectedStart, value);
            }
        });

        clearButton.addEventListener('click', function () {
            selectedStart = '';
            selectedEnd = '';

            fromInput.value = '';
            toInput.value = '';

            updateVisualSelection();
            updateStatistics();
        });
    }

    document.querySelectorAll('[data-calendar]').forEach(function (root) {
        initCalendar(root);
    });
})();

/*
BX.ready(function (e) {
    $('.pc-day--workday').on('click',function (e) {
        e.preventDefault();
        console.log($(this).data('date'));
        console.log(e.target);
    })
})
*/

BX.ready(function () {
    // Создаём один попап и используем его при каждом клике.
    const popup = new BX.PopupWindow('workday-popup', null, {
        content: BX('workday-form'),
        titleBar: 'Форма для выбранного дня',
        closeIcon: true,
        closeByEsc: true,
        overlay: true,
        autoHide: false,
        width: 450
    });

    // Делегирование работает и для элементов,
    // добавленных на страницу после загрузки.
    $(document).on('click', '.pc-day--workday', function (e) {
        e.preventDefault();

        const date = $(this).data('date');

        BX('workday-date').value = date == null ? '' : String(date);

        popup.show();
    });
});