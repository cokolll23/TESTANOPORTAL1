BX.ready(function (e) {
    const observer = new MutationObserver(function (mutations) {
        const table = document.querySelector('.kt-details__content table.kt-contact-table');
        if (table) {
            // Элемент появился! Работаем с ним
            $(table).find('tr').each(function (i, val) {


            });
            // observer.disconnect(); // отключаем, если не нужно ждать еще
        }
    });

    observer.observe(document.body, {childList: true, subtree: true});
});
