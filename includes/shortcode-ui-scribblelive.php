<?php
/*------------------------------------------------------------------------------
# 1. Register the shortcodes.
------------------------------------------------------------------------------*/

/**
 * Register Shortcodes
 *
 * @since 1.16.10.25
 * @version 1.16.10.25
 */
function fcc_shortcode_ui_register_scribblelive() {
	# Shortcode for ScribbleLive
	add_shortcode( 'fcc_scribblelive', 'fcc_shortcode_scribblelive' );
}
add_action( 'init', 'fcc_shortcode_ui_register_scribblelive' );

/*------------------------------------------------------------------------------
# 2. Register the Shortcode UI setup for the shortcodes.
------------------------------------------------------------------------------*/

/**
 * ScribbleLive Embed - UI Functions
 *
 * Register UI for ScribbleLive shortcode
 * @author Ryan Veitch <ryan.veitch@forumcomm.com>
 * @since 1.16.08.26
 * @version 1.16.08.26
 */
function fcc_shortcode_ui_scribble() {

	$scribble_logo = '<img src="' . SCUI__PLUGIN_DIR . 'img/sl_logo_gray.svg' . '">' ;
	$slhelp = '<img src="' . SCUI__PLUGIN_DIR . 'img/sl_help.png' . '">' ;

	shortcode_ui_register_for_shortcode( 'fcc_scribblelive',
		array(
			'label' => esc_html__( 'ScribbleLive Embed', 'shortcode-ui' ),
			'listItemImage' => $scribble_logo,
			'attrs' => array(
				array(
					'label'  => esc_html__( 'ScribbleLive Event ID', 'shortcode-ui' ),
					'description'  => '<br>Copy the event ID from the url in the ScribbleLive dashboard.: <br>' . $slhelp,
					'attr'   => 'eventid',
					'type'   => 'text',
					'encode' => true,
					'meta'   => array(
						'placeholder' => esc_html__( 'Example: 1717426', 'shortcode-ui' ),
						'data-test'   => 1,
					),
				),
			),
		)
	);
}
add_action( 'register_shortcode_ui', 'fcc_shortcode_ui_scribble' );

/*------------------------------------------------------------------------------
# 3. Define the callback for the advanced shortcode.
------------------------------------------------------------------------------*/

/**
 * ScribbleLive Embed (Callback Function)
 *
 * @author Ryan Veitch <ryan.veitch@forumcomm.com>
 * @since 1.16.08.26
 * @version 1.16.08.26
 */
function fcc_shortcode_scribblelive( $attr, $content = '', $shortcode_tag ) {

	 	$attr = shortcode_atts( array(
	 		'eventid'           => '',
		), $attr, $shortcode_tag );

		ob_start(); // Start Output Buffer

		$event_id = wp_kses_post( $attr['eventid'] );//

		echo '
		 <div class="scrbbl-embed" data-src="/event/'.$event_id.'"></div>
		 <script>
		 (function(d, s, id) {
		   var js, ijs = d.getElementsByTagName(s)[0];
		   if (d.getElementById(id)) return;
		   js = d.createElement(s);
		   js.id = id;
		   js.src = "//embed.scribblelive.com/widgets/embed.js";
		   ijs.parentNode.insertBefore(js, ijs);
		 }(document, "script", "scrbbl-js"));
		 </script>
		 ';

		return ob_get_clean(); // End Output Buffer
}
