<?php
/**
 * Plugin Name: FCC Shortcode UI
 * Description: Adds 'Insert Post Element' visual UI to shortcode embeds. Includes embeds for JW Player video, JW Player podcasts, ScribbleLive and Ustream.
 * Plugin URI:  https://github.com/openfcci/fcc-shortcode-ui
 * Author:      Forum Communications Company
 * Author URI:  http://www.forumcomm.com/
 * License:     GPL v2 or later
 * Text Domain: shortcode-ui
 * Version:     2.16.12.08
 */

/*
 DOCUMENTATION: https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI
 Available Shortcode UI attribute fields include:
 Text, checkbox, textarea, radio, select, email, url, number, date, attachment, color, post_select.
*/

# Exit if accessed directly
defined( 'ABSPATH' ) || exit;

# Declare Constants
define( 'SCUI__PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // directory poth
define( 'SCUI__PLUGIN_DIR',  plugin_dir_url( __FILE__ ) ); // full URL

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
# LOAD INCLUDES FILES
--------------------------------------------------------------*/

# JW Player
require_once( plugin_dir_path( __FILE__ ) . '/includes/shortcode-ui-jw-player.php' );
# JW Podcast
require_once( plugin_dir_path( __FILE__ ) . '/includes/shortcode-ui-jw-podcast.php' );
# ScribbleLive
require_once( plugin_dir_path( __FILE__ ) . '/includes/shortcode-ui-scribblelive.php' );
# uStream
require_once( plugin_dir_path( __FILE__ ) . '/includes/shortcode-ui-ustream.php' );

/*--------------------------------------------------------------
# Shortcake Editor Button
--------------------------------------------------------------*/

/**
 * Output an "Add Post Element" button with the media buttons.
 */
function fcc_action_media_buttons( $editor_id ) {
	printf( '<button type="button" class="button fcc-shortcake-add-post-element" data-editor="%s">' .
		'<span class="wp-media-buttons-icon dashicons dashicons-migrate"></span> %s' .
		'</button>',
		esc_attr( $editor_id ),
		esc_html__( 'Insert Post Element', 'shortcode-ui' )
	);
}
add_action( 'media_buttons', 'fcc_action_media_buttons' );

function fcc_shortcake_button_js( $hook ) {
	if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
		wp_register_script( 'fcc_shortcake_button', plugin_dir_url( __FILE__ ) . '/includes/js/fcc_shortcake_button.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'fcc_shortcake_button' );
	} else {
		return;
	}
}
add_action( 'admin_enqueue_scripts', 'fcc_shortcake_button_js' );
