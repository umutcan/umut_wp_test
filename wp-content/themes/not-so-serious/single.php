<?php get_header(); ?>

<div class="span-24 main">		

        <div class="span-13">

<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">

				<h1 class="posttitle"><?php the_title(); ?></h1>

 				<p><?php the_time('F j, Y') ?> in <?php the_category(', ') ?><?php the_tags(' Tags: ', ', ', '<br />'); ?></p>

				<div class="entry">

					<?php the_content('Read the rest of this entry &raquo;'); ?>

				</div>

<br clear="all"/>

<div class="postmetadata background">

 <?php post_comments_feed_link('RSS 2.0 Comments Feed'); ?> | 

<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {// Both Comments and Pings are open ?>

<a href="#respond">Leave a Response</a> | <a href="<?php trackback_url(); ?>" rel="trackback">Trackback</a>

<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {

// Only Pings are Open ?>

Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">Trackback</a>.

<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {

// Comments are open, Pings are not ?>

You can skip to the end and leave a response. Pinging is currently not allowed.

<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {

// Neither Comments, nor Pings are open ?>Both comments and pings are currently closed.

<?php } edit_post_link('Edit this entry.','',''); ?>

</div>				

			</div>

		<?php endwhile; ?>

	<?php comments_template(); ?>

<br clear="all"/>

<div class="navigation">

			<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>

			<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>

		</div>

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



        

		

  	