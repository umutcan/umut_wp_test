<?php

// look up for the path
require_once( dirname( dirname(__FILE__) ) . '/mvt-config.php');
// check for rights
if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to be here"));

global $wpdb;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo __("Add video by using MyVideotag", "my-videotag")?></title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo MVT_URLPATH ?>tinymce/tinymce.js"></script>
	<base target="_self" />
</head>
<body>
	<form onSubmit="insertMVTLink.insert();return false;" name="MVT" action="#">
    
		<table border="0" cellpadding="4" cellspacing="0">
         <tr>
          <td><p><center><label for="mvtvideourl"><strong style="font-size:11px;"><?php echo __("Please enter your video URL", "my-videotag")?>:</strong></label></p>
          <p><input type="text" id="mvtvideourl" name="mvtvideourl" value="" size="65" style="padding:5px; font-size:11px;" /></p></center></td>
          </tr>
          </table>
          
          <table border="0" cellpadding="4" cellspacing="0">
          <p><strong style="color:#F00"><?php echo __("Custom Settings (Optional)", "my-videotag")?>:</strong></label></p>
           <tr>
            <td nowrap="nowrap"><label for="mvtvideowidth"><strong>Width</strong></label></td>
            <td><input type="text" id="mvtvideowidth" name="mvtvideowidth" size="7" />&nbsp;px</td>
          </tr>
           <tr>
            <td nowrap="nowrap"><label for="mvtvideoheight"><strong>Height</strong></label></td>
            <td><input type="text" id="mvtvideoheight" name="mvtvideoheight" size="7" />&nbsp;px</td>
          </tr>
        </table>

	<div class="mceActionPanel" style="margin-top:15px">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="Insert" onClick="insertMVTLink();" />
		</div>
	</div>
</form>
</body>
</html>
