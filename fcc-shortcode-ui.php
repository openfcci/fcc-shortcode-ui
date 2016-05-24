<?php
/**
 * Plugin Name: FCC Shortcode UI
 * Version: 0.15.12.16
 * Description: Adds 'Insert Post Element' visual UI to shortcode embeds
 * Author: FCC Digital / Ryan Veitch
 * Author URI: http://forumcomm.com/
 * Text Domain: shortcode-ui
 * License: GPL v2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

 /* DOCUMENTATION: https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI
  * Available Shortcode UI attribute fields include:
  * Text, checkbox, textarea, radio, select, email, url, number, date, attachment, color, post_select.
  */

/**
 * If Shortcake isn't active, then this demo plugin doesn't work either
 */
add_action( 'init', 'fcc_shortcode_ui_detection' );
function fcc_shortcode_ui_detection() {
	if ( !function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		add_action( 'admin_notices', 'fcc_shortcode_ui_notices' );
	}
}

function fcc_shortcode_ui_notices() {
	if ( current_user_can( 'activate_plugins' ) ) {
		echo '<div class="error message"><p>Shortcode UI plugin must be active for Shortcode UI Example plugin to function.</p></div>';
	}
}

/**
 * Register the two shortcodes independently of their UI.
 * Shortcodes should always be registered, but shortcode UI should only
 * be registered when Shortcake is active.
 */
function shortcode_ui_fcc_register_shortcodes() {

	// JW Player Embed
	add_shortcode( 'fcc_jw_player', 'fcc_shortcode_jw_player' );

	// This is a simple example for a pullquote with a citation.
	add_shortcode( 'shortcake_dev', 'shortcode_ui_dev_shortcode' );

}
add_action( 'init', 'shortcode_ui_fcc_register_shortcodes' );


/**
 * An example shortcode with many editable attributes (and more complex UI)
 */
function shortcode_ui_dev_advanced_example() {

	/**
	 * Register UI for your shortcode
	 *
	 * @param string $shortcode_tag
	 * @param array $ui_args
	 */
	shortcode_ui_register_for_shortcode( 'shortcake_dev',
		array(
			/*
			 * How the shortcode should be labeled in the UI. Required argument.
			 */
			'label' => esc_html__( 'Shortcake Dev', 'shortcode-ui' ),
			/*
			 * Include an icon with your shortcode. Optional.
			 * Use a dashicon, or full URL to image.
			 */
			'listItemImage' => 'dashicons-editor-quote',
			/*
			 * Limit this shortcode UI to specific posts. Optional.
			 */
			'post_type' => array( 'post' ),
			/*
			 * Register UI for the "inner content" of the shortcode. Optional.
			 * If no UI is registered for the inner content, then any inner content
			 * data present will be backed up during editing.
			 */
			'inner_content' => array(
				'label'        => esc_html__( 'Quote', 'shortcode-ui' ),
				'description'  => esc_html__( 'Include a statement from someone famous.', 'shortcode-ui' ),
			),
			/*
			 * Register UI for attributes of the shortcode. Optional.
			 *
			 * If no UI is registered for an attribute, then the attribute will
			 * not be editable through Shortcake's UI. However, the value of any
			 * unregistered attributes will be preserved when editing.
			 *
			 * Each array must include 'attr', 'type', and 'label'.
			 * 'attr' should be the name of the attribute.
			 * 'type' options include: text, checkbox, textarea, radio, select, email,
			 *     url, number, and date, post_select, attachment, color.
			 * Use 'meta' to add arbitrary attributes to the HTML of the field.
			 * Use 'encode' to encode attribute data. Requires customization to callback to decode.
			 * Depending on 'type', additional arguments may be available.
			 */
			'attrs' => array(
				array(
					'label'       => esc_html__( 'Attachment', 'shortcode-ui' ),
					'attr'        => 'attachment',
					'type'        => 'attachment',
					/*
					 * These arguments are passed to the instantiation of the media library:
					 * 'libraryType' - Type of media to make available.
					 * 'addButton' - Text for the button to open media library.
					 * 'frameTitle' - Title for the modal UI once the library is open.
					 */
					'libraryType' => array( 'image' ),
					'addButton'   => esc_html__( 'Select Image', 'shortcode-ui' ),
					'frameTitle'  => esc_html__( 'Select Image', 'shortcode-ui ' ),
				),
				array(
					'label'  => esc_html__( 'Citation Source', 'shortcode-ui' ),
					'attr'   => 'source',
					'type'   => 'text',
					'encode' => true,
					'meta'   => array(
						'placeholder' => esc_html__( 'Test placeholder', 'shortcode-ui' ),
						'data-test'   => 1,
					),
				),
				array(
					'label' => esc_html__( 'Select Page', 'shortcode-ui' ),
					'attr' => 'page',
					'type' => 'post_select',
					'query' => array( 'post_type' => 'page' ),
					'multiple' => true,
				),
			),
		)
	);
}
//add_action( 'register_shortcode_ui', 'shortcode_ui_dev_advanced_example' );



/**
 * JW Player Media Embed
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
			/*
			 * How the shortcode should be labeled in the UI. Required argument.
			 */
			'label' => esc_html__( 'JW Player Media Embed', 'shortcode-ui' ),
			/*
			 * Include an icon with your shortcode. Optional.
			 * Use a dashicon, or full URL to image.
			 */
			'listItemImage' => $jwlogo,

			/*
			 * Register UI for attributes of the shortcode. Optional.
			 *
			 * If no UI is registered for an attribute, then the attribute will
			 * not be editable through Shortcake's UI. However, the value of any
			 * unregistered attributes will be preserved when editing.
			 *
			 * Each array must include 'attr', 'type', and 'label'.
			 * 'attr' should be the name of the attribute.
			 * 'type' options include: text, checkbox, textarea, radio, select, email,
			 *     url, number, and date, post_select, attachment, color.
			 * Use 'meta' to add arbitrary attributes to the HTML of the field.
			 * Use 'encode' to encode attribute data. Requires customization to callback to decode.
			 * Depending on 'type', additional arguments may be available.
			 */
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
          'label'       => esc_html__( 'Autostart', 'shortcode-ui' ),
          'attr'        => 'autostart',
          'type'        => 'checkbox',
        ),
			),
		)
	);
}
add_action( 'register_shortcode_ui', 'fcc_shortcode_ui_jw_player' );


/**
 * Render the shortcode based on supplied attributes
 */
function shortcode_ui_dev_shortcode( $attr, $content = '', $shortcode_tag ) {

	$attr = shortcode_atts( array(
		'source'     => '',
		'attachment' => 0,
		'source'     => null,
	), $attr, $shortcode_tag );

	ob_start();

	?>

	<section class="pullquote" style="padding: 20px; background: rgba(0,0,0,0.1);">
		<p style="margin:0; padding: 0;">
		<b>Content:</b> <?php echo wpautop( wp_kses_post( $content ) ); ?></br>
		<b>Source:</b> <?php echo wp_kses_post( $attr[ 'source' ] ); ?></br>
		<b>Image:</b> <?php echo wp_kses_post( wp_get_attachment_image( $attr[ 'attachment' ], array( 50, 50 ) ) ); ?></br>
		</p>
	</section>

	<?php

	return ob_get_clean();

}

/**
 * Render the shortcode based on supplied attributes
 */
function fcc_shortcode_jw_player( $attr, $content = '', $shortcode_tag ) {

	$attr = shortcode_atts( array(
		'key'     => '',
		'attachment' => 0,
		'key'     => null,
    'autostart' => 0
	), $attr, $shortcode_tag );

	ob_start();

	if ( is_admin() ) { echo '<div align="center" class="fccjwplayer" style="max-width: 650px;">'; }

	?>

    <script type="text/javascript" src="//content.jwplatform.com/libraries/XmRneLwC.js"></script>

    <div id="<?php echo wp_kses_post( $attr[ 'key' ] ); ?>">Loading the player...</div>

    <script type="text/javascript">
      var playerInstance = jwplayer("<?php echo wp_kses_post( $attr[ 'key' ] ); ?>");
      playerInstance.setup({
      	file: "//content.jwplatform.com/videos/<?php echo wp_kses_post( $attr[ 'key' ] ); ?>.mp4",
      	image: "http://assets-jpcust.jwpsrv.com/thumbs/<?php echo wp_kses_post( $attr[ 'key' ] ); ?>.jpg",
        <?php
          //If Autostart is checked, Autostart the video
          if($attr[ 'autostart' ]){
            echo "autostart: true";
          }else{
            echo "autostart: false";
          }
        ?>
      });
    </script>

  <?php

	if ( is_admin() ) { echo '</div>'; }

	return ob_get_clean();

} // End function fcc_shortcode_jw_player

/* SCRIBBLE LIVE FORMAT

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
//Register Settings
