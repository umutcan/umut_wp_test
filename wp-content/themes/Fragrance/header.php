<?php
/**
* Header template used by Fragrance.
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
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title>
 <?php wp_title('|',true,'right'); ?>
 <?php bloginfo('name'); ?>
 </title>
 
 <link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="nav">
  <?php wp_nav_menu( array( 'container' => 'none', 'theme_location' => 'primary' ,'show_home'=>'1') ); ?>
</div>
<div class="wrap">
    <div id="header">  
    <?php if (is_home()): ?>
    <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="logo">
    <?php bloginfo('name'); ?>
  </a></h1>
    <?php else: ?>
   <a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="logo">
  <?php bloginfo('name'); ?>
  </a>
    <?php endif;?>
<br />


    <?php bloginfo( 'description' ); ?>
      <div class="cb"></div>
    </div>
        <?php
                        // Check if this is a post or page, if it has a thumbnail, and if it's a big one
                        if ( is_singular() && current_theme_supports( 'post-thumbnails' ) &&
                                has_post_thumbnail( $post->ID ) &&
                                ( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
                                $image[1] >= HEADER_IMAGE_WIDTH ) :
                            // Houston, we have a new header image!
                            echo get_the_post_thumbnail( $post->ID );
                        elseif ( get_header_image() ) : ?>
    <div id="img_bg_bg">
      <div id="img_bg" style="max-width:<?php echo HEADER_IMAGE_WIDTH; ?>px;height:<?php echo HEADER_IMAGE_HEIGHT; ?>px;background-image:url(<?php esc_url ( header_image() ) ?>); background-repeat:no-repeat;">  </div>
          
              </div>
              
                        
                        
      <?php else:?>
      <?php endif; ?>
    
<div class="main">
