<?php
ob_start();
@header("Cache-Control: no-cache, must-revalidate");
@header("Pragma: no-cache");
if(!session_id()) { @session_start(); }
$id=is_int(intval($_GET['lastid']))?intval($_GET['lastid']):0;
function set_db_vars() {
	if(!file_exists(dirname(__FILE__).'/../../../wp-config.php')) {
		die("Could not find wp-config.php, please install ajaxchat in wp-content/plugins/");
	}
	$fh=fopen(dirname(__FILE__)."/../../../wp-config.php","r");
	if(!$fh) { die("Could not open wp-config.php for reading."); }
	while(!feof($fh)) {
		$line=fgets($fh);
		$matches=array();
		preg_match('/^define\(\'(.*)\', +?\'(.*)\'\);/',$line,$matches);
		if(count($matches)==3) { define($matches[1],$matches[2]); }
	}
	fclose($fh);
	if(!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASSWORD') || !defined('DB_NAME')) { die("There was an error finding your database settings in wp-config.php, please contact payden@paydensutherland.com'"); }
}
set_db_vars();
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
class psdb {
	private $db;
	public $error;
	public $last_result;
	function __construct() {
		if(!($this->db=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD))) { die("Unable to connect to database using configuration from wp-config.php"); }
		if(!mysql_select_db(DB_NAME,$this->db)) { die("Unable to select database from wp-config.php"); }
	}
	function get_row($q) {
		if(empty($q)) { return false; }
		$r=mysql_query($q);
		if(!$r) { $this->error="Error executing SQL query '".$q."': ".mysql_error(); return false;}
		else { $this->error=false; }
		if(!mysql_num_rows($r)) { return 0; }
		$row=mysql_fetch_row($r);
		return $row;
	}
	function doquery($q) {
		if(empty($q)) { return false; }
		$r=mysql_query($q);
		if($r) { $this->error=false; return true; }
		else { $this->error=mysql_error(); }
	}
	function query_rows($q) {
		if(empty($q)) { return false; }
		$r=mysql_query($q);
		if(!$r) { $this->error="Error executing query '".$q."': ".mysql_error(); return -1; }
		else { $this->error=false; }
		return $r;
	}
}

$psdb=new psdb;
$t=time();
$r=$psdb->get_row("SELECT name,starttime,lasttime FROM ajaxim_sess WHERE sessid='".session_id()."'");
if($psdb->error) { print $psdb->error; }
if($r==0) {
	foreach($_COOKIE as $cname=>$cvalue) {
		if(preg_match('/^wordpress_logged_in/',$cname)) {
			$cvals=explode("|",$cvalue);
			$name=$cvals[0];
		}
	}
	$name=$name ? $name : "Guest_".getrand(5);
	$_SESSION['myName']=$name;
	$psdb->doquery("INSERT INTO ajaxim_sess (name,lasttime,starttime,sessid) VALUES('".$name."',".$t.",".$t.",'".session_id()."')");
	if($psdb->error) { print $psdb->error; }
}
else {

	if($t-$r[2]>=30) { $psdb->doquery("UPDATE ajaxim_sess SET lasttime=".$t." WHERE sessid='".session_id()."'"); }
}
$_SESSION['lastmsg']=$id;
$r2=$psdb->query_rows("SELECT name,time,msg,msgid FROM ajaxim_data WHERE time>".($r==0?$t:$r[1])." AND msgid>".$id." ORDER BY time");
if($psdb->error) { print $psdb->error; }
if(mysql_num_rows($r2)) {
	$str="";
	while($row=mysql_fetch_row($r2)) {
		$str.="<div id='".$row[3]."' name='".$row[3]."'>(".date("g:ia",$row[1]).")&nbsp;&lt;".$row[0]."&gt;&nbsp;".stripslashes($row[2])."</div>";
	}
	print $str;
}
?>
