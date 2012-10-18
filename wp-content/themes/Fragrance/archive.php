<?php
/**
* Archive template used by Fragrance.
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
<?php if (have_posts()) : ?>
			<h1 class="pagetitle">
<?php if ( is_day() ) : ?>
				<?php printf(  'Daily Archives: <span>%s</span>', get_the_date() ); ?>
<?php elseif ( is_month() ) : ?>
				<?php printf( 'Monthly Archives: <span>%s</span>', get_the_date('F Y') ); ?>
<?php elseif ( is_year() ) : ?>
				<?php printf(  'Yearly Archives: <span>%s</span>', get_the_date('Y') ); ?>
 <?php elseif ( is_author() ) : ?>
				<?php printf( 'Author Archive'); ?>
 <?php elseif ( is_category() ) : ?>
				<?php printf( 'Category Archive: <span>%s</span>',single_cat_title("", false)); ?>
 <?php elseif ( is_tag() ) : ?>
				<?php printf( 'Tag Archive: <span>%s</span>',single_tag_title("", false)); ?>
<?php else : ?>
				Blog Archives
<?php endif; ?>
			</h1>

  <?php while (have_posts()) : the_post(); ?>
  <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="articletitle">
      <h2 class="title">
        <?php the_title(); ?>
      </h2>
      <div class="postinfo"> BY
        <?php the_author(); ?>
        |
        <?php the_time('F jS, Y') ?>
      </div>
    </div>
    <div class="comentnum">
<?php comments_popup_link('0 comment','1 comment','% comments', '','' ); ?>
    </div>
    <div class="cb"><hr />
</div>
    
    
    <div class="entry">
      <?php the_content('Read the rest of this entry &raquo;'); ?>
      <?php wp_link_pages(array('before' => '<div class="page-link"><strong>Pages:</strong> ', 'after' => '</div>', 'next_or_number' => 'number')); ?>   </div>
    <div class="tags">
        <p><?php edit_post_link('Edit ', '', ''); ?></p><p>
  
      Category:
        <?php the_category(', '); ?></p>

<p>
        <?php the_tags('TAG:', ' , ' , ''); ?>
</p>
    </div>
    <div class="navigation cb">
      <div class="alignleft">
        <?php next_posts_link('&laquo; Older Entries') ?>
      </div>
      <div class="alignright">
        <?php previous_posts_link('Newer Entries &raquo;') ?>
      </div>
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
