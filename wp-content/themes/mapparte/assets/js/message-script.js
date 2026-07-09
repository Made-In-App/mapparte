jQuery(document).ready(function ($) {

    // Message popup actions
    $('#message-popup .btn-secondary').click(function () {
        $('#message-popup').modal('toggle');
    });

    $('#message-popup .btn-outline-primary').click(function (e) {
        e.preventDefault();

        if ( $('#message-popup .message-form #message').val() ) {
            $.ajax({
                url: messages.ajaxUrl,
                data: {
                    action: "send_message",
                    spaceId: messages.spaceId,
                    userId: messages.currentUserId,
                    nonce: messages.nonce,
                    message: $('#message-popup .message-form #message').val()
                },
                method: 'POST',
                success: function () {
                    alert('Messaggio inviato con successo!');
                    $('#message-popup').modal('toggle');
                },
                error: function (xhr, status, error) {
                    alert('Si è verificato un errore. Riprova.');
                    $('#message-popup').modal('toggle');
                    $('#open-modal-login').click();
                },
            });
        }

    });

});

