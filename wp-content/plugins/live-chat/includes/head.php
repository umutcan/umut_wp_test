<?php

//hooks
add_action('wp_head','lc_head_embed');
add_action('admin_head','lc_admin_head');
add_action('init','lc_add_jquery');

//add jquery in the head
function lc_add_jquery(){
	wp_enqueue_script('jquery');
}

//writing in frontend head
function lc_head_embed()
{
	
	echo '<script type="text/javascript">';
	echo 'var blog_url = \''.get_bloginfo('wpurl').'\'';
	echo '</script>'."\n";
	echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/live-chat/js/functions.js"></script>';
	echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/live-chat/js/md5.js"></script>';
	echo '<link rel="stylesheet" type="text/css" media="all" href="'.WP_PLUGIN_URL.'/live-chat/css/style.css" />';
	
}

//writing in backend head
function lc_admin_head()
{
	
	echo '<link rel="stylesheet" type="text/css" media="all" href="'.WP_PLUGIN_URL.'/live-chat/css/style.css" />';
	
}

?>
