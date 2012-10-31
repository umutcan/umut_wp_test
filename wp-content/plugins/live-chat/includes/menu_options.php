<?php

//menu options page
function live_chat_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    
    // Save options if user has posted some information  
    if(isset($_POST['submitted'])){  
		if(isset($_POST['authorlink'])){update_option('live_chat_author_link',"1");}else{update_option('live_chat_author_link',"0");}
		if(isset($_POST['showavatar'])){update_option('live_chat_show_avatar',"1");}else{update_option('live_chat_show_avatar',"0");}
		if(isset($_POST['direction'])){update_option('live_chat_direction',"1");}else{update_option('live_chat_direction',"0");}
		if(isset($_POST['onlymembers'])){update_option('live_chat_only_members',"1");}else{update_option('live_chat_only_members',"0");}
		if(isset($_POST['sendemail'])){update_option('live_chat_send_email',"1");}else{update_option('live_chat_send_email',"0");}

		//set timezone
		if(isset($_POST['timezone'])){
			if( round(intval($_POST['timezone'])) < -24 or round(intval($_POST['timezone'])) > 24 ){
				echo '<div class="error"><p>Invalid Timezone</p></div>';
			}else{
				update_option('live_chat_timezone',round($_POST['timezone']));
			}
		}
	
		//setting saved message
		echo '<div class="updated"><p>Settings saved</p></div>';
	
	}
			
	
	// Delete all the messages
	if(isset($_POST['deletemessages'])){
		lc_delete_all_messages();
		echo '<div class="updated"><p>All the messages have been deleted</p></div>';
	}
	
	// OUTPUT START
	
	?>
	
	<!-- display the form -->
	<div class="live-chat-admin wrap">
		<div class="icon-title">
			<h2>General Options</h2>
		</div>		
		
		<form method="post" action="">
		
			<input type="hidden" name="submitted" value="1">
			
			<label>Official Page link - This is a little optional kindnees to the plugin author</label><input name="authorlink" type="checkbox" value="true" <?php if(get_option('live_chat_author_link')=="1"){echo 'checked="checked"';} ?> /><span>Link the chat title to the plugin Official Page</span>
			<label>Select this field to enable the identicon avatars - GD PHP Library Required</label><input name="showavatar" type="checkbox" value="true" <?php if(get_option('live_chat_show_avatar')=="1"){echo 'checked="checked"';} ?> /><span>Show Avatars</span>
			<label>Set layout direction as Right to Left - Only for Arabic and Hebrew users</label><input name="direction" type="checkbox" value="true" <?php if(get_option('live_chat_direction')=="1"){echo 'checked="checked"';} ?> /><span>Right to Left Layout</span>
			<label>Select if the chat is available only for registered users<br />N.B. Remember to activate the [ Anyone can register ] option inside [ Setting -> General ] otherwise users can't register</label><input name="onlymembers" type="checkbox" value="true" <?php if(get_option('live_chat_only_members')=="1"){echo 'checked="checked"';} ?> /><span>Only registered user can use the chat</span>
			<label>Email me every new chat message</label><input name="sendemail" type="checkbox" value="true" <?php if(get_option('live_chat_send_email')=="1"){echo 'checked="checked"';} ?> /><span>Email me</span>
			<label>Timezone</label><input maxlength="3" name="timezone" type="text" value="<?php echo get_option('live_chat_timezone'); ?>" /><span>Insert a value between -24 and +24</span>	
			
			<input class="button-primary" type="submit" value="Save">
			
		</form>
		
		<div class="danycode-menu-separator"></div>	
		
		<label>Delete all the messages from the database</label>
		<form method="post" action="">
		
			<input type="hidden" name="deletemessages" value="1">
			
			<input class="button-secondary" type="submit" value="Delete">
			
		</form>
		
	</div>
	
	<!-- display credits -->
	<?php lc_danycode_credits('Live Chat','http://www.danycode.com/live-chat/'); ?>
	
	<!-- OUTPUT END -->
	
	<?php
}

//delete all the messages from the database
function lc_delete_all_messages(){

	global $wpdb;$table_name=$wpdb->prefix."live_chat_table";

	//delete all the record
	$wpdb->get_results("DELETE FROM $table_name");
	
}

?>
