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
function fcc_shortcode_ui_register_jw_player() {
	# Shortcode for JW Player
	add_shortcode( 'fcc_jw_player', 'fcc_shortcode_jw_player' );
}
add_action( 'init', 'fcc_shortcode_ui_register_jw_player' );

/*------------------------------------------------------------------------------
# 2. Register the Shortcode UI setup for the shortcodes.
------------------------------------------------------------------------------*/

/**
 * Shortcode UI setup for JW Player Video Embed
 *
 * @author Ryan Veitch <ryan.veitch@forumcomm.com>
 * @since 1.16.02.23
 * @version 1.16.10.25
 */
function fcc_shortcode_ui_jw_player() {

	$jwlogo = '<img src="' . SCUI__PLUGIN_DIR . 'img/jwplayer-icon.svg' . '">' ;
	$jwhelp = '<img src="' . SCUI__PLUGIN_DIR . 'img/jw-reference-new.png' . '">' ;

	/**
	 * Register UI for your shortcode
	 *
	 * @param string $shortcode_tag
	 * @param array $ui_args
	 */
	shortcode_ui_register_for_shortcode( 'fcc_jw_player',
		array(
			'label' => esc_html__( 'JW Player Video', 'shortcode-ui' ),
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
					'label'       => esc_html__( 'Enable Autostart', 'shortcode-ui' ),
					'description' => 'Video will automatically start playing on page load when enabled.<br><strong>Note: </strong>Do not select when embedding more than one video in a post to prevent multiple videos playing at the same time.',
					'attr'        => 'enable_autostart',
					'type'        => 'checkbox',
				),
			),
		)
	);
}
add_action( 'register_shortcode_ui', 'fcc_shortcode_ui_jw_player' );

/*------------------------------------------------------------------------------
# 3. Define the callback for the advanced shortcode.
------------------------------------------------------------------------------*/

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
			'enable_autostart' => '',
	 	), $attr, $shortcode_tag );

	 	ob_start(); // Start Output Buffer

		$video_key = wp_kses_post( $attr['key'] );
		$player_key = 'XmRneLwC'; // TODO Make key a dynamic field?
		if ( 'true' == $attr['enable_autostart'] ) {
			$autostart = 'true'; // Enable Autostart
		} else {
			$autostart = 'false'; // Disable Autostart
		}

	 	if ( is_admin() ) { echo '<div align="center" class="fccjwplayer" style="max-width: 650px; margin: auto;">'; }
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
		 console.log("%c JW Player Mode: " + playerInstance.getRenderingMode() + " ", "background: #3D4962; color: white; display: block;");
	   </script>
	   ';
	 	if ( is_admin() ) { echo '</div>'; }

	 	return ob_get_clean(); // End Output Buffer
}
