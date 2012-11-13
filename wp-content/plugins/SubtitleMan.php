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
add_action( 'admin_init', 'my_plugin_admin_init' );

function my_plugin_menu() {
	add_menu_page( 'SubTitle Man', 'SubTitle Management', 'manage_subtitle', 'sub-man', 'my_plugin_options' );
        add_action('admin_print_scripts-' . $page, 'my_plugin_admin_scripts');
        
}

function my_plugin_admin_init() {
        /* Register our script. */
        wp_register_script( 'my-plugin-script', plugins_url('/script.js', __FILE__) );
    }
    
function my_plugin_options() {
	if ( !current_user_can( 'manage_subtitle' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        $params=$_REQUEST;
        if(isset($params["project"])&&isset($params["ep"])&&isset($params["file"]))
            include_once '../wp-admin/subtitle/todb.php';
        else if(isset($params["new"])&&$params["new"]=1)
            include_once '../wp-admin/subtitle/load.php';
        else if(isset($params["pid"])&&$params["pid"]>0 && isset($params["download"])&&$params["download"]>0){
            /*wp_redirect('../wp-admin/subtitle/download.php?pid='.$params["pid"]);//
            exit();*/
            $location="../wp-admin/subtitle/download.php?pid=".$params["pid"]."&download=".$params["download"];
            echo "<meta http-equiv='refresh' content='0;url=$location' />";
        }
        else if(isset($params["pid"])&&$params["pid"]>0)
            include_once '../wp-admin/subtitle/subtitle.php';
        else
            include_once '../wp-admin/subtitle/list.php';
        
        echo "";
}

function my_plugin_admin_scripts() {
        /*
         * It will be called only on your plugin admin page, enqueue our script here
         */
        wp_enqueue_script( 'my-plugin-script' );
    }
  
    function get_subtitle_list($req){
        
        $params=$req;
        if(isset($params["pid"])&&$params["pid"]>0)
            include_once 'wp-admin/subtitle/subtitle_front.php';
        else
            include_once 'wp-admin/subtitle/list_front.php';
    }
?>
