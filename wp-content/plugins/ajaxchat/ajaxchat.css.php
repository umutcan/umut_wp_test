<?php
ob_start();
require_once('ajaxchat_config.php');
@header("Cache-Control: no-cache, must-revalidate");
@header("Pragma: no-cache");
@header("Content-type: text/css");
?>
#ajaxIM {position:fixed;left:0px;bottom:0px;z-index:100000;width:100%;background-color:<?php echo (get_option('ajaxchat_bottom_color')!=''?get_option('ajaxchat_bottom_color'):"#c0c0c0");?>;color:#000;text-align:left;padding:3px;margin:0px;border:1px solid black;}
#ajaxIM div {margin-left:10px;margin-right:10px;}
#ajaxIM a {color:#000;text-decoration:none;}
#ac_window {z-index:100000;}
.tableheader {background-color:<?php echo (get_option('ajaxchat_header_color')!=''?get_option('ajaxchat_header_color'):"#2a4480");?>;font-weight:bold;text-align:center;color:#fff;}
.tabledata {background-color:#f0f0f0;text-align:left;color:#000;}
#messages {height:250px;overflow-y:auto;width:300px;}
#online_count {padding:0px;margin:0px;display:inline;}
#online_list {width:100%;height:250px;overflow-y:auto;}
#online {width:450px;}
#online_tbl td {padding:5px;}
#ac_window {position:fixed;width:450px;background-color:#000;text-align:left;}
