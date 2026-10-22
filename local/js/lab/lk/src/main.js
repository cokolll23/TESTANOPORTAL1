/*BX.ready(function (e) {
    const observer = new MutationObserver(function (mutations) {
        const table = document.querySelector('.kt-details__content table.kt-contact-table');
        if (table) {
            // Элемент появился! Работаем с ним
            $(table).find('tr').each(function (i, val) {
               // let pol = $(this).find('td.kt-contact-table__label').html();
                //if (pol === 'Пол') {
                    //let polV = pol.parent().find('.kt-contact-table__value span.kt-contact-text__content').text();
               // }


               // if (polV === 'Ж') {
                   /!* if ($(this).find('td.kt-contact-table__label').html() === 'День рождения') {
                        var t = $(this).find('td.kt-contact-table__label').parent().find('.kt-contact-table__value span.kt-contact-text__content').hide();

                    };*!/
               // }


            });
            // observer.disconnect(); // отключаем, если не нужно ждать еще
        }
    });

    observer.observe(document.body, {childList: true, subtree: true});
});*/


document.addEventListener('DOMContentLoaded', function () {
    const block = document.querySelector('.block');

    if (block) {
        block.style.border = '2px solid green';
    }
});