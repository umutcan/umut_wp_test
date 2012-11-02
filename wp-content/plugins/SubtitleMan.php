<?php
/**
 * @package Umut_Making
 * @version 1.6
 */
/*
  Plugin Name:Subtitle Management
  Plugin URI: http://wordpress.org/extend/plugins/hello-dolly/
  Description: My custom functions...
  Author: Matt Mullenweg
  Version: 1.6
  Author URI: http://ma.tt/
 */


add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_menu_page( 'SubTitle Man', 'My Plugin', 'manage_options', 'sub-man', 'my_plugin_options' );
}

function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        $params=$_REQUEST;
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
        if(isset($params["project"])&&isset($params["ep"])&&isset($params["file"]))
            include_once '../wp-admin/subtitle/todb.php';
        else
            include_once '../wp-admin/subtitle/load.php';
}

?>
