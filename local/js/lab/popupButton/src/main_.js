BX.ready(function (e) {

    //calendar-event-animate-counter-highlight
    $('body').on('click', '[data-role = "accept"]', function (e) {
        e.preventDefault();
        $('#myForm').removeClass('hidden');
    });
    $('body').on('click', '.popup-window-close-icon', function (e) {
        alert('closeFormBtn');
        $('#myForm').remove();
    });
    $('body').on('click', '#closeFormBtn', function (e) {
        $('#myForm').remove();
    });


    // jQuery вариант для формы  #closeFormBtn


    $('body').on('submit', '#myForm', function (e) {
        e.preventDefault();
        var eventId =

            $.ajax({
                url: '/ajax/ajax_add_event.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',

                success: function (response) {



                    if (response.success==true) {

                        $('#myForm').remove();

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
        if ($(_this[0]).hasClass('calendar-event-animate-counter-highlight')) {
            let eventId = $(_this[0]).data('bx-calendar-entry');
            let userId = window.location.href;
            console.log(eventId);

            // Создаем HTML форму как строку
            const formHTML = `
    <form class="popup-window calendar-simple-view-popup hidden" id="myForm" action="/submit" method="POST">
        <h3>Написать дополнение</h3>
        <div class="close-icon" id="closeFormBtn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
  <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
</svg>
            </div>
       <input name="userId" type="text" value="${userId}">
       <input name="eventId" type="text" value="${eventId}">
           <textarea id="message" name="message" rows="4"></textarea>
        
        <button id="sendBtn" class="ui-btn ui-btn-primary" type="submit">Отправить</button>
    </form>
`;
            $('#myForm').remove();
            $('body').append(formHTML);
        } else {
            $('#myForm').remove();
        }

    });

});

