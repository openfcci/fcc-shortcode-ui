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
	global $post;
	$attr = shortcode_atts( array(
		'key' => '',
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

	$ad_unit = '/7021/fcc.forum';
	$keywords = array();
	$keywords[] = 'blog_' . get_current_blog_id();

	foreach ( get_the_category( $post->ID ) as $category ) {
		$keywords[] = str_replace( "'", '', str_replace( '&', 'and', htmlspecialchars_decode( $category->name ) ) );
	}

	foreach ( get_the_tags( $post->ID ) as $tag ) {
		$keywords[] = str_replace( "'", '', str_replace( '&', 'and', htmlspecialchars_decode( $tag->name ) ) );
	}

	$keywords = implode( ',', $keywords );

	$params = array(
	  'sz' => '640x480',
	  'cust_params' => "kw={$keywords}",
	  'ad_rule' => 0,
	  'impl' => 's',
	  'gdfp_req' => 1,
	  'env' => 'vp',
	  'output' => 'xml_vast2',
	  'unviewed_position_start' => 1,
	  'url' => '[REFERRER_URL]',
	  'correlator' => '[TIMESTAMP]',
	);

	$query = http_build_query( $params );
	$adtag = "http://pubads.g.doubleclick.net/gampad/ads?iu={$ad_unit}&{$query}";

	//$adtag = "https://pubads.g.doubleclick.net/gampad/ads?iu=/7021/fcc.forum&&sz=640x480&cust_params=&impl=s&gdfp_req=1&env=vp&output=xml_vast2&unviewed_position_start=1&url=[REFERRER_URL]&correlator=[TIMESTAMP]&ad_rule=0";

	if ( is_admin() ) { echo '<div align="center" class="fccjwplayer" style="max-width: 650px; margin: auto;">'; }
	echo '
   <script src="https://content.jwplatform.com/libraries/'.$player_key.'.js"></script>
   <div id="'.$video_key.'">Loading the player...</div>
   <script type="text/javascript">
   var playerInstance = jwplayer("'.$video_key.'");
   playerInstance.setup({
   	file: "https://content.jwplatform.com/videos/'.$video_key.'.mp4",
   	image: "https://assets-jpcust.jwpsrv.com/thumbs/'.$video_key.'.jpg",
   	mediaid: "'.$video_key.'",
   	autostart: '.$autostart.',
		advertising: {
			client: "googima",
			schedule: {
				adbreak1: {
					offset: "pre",
					tag: "'.$adtag.'",
				}
			}
		},
   });
	 console.log("%c JW Player Mode: " + playerInstance.getRenderingMode() + " ", "background: #3D4962; color: white; display: block;");
	 console.log("%c JW Player Advertising Keywords: '.$keywords.'", "background: #3D4962; color: white; display: block;");
	 playerInstance.on("adError",function(event){
	 	console.log("%c JW Ad Error: JW Player adError event was fired. ", "background: #FF0046; color: white; display: block;");
	 	console.log("%c JW " + event.message + " ", "background: #FF0046; color: white; display: block;");
	 	console.log("%c JW Ad Tag: " + event.tag + " ", "background: #FF0046; color: white; display: block;");
	 });
	 playerInstance.on("adStarted",function(event){
	 	console.log("%c JW Event: adStarted (VPAID-only) ", "background: #1F8DF7; color: white; display: block;");
	 	console.log("%c JW adStarted Volume: " + playerInstance.getVolume() + " ", "background: #1F8DF7; color: white; display: block;");
	 	console.log("%c JW creativetype:" + event.creativetype + " ", "background: #1F8DF7; color: white; display: block;");
	 	console.log("%c JW adTag:" + event.tag  + " ", "background: #1F8DF7; color: white; display: block;");
	 });

   </script>
   ';
	if ( is_admin() ) { echo '</div>'; }

	return ob_get_clean(); // End Output Buffer
}
