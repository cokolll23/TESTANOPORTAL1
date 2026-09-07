BX.ready(function (e) {




    var ajaxUrl = '/local/ajax/ajax_add_event.php';

    $('body').on('click', ' a', function (e) {// data-id="chat21130"
        var btnId = $(this).attr('href').split('//')[1];
        // alert(btnId);
        if (btnId == 'chat13') {
            e.preventDefault();

            $('[data-id="' + btnId + '"]').click();
        }
    });

    $('body').on('click', '.close-popup', function (e) {
        $('#popupOverlay').toggleClass('active').remove();
    });

    // подробнее о мероприятии
    $('body').on('click', '.event-more', function (e) {
        e.preventDefault();
        var eventId = $('.popup').data('eventid');
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {eventId: eventId, action: 'getLinkMore'},
            dataType: 'json',

            success: function (response) {
                if (response.success == true) {
                    //window.location.href = response.LINK;
                    window.open(response.LINK, '_blank');
                } else {

                    $('.popup .popup-body form').remove();
                    $('#popupOverlay').addClass('active');
                    $('.popup .popup-body').html('<h3 style = "color:green;"> Нет мероприятий по этому событию</h3>');

                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

    // отправка формы
    $('body').on('submit', '.popup-body form', function (e) {
        e.preventDefault();
        var thisForm = $(this);
        var resData = thisForm.serialize();
        BX.showWait(document.getElementById('popup'));

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: resData,
            dataType: 'json',

            success: function (response) {

                if (response.success == 'true') {


                    BX.closeWait(document.getElementById('popup'));
                    alert(response.successText);
                    $('.popup .popup-body form').remove();
                    $('.popup .loader-overlay').remove();
                    $('.popup .popup-body').html(response.successText);
                    setTimeout(removePopup, 5000);

                }
                if (response.success == 'false') {
                    BX.closeWait(document.getElementById('popup'));
                    $('.popup .popup-body form').remove();
                    $('.popup .popup-body').html(response.successText);
                    alert(response.successText);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });

    });

    // вставка формы в попап окно
    $('body').on('click', '#event-sign-link,[data-role = "accept"]', function (e) {
        e.preventDefault();
        var eventId = $('.popup').data('eventid');
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {eventId: eventId, action: 'getWebForm'},
            dataType: 'json',

            success: function (response) {

                if (response.success == true) {
                    $('#popupOverlay').addClass('active');
                    $('.popup .popup-body').html(response.html);
                    $('.popup .popup-capture h2').html(response.NAME);
                } else {
                    if (response.FormLink == null) {
                        $('.popup .popup-body form').remove();
                        $('#popupOverlay').addClass('active');
                        $('.popup .popup-body').html('<h3 style = "color:green;"> Нет мероприятий по этому событию</h3>');
                    }else{
                        window.open(response.FormLink, '_blank');
                    }


                }

            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });


    // создание попап окна пустого
    $('body').on('click', '.calendar-event-line-wrap', function (e) {
        e.preventDefault();
        var _this = $(this);
        console.log(_this);
        //var eventName =
        let eventId = $(_this[0]).data('bx-calendar-entry');


        const formHTML = `
     
    <div class="popup-overlay" id="popupOverlay">
        <div draggable = "true" class="popup" id="popup">
            <div class="popup-header">
                <button class="close-popup" id="closePopupBtn" aria-label="Закрыть">&times;</button>
            </div>
            <div class="popup-capture">
            <h2></h2>
            </div>
            <div class="popup-body"></div>
        </div>
    </div>
`;
        $('#popupOverlay').remove();
        $('body').append(formHTML);
        $('.popup').attr('data-eventId', eventId);

    });

});

function removePopup() {
    $('#popupOverlay').removeClass('active').remove();
}








