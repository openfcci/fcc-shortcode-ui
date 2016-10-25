jQuery(document).ready(function($) {
	$(document.body).on( 'click', '.fcc-shortcake-add-post-element', function( event ) {
		var elem = $( event.currentTarget ),
			editor = elem.data('editor'),
			options = {
				frame: 'post',
				state: 'shortcode-ui',
				title: shortcodeUIData.strings.media_frame_title
			};

		event.preventDefault();

		// Remove focus from the `.shortcake-add-post-element` button.
		// Prevents Opera from showing the outline of the button above the modal.
		//
		// See: https://core.trac.wordpress.org/ticket/22445
		elem.blur();

		wp.media.editor.open( editor, options );
	} );

});
