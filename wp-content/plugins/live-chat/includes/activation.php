<?php

//hook
register_activation_hook(WP_PLUGIN_DIR.'/live-chat/main.php','live_chat_create_table');

//create prefix+live_chat_table
function live_chat_create_table(){
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	//create *prefix*_live_chat_table
	global $wpdb;$table_name=$wpdb->prefix . "live_chat_table";
	$sql = "CREATE TABLE $table_name (
	  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  message VARCHAR(255) DEFAULT '' NOT NULL,
	  ip VARCHAR(39) DEFAULT '' NOT NULL,
	  username VARCHAR(20) DEFAULT '' NOT NULL,
	  date DATETIME
	)
	COLLATE = utf8_general_ci
	";
	dbDelta($sql);
}

?>
