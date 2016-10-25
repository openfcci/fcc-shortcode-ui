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
function fcc_shortcode_ui_register_ustream() {
	# Shortcode for uStream
	add_shortcode( 'fcc_ustream', 'fcc_shortcode_ustream' );
}
add_action( 'init', 'fcc_shortcode_ui_register_ustream' );

/*------------------------------------------------------------------------------
# 2. Register the Shortcode UI setup for the shortcodes.
------------------------------------------------------------------------------*/

/**
 * Ustream Embed - UI Functions
 *
 * @author Ryan Veitch <ryan.veitch@forumcomm.com>
 * @since 1.16.08.26
 * @version 1.16.08.26
 */
function fcc_shortcode_ui_ustream() {

	$ustream_logo = '<img src="' . SCUI__PLUGIN_DIR . 'img/ustream.svg' . '">' ;
	$ustream_help = '<img src="' . SCUI__PLUGIN_DIR . 'img/us_help.png' . '">' ;

	/**
	 * Register UI for Ustream shortcode
	 *
	 * @param string $shortcode_tag
	 * @param array $ui_args
	 * @since 1.16.08.26
	 * @version 1.16.08.26
	 */
	shortcode_ui_register_for_shortcode( 'fcc_ustream',
		array(
			'label' => esc_html__( 'Ustream Embed', 'shortcode-ui' ),
			'listItemImage' => $ustream_logo,
			'attrs' => array(
				array(
					'label'  => esc_html__( 'Ustream Content ID', 'shortcode-ui' ),
					'description'  => '<br>Enter the content ID for the stream embed.<br>
					<ul>
					<li><strong>FCC Live: </strong><code>22487066</code></li>
					<li><strong>WDAY: </strong><code>13746121</code></li>
					<li><strong>WDAZ X&apos;Tra: </strong><code>19970897</code></li>
					<li><strong>WDAZ: </strong><code>14486691</code></li>
					</ul>',
					'attr'   => 'contentid',
					'type'   => 'text',
					'encode' => true,
					'value' => '13746121',
					'meta'   => array(
						'placeholder' => esc_html__( 'Example: 13746121', 'shortcode-ui' ),
						'data-test'   => 1,
					),
				),
			),
		)
	);
}
add_action( 'register_shortcode_ui', 'fcc_shortcode_ui_ustream' );

/*------------------------------------------------------------------------------
# 3. Define the callback for the advanced shortcode.
------------------------------------------------------------------------------*/

/**
 * Ustream Embed (Callback Function)
 *
 * @author Ryan Veitch <ryan.veitch@forumcomm.com>
 * @since 1.16.08.29
 * @version 1.16.08.29
 */
function fcc_shortcode_ustream( $attr, $content = '', $shortcode_tag ) {

	 	$attr = shortcode_atts( array(
	 		'contentid' => '',
			'toggle' => true,
		), $attr, $shortcode_tag );

		ob_start(); // Start Output Buffer

		$content_id = wp_kses_post( $attr['contentid'] );
		$toggle = $attr['toggle']; // TODO add enable/disable toggle functionality?

		if ( is_admin() ) { echo '<div align="center" class="fccjwplayer" style="margin: auto;">'; }
		echo '
		 <iframe src="http://www.ustream.tv/combined-embed/' . $content_id . '?social=0&amp;videos=0&amp;html5ui&amp;autoplay=true&amp;showtitle=false" style="border: 0 none transparent;" webkitallowfullscreen allowfullscreen frameborder="no" width="640" height="360></iframe>
		 ';
		if ( is_admin() ) { echo '</div>'; }

		return ob_get_clean(); // End Output Buffer
}
