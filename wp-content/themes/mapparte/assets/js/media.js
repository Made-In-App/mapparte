(function($) {

    $(document).ready( function() {
        var file_frame; // variable for the wp.media file_frame

        // attach a click event (or whatever you want) to some element on your page
        $( '#frontend-button' ).on( 'click', function( event ) {
            event.preventDefault();

            // if the file_frame has already been created, just reuse it
            if ( file_frame ) {
                file_frame.open();
                return;
            }

            file_frame = wp.media.frames.file_frame = wp.media({
                title: $( this ).data( 'uploader_title' ),
                button: {
                    text: $( this ).data( 'uploader_button_text' ),
                },
                multiple: false // set this to true for multiple file selection
            });

            file_frame.on( 'select', function() {
                attachment = file_frame.state().get('selection').first().toJSON();
                $imgContainer = $( '#sortable' );
                $img = $( '.frontend-image' );
                $img.attr('src', attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url);
                $img.attr('id', attachment.id );
                $img.after("<a class=\"remove\" href=\"#\">Elimina</a>");
                $img.removeClass( 'frontend-image' );
                $imgContainer.append('<li class="col-sm-3"><img class="frontend-image" src="" /></li>');
                // Aggiorna subito il campo nascosto per il submit (compatibile con browser che non fireano DOMSubtreeModified)
                var ids = [];
                $( '#sortable li img[id]' ).each(function() { ids.push($(this).attr('id')); });
                $( '#gallery_imgs' ).val( ids.join(',') );
            });

            file_frame.open();
        });
    });

})(jQuery);