<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php if ( is_home() ) { bloginfo('name') ?> | <?php bloginfo('description'); } else { wp_title(''); ?> | <?php bloginfo('name'); } ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/print.css" type="text/css" media="print" />

<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/ie.css" type="text/css" media="screen, projection" />

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>

</head>

<body class="wrapper">

	<div class="container">

<div id="navmenu">

<ul>

<?php wp_list_pages('title_li=' ); ?>

</ul>

</div>

<div class="span-24 header_wrapper">

        <div class="span-16">

<div class="header">

<div id="desc"><?php bloginfo('description'); ?></div>

<div id="blogname"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></div>		

<dl class="feed">

<dt><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to the feed"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/feed.png" alt="Subscribe to the feed" /> Feed</a></dt>


<dt><a href="<?php bloginfo('comments_rss2_url'); ?>
" title="Comments feed"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/feed.png" alt="Comments feed" /> Comments feed</a></dt>

</dl>

<?php include (TEMPLATEPATH . "/searchform.php"); ?>

<?php include (TEMPLATEPATH . "/adsense_link_unit.php"); ?> 

        </div>

</div>

        <div class="span-8 last">

<?php include (TEMPLATEPATH . "/header_ad.php"); ?> 

</div>

</div>