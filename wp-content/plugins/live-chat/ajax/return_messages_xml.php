<?php

//check the email address
if(!isset($_POST['theusername'])){exit();}
$username=trim(addslashes($_POST['theusername']));

//including the library that allows to use the wpdb object with external files
require_once('../../../../wp-load.php');

//get the messages from the database
global $wpdb;$table_name=$wpdb->prefix."live_chat_table";
$results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC LIMIT 50", ARRAY_A);

//return an array with the elements in reverse order
$results=array_reverse($results);

//START OUTPUT

//generate the xml header
header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');

$outstr.="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<data>\n";

if(count($results)>0){
		foreach($results as $element) {
			
			$outstr.="\t<element>\n";
			$outstr.="\t\t<id>".htmlspecialchars($element['id'],ENT_QUOTES,'UTF-8')."</id>\n";
			$outstr.="\t\t<message>".htmlspecialchars(stripslashes($element['message']),ENT_QUOTES,'UTF-8')."</message>\n";
			$outstr.="\t\t<username>".htmlspecialchars(stripslashes($element['username']),ENT_QUOTES,'UTF-8')."</username>\n";
			//$outstr.="\t\t<date>".htmlspecialchars($element['date'],ENT_QUOTES,'UTF-8')."</date>\n";
			$outstr.="\t\t<date>".htmlspecialchars(date('Y-m-d H:i:s',strtotime($element['date'])+(get_option('live_chat_timezone')*3600)),ENT_QUOTES,'UTF-8')."</date>\n";
			$outstr.="\t</element>\n";
			
		}			
}

$outstr.="</data>\n";
echo $outstr;

?>
