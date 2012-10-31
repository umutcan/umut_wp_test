<?php /*
**************************************************************************

Plugin Name:        My Videotag
Plugin URI:			http://toxigeek.com/plugin-my-videotag
Description:		This Plugin will add a Tag called "video", which automatically identifies videos from sites like: youtube, googlevideo, dailymotion, metacafe, myspace, yahoo, megavideo, vimeo, tu.tv, etc... and add them to your post or comments Wordpress.
Version:			1.4.7
Author:				giObanii fiOri
Author URI:			http://toxigeek.com
@copyright			Copyright (c) 2011, giObanii fiOri

*************************************************************************

Copyright (C) 2011 @giObanii

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************/

function mvt_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}

//on activation, your My VideoTag options will be populated. Here a single option is used which is actually an array of multiple options
function activate_mvt() {
	$mvt_opts1 = get_option('mvt_options');
	$mvt_opts2 =array('width' => '640',
	                   'height' => '390',
					   'style' => 'border:1px solid #CCCCCC;margin: 0 auto 10px;padding: 5px;',
					   'comments' => '1');
	if ($mvt_opts1) {
	    $mvt = $mvt_opts1 + $mvt_opts2;
		update_option('mvt_options',$mvt);
	}
	else {
		$mvt_opts1 = array();	
		$mvt = $mvt_opts1 + $mvt_opts2;
		add_option('mvt_options',$mvt);		
	}
}


register_activation_hook( __FILE__, 'activate_mvt' );
global $mvt;
$mvt = get_option('mvt_options');
define('MVT_SLUG', 'my-videotag');
define('MVT_VER','1.4.7',false);
define('MVT_URL_FAVICON', 'http://www.google.com/s2/favicons?domain=');
define('MVT_URLPATH', trailingslashit( WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) ) );
include_once (dirname (__FILE__) . '/tinymce/tinymce.php');
require_once(dirname (__FILE__) . '/admin/mvt-settings.php');
require_once(dirname (__FILE__) . '/lib/mvt-codex.php');

//External CSS in the header
function mvt_scripts_styles() {
global $mvt;
	wp_enqueue_style( 'mvt_css_file', mvt_url( 'css/mvt-styles.css' ),
   false, MVT_VER, 'all'); 
}
add_action( 'init', 'mvt_scripts_styles' );

function mvt_head_scripts() {
	global $mvt;
	echo '<style type="text/css">'."\n";	
	echo '.myvideotag{'."\n";
	echo ''.$mvt['style'].''."\n";
	echo '}'."\n";
    echo '</style>'."\n";
}
add_action('wp_head', 'mvt_head_scripts');

function mvt_plugin_action_links($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
        $settings_link = '<a href="admin.php?page=my-videotag-settings">'.__('Settings', 'my-videotag').'</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'mvt_plugin_action_links', 10, 2);

// function for adding settings page to wp-admin
function mvt_settings() {
    // Add a new submenu under Options:
    if (function_exists('add_menu_page'))
    {
    add_menu_page('My Videotag Settings', 'My Videotag', 'edit_pages', 'my-videotag-settings', 'mvt_settings_page', mvt_url('images/video_small.png'));
   }
}

function get_version_mvt(){
	$plugin_dir = basename(dirname(__FILE__));
	$url = '/wp-content/plugins/'.$plugin_dir.'/my-videotag.php';
	$plugin_data = implode('', file(ABSPATH.$url));
	if (preg_match("|Version:(.*)|i", $plugin_data, $version)) {
		$version = $version[1];
	}
	return $version;
}

// Load up the localization file if we're using WordPress in a different language
// Place it in this plugin's "languages" folder and name it "my-videotag-[value in wp-config].mo"
load_plugin_textdomain( 'my-videotag', FALSE, '/my-videotag/languages' );

// Shortcode *******************
function myvidetaghortcode($atts, $content=null, $code="") {
global $mvt;
extract(shortcode_atts(array(
   "w" => $mvt['width'],
   "h" => $mvt['height']
), $atts));

if ( NULL === $content ) return '';
   return '' .get_video($content,$w,$h). '';
}
     
add_shortcode('video' , 'myvidetaghortcode' );

if (isset($mvt['comments'])) {
function init_common_shortcodes() {
  add_shortcode('video' , 'myvidetaghortcode');
}

function video_comment_shortcodes() {
  remove_all_shortcodes();
  init_common_shortcodes();
  add_filter('comment_text', 'do_shortcode');
}

init_common_shortcodes();
add_filter('comments_template', 'video_comment_shortcodes');
}

// Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'mvt_settings');
  add_action( 'admin_init', 'register_mvt_settings' ); 
} 
function register_mvt_settings() { // whitelist options
  register_setting( 'mvt-group', 'mvt_options' );
}


// Add javascript if quicktags are shown on page (will finally add our button and the code to execute when we click on it)
function my_videotag_addquicktags() {
	global $pagenow;
	if (is_admin() && ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'page.php' || $pagenow == 'page-new.php') ) {
		$js = MVT_URLPATH.'/js/my-videotag-quicktag.js';
		wp_enqueue_script("my_videotag_quickcode", $js, array('quicktags') );
	}
}
add_action( 'admin_print_scripts', 'my_videotag_addquicktags' );

?>