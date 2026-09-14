BX.ready(function (e) {
    const observer = new MutationObserver(function (mutations) {
        const table = document.querySelector('.kt-details__content table.kt-contact-table');
        const table1 = document.querySelector('.kt-structure-user-list__users');

        if (table1) {
            // Элемент появился! Работаем с ним
            //$(table1).find('tr').each(function (i, val) {
               // if ( $(this).find('td').text() === 'День рождения'){
                    console.log(table1);
               // }

           // });
             observer.disconnect(); // отключаем, если не нужно ждать еще
        }
    });

    observer.observe(document.body, {childList: true, subtree: true});
});
