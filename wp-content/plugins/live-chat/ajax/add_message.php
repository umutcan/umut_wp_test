<?php

//including the library that allows to use the wpdb object with external files
require_once('../../../../wp-load.php');

//if there are too many messages in the last x minutes exit
if(too_many_messages()){exit('too_many_messages');}

//check if message and username are set
if(!isset($_POST['newmessage']) or !isset($_POST['newusername'])){exit('message or username not set');}

//sanitize data
if(!get_magic_quotes_gpc()){
	$message=trim(addslashes($_POST['newmessage']));
	$username=trim(addslashes($_POST['newusername']));	
}else{
	$message=trim($_POST['newmessage']);
	$username=trim($_POST['newusername']);	
}

//ignore the $_POST['newusername'] and set the username as logged user
//only if the user is logged and the chat is allowed only to registered user
global $current_user;get_currentuserinfo();
if(get_option('live_chat_only_members')=="1" and strlen($current_user->display_name)>0){
	$username=$current_user->display_name;
}

//check message and username
if(strlen($message)==0 or strlen($username)==0){echo "er1";return;}

//add the message address to the database
global $wpdb;$table_name=$wpdb->prefix."live_chat_table";

$ip_address=substr(trim($_SERVER['REMOTE_ADDR']),0,39);
//insert email into database
$sql = "INSERT INTO $table_name SET message='$message',ip='$ip_address',username='$username',date=NOW()";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

//email the new message to the website administrator
if(get_option('live_chat_send_email')=="1"){email_website_administrator($username,$message);}

//response
echo "true";

//if there are too many messages in the last x minutes exit
function too_many_messages(){
	
	//get the messages from the database
	global $wpdb;$table_name=$wpdb->prefix."live_chat_table";
	$results = $wpdb->get_results("SELECT * FROM $table_name WHERE date BETWEEN now()-60 AND now() ", ARRAY_A);
	
	//limit allowed is maximum 1000 messages for minutes
	if(count($results)>=1000){
		return true;
	}else{
		return false;
	}
	
}

//email the website administrator about the new chat message
function email_website_administrator($username,$message){
					
	//set the recipient as the sender
	$recipient=get_option('admin_email');
	$sender=get_option('admin_email');
	
	//set email subject and content
	$subject='New message from '.$username;
	$content='<p>'.$username.' wrote:</p>'.'<p><i>'.$message.'</i></p>';
	
	//send the email
	$headers = array();
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=UTF-8';
	$headers[] = 'Content-Transfer-Encoding: 7bit';        
	$headers[] = 'From: ' . $sender;
	mail($recipient,$subject,$content,join("\r\n", $headers));	
	
}

?>
