<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" /> 
	<?php if (is_single()) {
		?>
		<title><?php the_title(); ?> | <?php bloginfo('name'); ?></title>
		<?php } else { ?>
			<title><?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></title>
			<?php } ?>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<?php include_once("colors.php"); ?>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>

</head>
<body>

		<div id="header_nav">
        	<ul id="nav" class="clearfloat">
				<li><a href="<?php echo get_option('home'); ?>/" class="on">Home</a></li> 
				<?php wp_list_pages('title_li='); ?>
			</ul>
		</div>
		<div id="header_left">
        	<div class="blogtitle">
            	<a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>
            </div>
            <div class="blogdescription">
				<?php bloginfo('description'); ?>
            </div>
		</div>