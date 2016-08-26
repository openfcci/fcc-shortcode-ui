<?php
/**
 * Plugin Name: FCC Shortcode UI
 * Description: Adds 'Insert Post Element' visual UI to shortcode embeds. Includes: JW Player video embed.
 * Plugin URI:  https://github.com/openfcci/fcc-shortcode-ui
 * Author:      Forum Communications Company
 * Author URI:  http://www.forumcomm.com/
 * License:     GPL v2 or later
 * Text Domain: shortcode-ui
 * Version:     1.16.05.18
 */

/*
 DOCUMENTATION: https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI
 Available Shortcode UI attribute fields include:
 Text, checkbox, textarea, radio, select, email, url, number, date, attachment, color, post_select.
*/

/*--------------------------------------------------------------
# Admin Notices
--------------------------------------------------------------*/

/**
 * If Shortcake isn't active, then this demo plugin doesn't work either
 */
add_action( 'init', 'fcc_shortcode_ui_detection' );
function fcc_shortcode_ui_detection() {
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		add_action( 'admin_notices', 'fcc_shortcode_ui_notices' );
	}
}

function fcc_shortcode_ui_notices() {
	if ( current_user_can( 'activate_plugins' ) ) {
		echo '<div class="error message"><p>Shortcode UI plugin must be active for Shortcode UI Example plugin to function.</p></div>';
	}
}

/*--------------------------------------------------------------
# Register Shortcodes
--------------------------------------------------------------*/

function shortcode_ui_fcc_register_shortcodes() {
	add_shortcode( 'fcc_jw_player', 'fcc_shortcode_jw_player' );
}
add_action( 'init', 'shortcode_ui_fcc_register_shortcodes' );

/*--------------------------------------------------------------
# JW Player Media Embed
--------------------------------------------------------------*/

/**
 * JW Player Video Embed - UI Functions
 *
 * @author Ryan Veitch <ryan.veitch@forumcomm.com>
 * @since 1.16.02.23
 * @version 1.16.05.18
 */
function fcc_shortcode_ui_jw_player() {

	$jwlogo = '<img src="' . plugin_dir_url( __FILE__ ) . 'img/jwplayer-icon.svg' . '">' ;
	$jwhelp = '<img src="' . plugin_dir_url( __FILE__ ) . 'img/jw-reference.png' . '">' ;

	/**
	 * Register UI for your shortcode
	 *
	 * @param string $shortcode_tag
	 * @param array $ui_args
	 */
	shortcode_ui_register_for_shortcode( 'fcc_jw_player',
		array(
			'label' => esc_html__( 'JW Player Media Embed', 'shortcode-ui' ),
			'listItemImage' => $jwlogo,
			'attrs' => array(
				array(
					'label'  => esc_html__( 'JW Player Video Key', 'shortcode-ui' ),
					'description'  => '<br>Copy the key from the Video Properties: <br>' . $jwhelp,
					'attr'   => 'key',
					'type'   => 'text',
					'encode' => true,
					'meta'   => array(
						'placeholder' => esc_html__( 'Example: 1eFPzk9b', 'shortcode-ui' ),
						'data-test'   => 1,
					),
				),
				array(
					'label'       => esc_html__( 'Disable Autostart?', 'shortcode-ui' ),
					'description' => 'Select this on any video after the first when embedding more than one video in a post to prevent multiple videos
					playing at the same time.',
					'attr'        => 'disable_autostart',
					'type'        => 'checkbox',
				),
			),
		)
	);
}
add_action( 'register_shortcode_ui', 'fcc_shortcode_ui_jw_player' );

/**
 * JW Player Video Embed (Callback Function)
 *
 * @author Ryan Veitch <ryan.veitch@forumcomm.com>
 * @since 1.16.02.23
 * @version 1.16.08.26
 */
function fcc_shortcode_jw_player( $attr, $content = '', $shortcode_tag ) {

	 	$attr = shortcode_atts( array(
	 		'key'               => '',
	 		'attachment'        => 0,
	 		'key'               => null,
			'disable_autostart' => '',
	 	), $attr, $shortcode_tag );

	 	ob_start(); // Start Output Buffer

		$video_key = wp_kses_post( $attr['key'] );
		$player_key = 'XmRneLwC'; // TODO Make key a dynamic field?
		if ( 'true' == $attr['disable_autostart'] ) {
			$autostart = 'false'; // Disable Autostart
		} else {
			$autostart = 'true'; // Enable Autostart
		}

	 	if ( is_admin() ) { echo '<div align="center" class="fccjwplayer" style="max-width: 650px;">'; }
		echo '
	   <script src="https://content.jwplatform.com/libraries/'.$player_key.'.js"></script>
	   <div id="'.$video_key.'">Loading the player...</div>
	   <script type="text/javascript">
	   var playerInstance = jwplayer("'.$video_key.'");
	   playerInstance.setup({
	   	file: "https://content.jwplatform.com/videos/'.$video_key.'.mp4",
	   	image: "https://assets-jpcust.jwpsrv.com/thumbs/'.$video_key.'.jpg",
	   	autostart: '.$autostart.',
	   });
		 console.log("JW Player Mode: " + playerInstance.getRenderingMode());
	   </script>
	   ';
	 	if ( is_admin() ) { echo '</div>'; }

	 	return ob_get_clean(); // End Output Buffer
}

/*--------------------------------------------------------------
# SCRIBBLE LIVE
--------------------------------------------------------------*/

/* SCRIBBLE LIVE FORMAT (For Later Use)

<div class="scrbbl-embed" data-src="/event/1717426"></div>

<script>
(function(d, s, id) {
  var js, ijs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s);
  js.id = id;
  js.src = "//embed.scribblelive.com/widgets/embed.js";
  ijs.parentNode.insertBefore(js, ijs);
}(document, 'script', 'scrbbl-js'));
</script>

*/
