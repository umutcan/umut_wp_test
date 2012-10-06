	<div id="sidebar">
		<ul>

			<li><h2>About</h2>
				<ul>
					<div id="aboutimage"></div>
					<div id="abouttxt">
						<?php bloginfo('description'); ?>
					</div>
				</ul>
			</li>

			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

			<?php wp_list_pages('title_li=<h2>Pages</h2>' ); ?>

			<?php wp_list_categories('show_count=1&title_li=<h2>Categories</h2>'); ?>


				<?php wp_list_bookmarks(); ?>


			<li><h2>Archives</h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

				<li><h2>Meta</h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
					<?php wp_meta(); ?>
				</ul>
				</li>


			<?php endif; ?>
		</ul>
	</div>

