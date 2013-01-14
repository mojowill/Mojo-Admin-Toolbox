/**
 * Thanks to Thomas Griffin for his super useful example on Github
 *
 * https://github.com/thomasgriffin/New-Media-Image-Uploader
 */
jQuery(document).ready(function($){
    // Prepare the variable that holds our custom media manager.
    var mojo_media_frame;
    var formlabel = 0;
    
    // Bind to our click event in order to open up the new media experience.
    $(document.body).on('click.mojoOpenMediaManager', '.mojo-open-media', function(e){
        // Prevent the default action from occuring.
        e.preventDefault();
        formlabel = jQuery(this).parent();
        // If the frame already exists, re-open it.
        if ( mojo_media_frame ) {
            mojo_media_frame.open();
            return;
        }


        mojo_media_frame = wp.media.frames.mojo_media_frame = wp.media({

            className: 'media-frame mojo-media-frame',
            frame: 'select',
            multiple: false,
            library: {
                type: 'image'
            },
        });


        mojo_media_frame.on('select', function(){
            // Grab our attachment selection and construct a JSON representation of the model.
            var media_attachment = mojo_media_frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom input field via jQuery.
            
            formlabel.find('input[type="text"]').val(media_attachment.url);
            //$('#mojo-new-media-image').val(media_attachment.url);
        });

        // Now that everything has been set, let's open up the frame.
        mojo_media_frame.open();
    });
});