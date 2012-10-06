<?php get_header(); ?>

<div class="span-24 main">		

        <div class="span-13">

<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">

				<h1 class="posttitle"><?php the_title(); ?></h1>

				<div class="entry">

					<?php the_content('Read the rest of this entry &raquo;'); ?>

				</div>

				<p><?php the_time('F j, Y') ?> in <?php the_category(', ') ?><br /> <?php the_tags('Tags: ', ', ', ''); ?> <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> <?php edit_post_link('Edit', '', ''); ?></p>

			</div>

		<?php endwhile; ?>

		<div class="navigation">

			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>

			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>

		</div>

<br clear="all"/>

	<?php else : ?>

		<h2 class="posttitle">Not Found</h2>

		<p>Sorry, but you are looking for something that isn't here.</p>

		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>

        </div>

<?php include (TEMPLATEPATH . "/lsidebar.php"); ?> 

<?php include (TEMPLATEPATH . "/rsidebar.php"); ?>

</div>

<?php get_footer(); ?>



        

		

  	