<?php
/*
Plugin Name: Live Chat
Plugin URI: http://www.danycode.com/live-chat/
Description: Add a live chat to your wordpress website
Version: 1.22
Author: Danilo Andreini
Author URI: http://www.danycode.com
Llcense: GPLv2 or later
*/

/*  Copyright 2012  Danilo Andreini (email : andreini.danilo@gmail.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Publlc Llcense
as published by the Free Software Foundation; either version 2
of the Llcense, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTlcULAR PURPOSE.  See the
GNU General Publlc Llcense for more details.

You should have received a copy of the GNU General Publlc Llcense
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

//embedding external files
require_once('includes/activation.php');
require_once('includes/menu_options.php');
require_once('includes/front_chat.php');
require_once('includes/head.php');
require_once('includes/credits.php');

//options initialization
if(strlen(get_option('live_chat_author_link'))==0){update_option('live_chat_author_link',"0");}
if(strlen(get_option('live_chat_show_avatar'))==0){update_option('live_chat_show_avatar',"0");}
if(strlen(get_option('live_chat_direction'))==0){update_option('live_chat_direction',"0");}
if(strlen(get_option('live_chat_only_members'))==0){update_option('live_chat_only_members',"0");}
if(strlen(get_option('live_chat_send_email'))==0){update_option('live_chat_send_email',"000000");}
if(strlen(get_option('live_chat_timezone'))==0){update_option('live_chat_timezone',"0");}

//create the mail list menu
add_action( 'admin_menu', 'live_chat_menu' );
function live_chat_menu() {
	$form_name='Live Chat';
	add_menu_page($form_name, $form_name, 'manage_options', 'lcmenu','live_chat_options',plugins_url().'/live-chat/img/icon16.png');
	add_submenu_page('lcmenu', $form_name.' - Options', 'Options', 'manage_options', 'lcmenu', 'live_chat_options');	
}

//delete database and options when the plugin is uninstalled
register_uninstall_hook( __FILE__, 'live_chat_uninstall' );
function live_chat_uninstall(){

	//deleting tables
	global $wpdb;
	
	$table_name=$wpdb->prefix . "live_chat_table";
	$sql = "DROP TABLE $table_name";
	mysql_query($sql);
	
	//deleting options
	delete_option('live_chat_author_link');
	delete_option('live_chat_show_avatar');
	delete_option('live_chat_direction');
	delete_option('live_chat_only_members');
	delete_option('live_chat_send_email');
	delete_option('live_chat_timezone');
	
}

?>
