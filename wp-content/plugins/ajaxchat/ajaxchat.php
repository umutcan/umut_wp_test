<?php
/*
Plugin Name: AjaxChat
Plugin URI: http://paydensutherland.com
Description: This plugin provides instant messaging between two or more parties viewing your blog.
Version: 0.5.1
Author: Payden Sutherland
Author URI: http://paydensutherland.com
License: GPL2


  Copyright 2010  Payden Sutherland  (email: payden@paydensutherland.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
ob_start(); //output buffering to buffer any headers
date_default_timezone_set('America/New_York');
define('AJAXCHAT_VERSION','0.5.1');
define('AJAXCHAT_PATH',dirname(__FILE__));
require_once('ajaxchat_options.php');
register_activation_hook(__FILE__,'activate_me');
register_deactivation_hook(__FILE__,'deactivate_me');
add_action('init','ajaxchat_script');
add_action('wp_print_styles','ajaxchat_style');
add_action('wp_login','ajaxchat_wp_login');
add_filter('wp_print_footer_scripts','ajaxim_footer');

global $wpdb;
if(!function_exists('getrand')) {
	function getrand($len) {
	        $str="";
	        $a=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9');
	        $arrcnt=count($a);
	        for($i=0;$i<$len;$i++)
	                $str.=$a[rand(0,$arrcnt-1)];
	        return $str;
	}
}
if(!function_exists('ajaxchat_style')) {
	function ajaxchat_style() {
		wp_register_style('ajaxchat_style',plugins_url('/ajaxchat.css.php',__FILE__));
		wp_enqueue_style('ajaxchat_style');
	}
}
if(!function_exists('ajaxchat_script')) {
	function ajaxchat_script() {
		wp_register_script('ajaxchat_script',plugins_url('ajaxchat.js.php',__FILE__),array('jquery'));
		wp_register_script('jquery-ui','https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js',array('jquery'));
		wp_enqueue_script('ajaxchat_script');
		wp_enqueue_script('jquery-ui');
	}
}
if(!function_exists('ajaxchat_wp_login')) {
	function ajaxchat_wp_login($user_name) {
		if(!($data=get_userdatabylogin($user_name))) { return; }
		$dispname = strlen($data->display_name) <= 15 ? $data->display_name : substr($data->display_name,0,15);
		if(!session_id()) { session_start(); }
		global $wpdb;
		$rows=$wpdb->get_results($wpdb->prepare("SELECT sessid FROM ajaxim_sess WHERE name='%s'",$dispname)); //Sorry your name's about to be clobbered.
		foreach($rows as $i=>$row) {
			$newName = "Guest_".getrand(5);
			$wpdb->query($wpdb->prepare("UPDATE ajaxim_sess SET name='%s' WHERE sessid='%s'",$newName, $row->sessid));
		}
		$wpdb->query($wpdb->prepare("UPDATE ajaxim_sess SET name='%s' WHERE sessid='%s'",$dispname,session_id()));
		$_SESSION['myName']=$dispname;
	}
}
if(!function_exists('ajaxim_footer')) {
	function ajaxim_footer() {
		$user=get_currentuserinfo();
		if(preg_match('/^\/wp-admin\//',$_SERVER['PHP_SELF']) || preg_match('/^\/wp-login/',$_SERVER['PHP_SELF'])) { die(); }
		echo "<div id='ajaxIM'>\n";
		echo "<div style='float:left;font-weight:bold;'>AjaxChat v".get_option('ajaxchat_version')."</div>\n";
		echo "<div style='float:right;font-weight:bold;'><a href='javascript:void(0);' id='open-online'>Chat&nbsp;(<span id='online_count'></span>)</a></div>\n";
		echo "</div>\n";
		//Do main ajaxchat window
		?>
		<div id='ac_window' style='display:none;'>
			<table id='ac_window_table' align='center' width='100%' cellpadding='3' cellspacing='1' style='background-color:#000;border-collapse:separate;''>
			<tr id='ac_window_title' class='tableheader'>
				<td colspan='2'><div><div style='display:inline-table;float:left;'><a href='javascript:void(0);' id='pop-toggle'>Pop in/out</a></div><div style='display:inline;'>AjaxChat v<?php echo get_option('ajaxchat_version');?></div><div style='display:inline-table;float:right;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' id='closeX'>X</a></div></td>
			</tr>
			<tr class='tabledata'>
				<td><input type='text' id='myName' style='width:200px;'>&nbsp;<input type='button' id='nameBtn' value='Edit Name'/></td><td rowspan='2' style='width:25%;text-align:center;vertical-align:top;'><strong>Online</strong><div id='online_list'></div></td>
			</tr>
			<tr class='tabledata'>
				<td>
					<div id='messages'>
					<?php
						if(!session_id()) { @session_start(); }
						$_SESSION['lastmsg']=0;
						include(AJAXCHAT_PATH.'/ajaxchat_ping.php');
					?>
					</div>
				</td>
			</tr>
			<tr class='tabledata'>
				<td colspan='2'><input type='text' id='msg' style='width:99%;'/></td>
			</tr>
			</table>
		</div>
		<?php
	}
}
if(!function_exists('activate_me')) {
	function activate_me() {
		global $wpdb;
		if(get_option('ajaxchat_version')=='') { add_option('ajaxchat_version',AJAXCHAT_VERSION); }
		else {
			if(get_option('ajaxchat_version')!=AJAXCHAT_VERSION) { update_option('ajaxchat_version',AJAXCHAT_VERSION); $upgrade=true; }
		}
		if(get_option('ajaxchat_bottom_color')=='') { add_option('ajaxchat_bottom_color','#c0c0c0'); }
		if(get_option('ajaxchat_header_color')=='') { add_option('ajaxchat_header_color','#2a4480'); }
		require_once(ABSPATH.'/wp-admin/includes/upgrade.php');
		if(($wpdb->get_var("SHOW TABLES LIKE 'ajaxim_sess'") != "ajaxim_sess") || ($wpdb->get_var("SHOW TABLES LIKE 'ajaxim_data'")!="ajaxim_data")) {
			$sql = "CREATE TABLE  ajaxim_sess (
				name VARCHAR(256) NOT NULL,
				lasttime BIGINT NOT NULL,
				starttime BIGINT NOT NULL,
				sessid VARCHAR(128) NOT NULL PRIMARY KEY
				);";
			dbDelta($sql);
			$sql = "CREATE TABLE ajaxim_data (
				name VARCHAR(256) NOT NULL,
				time BIGINT NOT NULL,
				msg VARCHAR(1024) NOT NULL,
				msgid INT NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (msgid)
				);";
			dbDelta($sql);
		}
		return true;
	}
}
if(!function_exists('deactivate_me')) {
	function deactivate_me() {
		//nothing yet.
	}
}
?>
