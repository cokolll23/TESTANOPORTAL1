BX.ready(function (e) {

    var ajaxUrl = '/local/ajax/ajax_add_event.php';

    $('body').on('click', '.close-popup', function (e) {
        $('#popupOverlay').toggleClass('active').remove();
    });
    /*$('body').on('click', 'a', function (e) {// data-id="chat21130"
        e.preventDefault();
        var btnId = $(this).attr('href').split('//')[1] ;
        if (btnId) {
            $('[data-id="' + btnId + '"]').click();
        }
});*/

    $('body').on('click', '#event-sign-link', function (e) {
        e.preventDefault();
        var eventId = $('.popup').data('eventid');
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {eventId: eventId, action: 'getWebForm'},
            dataType: 'json',

            success: function (response) {

                if (response.success == true) {

                    //alert(eventId);
                    $('#popupOverlay').addClass('active');
                    $('.popup .popup-body').html(response.html);
                    // $(response.WEB_FORM_ID).attr('action',window.location.href);

                } else {

                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

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
                    window.location.href = response.LINK;
                } else {

                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

    $('body').on('submit', '.popup-body form', function (e) {

        e.preventDefault();
        var thisForm = $(this);
        var resData = thisForm.serialize();

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: resData,
            dataType: 'json',

            success: function (response) {

                if (response.success == true) {
                    $('#popupOverlay').toggleClass('active');//.remove();
                } else {

                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });

    });


    $('body').on('click', '.calendar-event-line-wrap', function (e) {
        e.preventDefault();
        var _this = $(this);

        let eventId = $(_this[0]).data('bx-calendar-entry');


        const formHTML = `
     
    <div class="popup-overlay" id="popupOverlay">
        <div draggable = "true" class="popup">
            <div class="popup-header">
                <button class="close-popup" id="closePopupBtn" aria-label="Закрыть">&times;</button>
            </div>
            <div class="popup-body">
               
            </div>
          
        </div>
    </div>
`;
        $('#popupOverlay').remove();
        $('body').append(formHTML);
        $('.popup').attr('data-eventId', eventId);

    });


});








