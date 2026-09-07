BX.ready(function (e) {

    $('.humanresources-tree__node_subdivisions').each(function() {
        $(this).html(' подр');
    });

    //console.log($('.kt-competencies'));
    /* $('body').on('click', '.kt-competencies .kt-widget-title__text ', function (e) {
         e.preventDefault();
         alert('ok');
         $('.kt-competencies ').find('.kt-widget-title__text').html('Задачи в организации');
     });*/

    /*  <a href="/company/structure.php?set_filter_structure=Y&amp;structure_UF_DEPARTMENT=306"
    data-role="department_name" title="МУФ">МУФ</a>

    #js-structure-list #306
  */
    var fullUrl = $(location).attr('href');

    var blVisStructure = fullUrl.includes('vis_structure.php')
    var blHr = fullUrl.includes('hr')

    if (blHr == true) {// .humanresources-tree__node_children 166 159
        $('.humanresources-tree__node_children div ').each(function (i) {
            var aIdData = $(this).attr('data', 'id');
            console.log(aIdData);
            if (aIdData == 166 || aIdData == 159) {
                $(this).addClass('hidden');
                console.log($(this));
            }
        });
    }
    if (blVisStructure == true) {
        $('#js-structure-list ul li.child ').each(function (i) {
            var aId = $(this).find('a').attr('id')
            if (aId == 306 || aId == 299) {
                $(this).addClass('hidden');
                console.log($(this).find('a').attr('id'));
            }
        });
        //$('#js-structure-list #306')

        $('#bx_visual_structure td ').each(function (i) {

            var idSpan = $(this).find('span.structure-dept-block').attr('id');
            // structure-dept-block structure-dept-second structure-dept-editable id='bx_str_76'
            if (idSpan == 'bx_str_306' || idSpan == 'bx_str_299') {
                $(this).addClass('hidden');
            }
        });
    }


    $('body').on('click', 'ul.menu-items li#bx_left_menu_menu_office', function (e) {
        e.preventDefault();
        $('#top_menu_id_k-team #top_menu_id_k-team_menu_office').click();

    });

    /* $('ul.menu-items li').each(function (e) {
         if ($(this).attr('id') == 'bx_left_menu_2301492049') {
             $(this).addClass( 'menu-ano-life');
         }
     });*/

// кнопка для открытия определенного чата из колонки справа страницы
    $('body').on('click', '#layout-left-column .menu-items-body a', function (e) {// data-id="chat21130"

        var btnId = $(this).attr('href').split('//')[1];
        if (btnId == 'chat21130') {
            e.preventDefault();
            $('[data-id="' + btnId + '"]').click();
        }
    });
});
