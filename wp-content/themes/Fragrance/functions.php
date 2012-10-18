<?php
/**
* Theme functions used by Fragrance.
*
* Authors: wpart
* Copyright: 2012
* {@link http://wpart.org/}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package Fragrance.
* @since 1.0
*/

 ?>

<?php

function Fragrance_widgets_init() {   
	register_sidebar(array(
	    'id' => 'sidebar',
		'name' =>  'sidebar',
		'before_widget' => '<li id="%1$s" class="side widget %2$s">', 
		'after_widget' => '</li>',
		'before_title' => '<h3 class="title3">', 
		'after_title' => '</h3>' 
	));
 
}

add_action( 'widgets_init', 'Fragrance_widgets_init');

if ( ! isset( $content_width ) )
	$content_width = 665;

function Fragrance_setup() {


	add_custom_background();

	add_editor_style();


	add_theme_support( 'post-thumbnails' ); 
	
	set_post_thumbnail_size( 300, 90, true );

	add_theme_support( 'automatic-feed-links' );
	
		register_nav_menus( array(
		'primary' =>'Primary Menu',
	) );		
			
			
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );
			


	
	
	
	defined( 'HEADER_IMAGE' );
	define( 'HEADER_IMAGE', '%s/images/header.jpg' );

	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'Fragrance_header_image_width', 960 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'Fragrance_header_image_height', 250 ) );

	
	define( 'NO_HEADER_TEXT', true );


	add_custom_image_header( 'Fragrance_header_style', 'Fragrance_admin_header_style' );
	
	function Fragrance_header_style() {
				?>
<style type="text/css">
#headimg {
}
</style>
<?php
			}
	
	
function Fragrance_admin_header_style() { ?>
<style type="text/css">
#headimg {
 height: <?php echo HEADER_IMAGE_HEIGHT;
?>px;
 width: <?php echo HEADER_IMAGE_WIDTH;
?>px;
}
#headimg h1, #headimg #desc {
	display: none;
}
</style>
<?php }
			
			
			
			
}
add_action( 'after_setup_theme', 'Fragrance_setup' );


function Fragrance_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'Fragrance_excerpt_length', 999 );


function Fragrance_excerpt_more($more) {
       global $post;
	return '<br /><a class="more" href="'. get_permalink($post->ID) . '">MORE</a>';
}
add_filter('excerpt_more', 'Fragrance_excerpt_more');


function Fragrance_scripts(){
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
  }
  add_action( 'wp_enqueue_scripts', 'Fragrance_scripts' );

function Fragrance_filter_wp_title( $old_title, $sep, $sep_location ){
 
// add padding to the sep
$ssep = ' ' . $sep . ' ';
 
// find the type of index page this is
if( is_category() ) $insert = $ssep . 'Category';
elseif( is_tag() ) $insert = $ssep . 'Tag';
elseif( is_author() ) $insert = $ssep . 'Author';
elseif( is_year() || is_month() || is_day() ) $insert = $ssep . 'Archives';
else $insert = NULL;
 
// get the page number we're on (index)
if( get_query_var( 'paged' ) )
$num = $ssep . 'page ' . get_query_var( 'paged' );
 
// get the page number we're on (multipage post)
elseif( get_query_var( 'page' ) )
$num = $ssep . 'page ' . get_query_var( 'page' );
 
// else
else $num = NULL;
 
// concoct and return new title
return  $insert . $old_title . $num  ;
}
add_filter( 'wp_title', 'Fragrance_filter_wp_title', 10, 3 );

?>
