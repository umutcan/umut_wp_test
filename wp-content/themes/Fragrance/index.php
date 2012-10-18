<?php
/**
* Index template used by Fragrance.
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
<div id="lastestpost">
<?php $i=1;
	if (have_posts() ||!isset($_GET['paged'])) :
		while (have_posts()) : the_post();

	 ?>
  <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="top">
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
        <?php the_title(); ?>
      </a></h2>
      <div class="postinfo"><span class="orange"><?php the_time('F jS, Y') ?></span><span class="gray"> BY        <?php the_author_posts_link(); ?></span>
        |
        <span class="orange">
      <?php comments_popup_link('0 Comments &#187;','1 Comments &#187;','% Comments &#187;', '','Comments Close' ); ?></span>
     </div>
    
    <div class="cb"></div>
    <div class="entry">
    
    <?php 
//This must be in one loop

if(has_post_thumbnail()) { ?>
<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" class="block">
<?php 
	the_post_thumbnail();
	?>
    </a>
    <?php
} 
elseif ( ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) ) || in_category( 'gallery' ) ) { ?>
      <a href="<?php echo get_post_format_link( 'gallery' ); ?>" title="View Galleries">
     More Galleries
      </a>
      
      <?php
   } 
else {
?>
<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" class="block">
<?php 	
    echo '<span class="newpost'.$i.'"></span>'; 

	?>
    </a>
    <?php

}
?>
      
      
      
      <?php the_excerpt('Read the rest of this entry &raquo;'); ?>
    </div>
  </div>
  <div class="bottom"> </div>
</div>
    <?php $i++; endwhile; ?>
    
    <?php else : ?>
    <?php endif; ?>

</div>
<div class="navigation cb">
      <div class="alignleft">
        <?php next_posts_link('&laquo; Older Entries') ?>
      </div>
      <div class="alignright">
        <?php previous_posts_link('Newer Entries &raquo;') ?>
      </div>
    </div>
<div class="cb"></div>
</div>
</div>
  <?php get_template_part('bottombar'); ?>

<!-- /content -->

<?php get_footer(); ?>
