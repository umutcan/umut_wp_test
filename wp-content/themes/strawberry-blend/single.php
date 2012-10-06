<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="categorylink">Category: <?php the_category(', ') ?></div>

				<div class="entry">
					<?php the_content('Read the rest of this entry &raquo;'); ?>
					<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
				</div>

				<div class="postmetadata">
					<div class="category"><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></div>
					<div class="comments"><?php comments_number('No comments yet', '1 comment so far', '% comments so far', 'comments-link', 'Comments off'); ?></div>
				</div>
			</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
