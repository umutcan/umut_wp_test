<div class="span-8 sidebars">

<ul>

<?php 	/* Widgetized sidebar, if you have the plugin installed. */

					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(LeftSidebar) ) : ?>

<?php wp_list_pages('title_li=<h2 class="lsidebartitle">Pages</h2>' ); ?>

<?php wp_list_categories('show_count=1&title_li=<h2 class="lsidebartitle">Categories</h2>'); ?>

<li><h2 class="lsidebartitle">Archives</h2>

				<ul>

				<?php wp_get_archives('type=monthly'); ?>

				</ul></li>

<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>			

		<li><h2 class="lsidebartitle">Links</h2>

                                <ul>

                   <?php get_links(-1, '<li>', '</li>', ' - '); ?>

                                </ul></li>
<?php } ?>

<?php endif; ?>

</ul>

</div>