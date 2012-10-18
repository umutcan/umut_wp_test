<?php
/**
* Single template used by Fragrance.
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

get_header(); ?>
<div class="SinglePage">
<div id="left">
<div class="sidenav fl"><a class="top" href="#nav" title="up"></a><a class="comment" href="#reply-title" title="leave a reply"></a><a class="bottom" href="#footer" title="down"></a></div>
<div class="content">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="articletitle">
      <h1 class="title">
        <?php the_title(); ?>
      </h1>
      <div class="postinfo"> BY
        <?php the_author(); ?>
        |
        <?php the_time('F jS, Y') ?>
      </div>
    </div>
    <div class="comentnum">
<?php comments_popup_link('0 comment','1 comment','% comments', '','Comments are closed.' ); ?>
    </div>
    <div class="cb"><hr />
</div>
    
    
        <div class="entry">
  
      
<?php if (wp_attachment_is_image() ) :
	      	?><div class="entry-attachment">
          

            
            <?php
$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
	foreach ( $attachments as $k => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}
	$k++;
	// If there is more than 1 image attachment in a gallery
	if ( count( $attachments ) > 1 ) {
		if ( isset( $attachments[ $k ] ) )
			// get the URL of the next image attachment
			$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
		else
			// or get the URL of the first image attachment
			$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	} else {
		// or, if there's only 1 image attachment, get the URL of the image
		$next_attachment_url = wp_get_attachment_url();
	}
?>
						<p class="attachment">  <?php previous_image_link( array( 37, 37 )); ?>                        <a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
							$attachment_width  = apply_filters( 'Fragrance_attachment_size', 900 );
							$attachment_height = apply_filters( 'Fragrance_attachment_height', 900 );
							echo wp_get_attachment_image( $post->ID, array( $attachment_width, $attachment_height ) ); // filterable image width with, essentially, no limit for image height.
						?></a><?php next_image_link( array( 37, 37 )); ?></p>
         <div class="cb"></div>
         </div></div>

						<!-- #nav-below -->
<?php else : ?>
      <?php 
	  
the_content('<span class="more">Read the rest of this entry &gt;&gt;</span>');
    wp_link_pages(array('before' => '<div class="page-link"><strong>Pages:</strong>  ', 'after' => '</div>', 'next_or_number' => 'number')); ?>


         <div class="cb"></div>

						</div>
<?php endif; ?>
      
      
      
    <div class="tags">
        <p><?php edit_post_link('Edit ', '', ''); ?></p><p>
  
      Category:
        <?php the_category(', '); ?></p>

<p>
        <?php the_tags('TAG:', ' , ' , ''); ?>
</p>
    </div>
    <div class="navigation">
			<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
			<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
		</div>
        
  </div>
  <?php endwhile;?>
  <?php comments_template(); ?>
  <?php else : ?>
  <?php endif; ?>
</div>
</div>
<!-- /content -->

<?php get_sidebar(); ?>
</div>
</div></div>
<?php get_footer(); ?>
