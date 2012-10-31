<?php
ob_start();
require_once('ajaxchat_config.php');
@header("Cache-Control: no-cache, must-revalidate");
@header("Pragma: no-cache");
if(!session_id()) { @session_start(); }
global $wpdb;
print $wpdb->version;
global $current_user;
get_currentuserinfo();
$force_login=get_option('ajaxchat_force_login');
switch($_GET['action']) {
	case "test":
		print_r($_SESSION);
		break;
	case "popout":
		if(empty($_GET['status'])) { die(); }
		$_SESSION['popOut']=$_GET['status'];
		break;
	case "sendCoord":
		$_SESSION['winCoord']="left: ".($_GET['left'] ? $_GET['left'] : 0).", top: ".($_GET['top'] ? $_GET['top'] : 0);
		break;
	case "open":
		if($_GET['val']==1) { $_SESSION['open']=1; }
		else { $_SESSION['open']=0; }
		break;
	case "updateName":
		if(empty($_GET['name'])) { die("Error: No name provided"); }
		if(strlen($_GET['name'])>15) { $name=substr($_GET['name'],0,15); }
		else { $name=$_GET['name']; }
		if($name==$_SESSION['myName']) { die("Error: Your name is already `".$name."`"); }
		$r=$wpdb->query($wpdb->prepare("SELECT name FROM ajaxim_sess WHERE name='%s' AND lasttime>%d",$name,time()-40));
		if($r) { die("Error: Name already in use."); }
		$r=$wpdb->query($wpdb->prepare("UPDATE ajaxim_sess SET name='%s' WHERE sessid='%s'",$name,session_id()));
		if($r) { print "OK:".stripslashes($name); $_SESSION['myName']=$name; }
		else { print "Error: ".$wpdb->last_error; }
		break;
	case "online_list":
		if($current_user->ID=='' && $force_login) { die("Please login."); }
		$r=$wpdb->query("SELECT name,sessid FROM ajaxim_sess WHERE lasttime>=".(time()-35)." ORDER BY name");
		/* Print number of rows and a delimiter
		   We'll use this to send both the count
		   and names at the same time and parse
		   it out on the JavaScript side.       */
		print $r."::";
		foreach($wpdb->last_result as $row) { print "<div>".($row->sessid==session_id() ? "<strong>" : "").stripslashes($row->name).($row->sessid==session_id() ? "</strong>" : "")."</div>"; }
		break;
	case "send":
		if($current_user->ID=='' && $force_login) { die(); }
		if(empty($_POST['msg'])) { die("ERROR: No message to send"); }
		$msg=strip_tags($_POST['msg']);
		if(!strlen($msg)) { die(); }
		$r=$wpdb->get_row("SELECT MAX(msgid) FROM ajaxim_data",ARRAY_N);
		$max=$r[0]+1;
		$r=$wpdb->get_row("SELECT name FROM ajaxim_sess WHERE sessid='".session_id()."'");
		$r2=$wpdb->query($wpdb->prepare("INSERT INTO ajaxim_data (name,msg,time,msgid) VALUES('%s','%s',".time().",%s)",$r->name,$msg,$max));
		break;
	default:
}
?>
