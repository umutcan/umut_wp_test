<?php

//hook
add_action( 'get_footer', 'lc_show_chat' );

//show the front end form
function lc_show_chat()
{

	?>

	<div id="lc-chat" style="border: 1px solid #<?php echo get_option('live_chat_color2'); ?> !important;" >
	
		<?php
		
		//chat only for registered users
		global $current_user;
        get_currentuserinfo();
        if(get_option('live_chat_only_members')=="1" and strlen($current_user->display_name)==0){
			
			?>
			
			<!-- chat head -->
			<div class="lc-chat-head" >
				<div id="lc-status-icon" class="lc-status-icon-offline"></div><?php if(get_option('live_chat_author_link')=="1"){echo '<a href="http://www.danycode.com/live-chat/" target="_blank">Live Chat</a>';}else{echo '<p>Live Chat</p>';} ?>
				<div id="lc-close-icon" onclick="javascript: lcCollapse();" ></div>
				<div id="lc-open-icon" onclick="javascript: lcExpand();" ></div>
			</div>		
			
			<div id="lc-only-members">
				<div id="lc-welcome-message">The Live Chat is available only for registered users</div>
				<a id="lc-user-login" href="<?php echo get_home_url(); ?>/wp-login.php">Log In</a>
				<a id="lc-user-register" href="<?php echo get_home_url(); ?>/wp-login.php?action=register">Register</a>
			</div>
			
			<?php
			
		}else{
			
			?>
			
			<!-- store some hidden informations -->
			<input type="hidden" name="livechatstatus" id="livechatstatus" value="closed">
			<input type="hidden" name="livechatusername" id="livechatusername" value="<?php echo lc_get_username(); ?>">
			<input type="hidden" name="livechatlastactivity" id="livechatlastactivity" value="">
			<input type="hidden" name="livechatshowavatar" id="livechatshowavatar" value="<?php if(get_option('live_chat_show_avatar')=="1"){echo 1;}else{echo 0;} ?>">
			<input type="hidden" name="livechatdirection" id="livechatdirection" value="<?php if(get_option('live_chat_direction')=="1"){echo 1;}else{echo 0;} ?>">
			
			<!-- chat head -->
			<div class="lc-chat-head" >
				<div id="lc-status-icon" class="lc-status-icon-offline"></div><?php if(get_option('live_chat_author_link')=="1"){echo '<a href="http://www.danycode.com/live-chat/" target="_blank">Live Chat</a>';}else{echo '<p>Live Chat</p>';} ?>
				<div id="lc-close-icon" onclick="javascript: lcCollapse();" ></div>
				<div id="lc-open-icon" onclick="javascript: lcExpand();" ></div>
			</div>
			
			<!-- chat middle -->
			<div class="lc-chat-middle" <?php if(get_option('live_chat_direction')=="1"){echo 'style="direction: rtl !important;"';} ?> >
			
			</div>
			
			<!-- chat bottom -->
			<div class="lc-chat-bottom">
				
				<!-- messagge submit form -->
				<form id="lc-submit-form" action="" onsubmit="return lcAddMessage()">
					<input id="lc-username" type="hidden" value="">
					<input id="lc-message" type="text" autocomplete="off" value="" maxlength="255" >
					<input id="lc-button" type="submit" value="SEND" >
				</form>
			</div>
			
			<!-- chat welcome -->
			
			<div id="lc-set-username-container">
				
				<div id="lc-welcome-message">Join the Live Chat</div>
				<form id="lc-set-username-form" action="" onsubmit="javascript: return lcSetUsername();">
					<input id="lc-set-username" type="text" autocomplete="off" value="Your Name" onclick="javascript: jQuery('#lc-set-username').val('');" >
					<input id="lc-chat-now" type="submit" value="Chat Now" >
				</form>
			
			</div>			
			
			<?php
			
		}

		
		
		?>
		
	</div>
	
	<?php

}

function lc_get_username(){
	
	global $current_user;
    get_currentuserinfo();

	if(get_option('live_chat_only_members')=="1" and strlen($current_user->display_name)>0){
		$lc_username=$current_user->display_name;
	}else{
		if(isset($_COOKIE["livechatusername"])){
			$lc_username=$_COOKIE["livechatusername"];
		}else{
			$lc_username="";
		}
	}
	
	return $lc_username;
	
}

?>
