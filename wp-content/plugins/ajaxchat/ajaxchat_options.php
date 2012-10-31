<?php
if(is_admin()) {
add_action('admin_menu','ajaxchat_add_menu');
add_action('admin_init','ajaxchat_regset');
}
function ajaxchat_regset() {
register_setting('ajaxchat','ajaxchat_bottom_color');
register_setting('ajaxchat','ajaxchat_header_color');
}
function ajaxchat_add_menu() {
add_plugins_page('AjaxChat Configuration','AjaxChat Configuration','administrator',__FILE__,'ajaxchat_options');
}
function ajaxchat_options() {
?>
<div class='wrap'>
<h2>AjaxChat Configuration</h2>
<form method='post' action='options.php'>
<?php
settings_fields('ajaxchat');
$ajaxchat_bottom_color_current=get_option('ajaxchat_bottom_color');
$ajaxchat_header_color_current=get_option('ajaxchat_header_color');
?>
<table class='form-table'>
<tr valign='top'>
<th scope='row'>AjaxChat Bar Color:</th>
<td><input name='ajaxchat_bottom_color' type='text' value='<?php echo $ajaxchat_bottom_color_current;?>'/></td>
</tr>
<tr valign='top'>
<th scope='row'>AjaxChat Window Header Color:</th>
<td><input name='ajaxchat_header_color' type='text' value='<?php echo $ajaxchat_header_color_current;?>'/></td>
</tr>
</table>
<input type='hidden' name='action=' value='update'/>
<input type='hidden' name='options' value='ajaxchat_force_login,ajaxchat_bottom_color,ajaxchat_header_color'>
<p class='submit'>
<input type='submit' class='button-primary' value='<?php _e('Save Changes') ?>'/>
<?php
if($_GET['settings-updated']) { echo "<div style='font-weight:bold;'>Options Saved!</div>\n"; }
?>
</p>
</form>
</div>
<?php } ?>
