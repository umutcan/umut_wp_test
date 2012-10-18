<?php
/**
* Comments template used by Fragrance.
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

<?php if ( post_password_required() ) : ?>
				<p class="nopassword">This post is password protected. Enter the password to view any comments.</p>
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>
<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
<h3 class="commenttitle">  
    <?php comments_number('0 comment','1 comment','% comments', '','Comments are closed.' ); ?> on <span>&quot;<?php the_title();?>&quot;</span>

</h3>

<div id="comments">
  <ol class="commentlist">
    <?php wp_list_comments(); ?>
  </ol>
</div>
<div class="navigation">
  <div class="alignleft">
    <?php previous_comments_link() ?>
  </div>
  <div class="alignright">
    <?php next_comments_link() ?>
  </div>
</div>
<?php else : // this is displayed if there are no comments so far ?>
<?php if ( comments_open() ) : ?>
<!-- If comments are open, but there are no comments. -->

	<?php else : // this is displayed if there are no comments so far ?>
<?php if ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments">Comments are closed.</p>
	<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>
<?php endif; // if you delete this the sky will fall on your head ?>
