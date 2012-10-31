jQuery(document).ready(function() {

	//*set the live chat status to closed to avoid the status set to open when an user update the page*
	
	//set the hidden field used as status flag to closed
	jQuery("#livechatstatus").val("closed");
	
	//*set the live chat status to closed to avoid a status set to open when an user update the page
	
	//*preload the stripes image*
	
	var imagePath=blog_url+'/wp-content/plugins/live-chat/img/ml-stripes.png';
	var image = jQuery('<img />').attr('src',imagePath);
	
	//*preload the stripes image
	

	//*general*

	//call this function every X time
	window.setInterval(lcUpdateChat,1000);
	
	//if the cookie/hidden is set log the user in the chat
	if(jQuery("#livechatusername").val().length>0){
		jQuery("#lc-username").val(jQuery("#livechatusername").val());
		jQuery("#lc-set-username-container").fadeOut(0);
		jQuery(".lc-chat-middle").fadeIn(0);
		jQuery(".lc-chat-bottom").fadeIn(0);
		
		lcUpdateChat();
	}
	
	//*general
	
	//*update the lastactivity hidden field*
	
	//save unixtime on ready event
	jQuery("#livechatlastactivity").val(getUnixtime());
	
	//save unixtime on mouse move event
    jQuery("html").mousemove(function(){
		jQuery("#livechatlastactivity").val(getUnixtime());
    });
    
    //save unixtime on key down event
	jQuery('html').keydown(function(event) {
		jQuery("#livechatlastactivity").val(getUnixtime());
	});
	
	//*update the lastactivity hidden field

});

function lcUpdateChat(){
	if( (jQuery("#livechatusername").val().length)>0 && jQuery("#livechatstatus").val()=="open" && userIsActive() ){
		
		//retrieve the last fifty messages
		lcReturnMessages();
		
		//update the status icon
		jQuery('#lc-status-icon').removeClass('lc-status-icon-offline lc-status-icon-online');
		jQuery('#lc-status-icon').addClass('lc-status-icon-online');
		
	}else{
		
		jQuery('#lc-status-icon').removeClass('lc-status-icon-offline lc-status-icon-online');
		jQuery('#lc-status-icon').addClass('lc-status-icon-offline');
		
	}	
}

//jquery ajax request - add the message to the database
function lcAddMessage(){
	message=jQuery("#lc-message").val();jQuery("#lc-message").val('');
	username=jQuery("#lc-username").val();
	
	if(message.length==0 || username.length==0){return false;}
	
	var url=blog_url+"/wp-content/plugins/live-chat/ajax/add_message.php"
    jQuery.post(url,{newmessage:message,newusername:username},function(result){
		if(result=="true"){
			//nothing
		}      
    });
    
    jQuery("#lc-message").focus();
    return false;
}

//jquery ajax request - return a json object with the last fifty messages
function lcReturnMessages(){
	
	username=jQuery("#lc-username").val();
	var url=blog_url+"/wp-content/plugins/live-chat/ajax/return_messages_xml.php"
    jQuery.post(url,{theusername:username},function(result){
		
		//parsing the xml response	
		var x=result.getElementsByTagName("element");	
		
		//if some message are added scroll the scrollbar after the XML parsing
		scroll=false;
		
		if (x.length>=0){
			for (i=0;i<x.length;i++){			
				
				id=(x[i].getElementsByTagName("id")[0].childNodes[0].nodeValue);
					
				message=(x[i].getElementsByTagName("message")[0].childNodes[0].nodeValue);
				username=(x[i].getElementsByTagName("username")[0].childNodes[0].nodeValue);
				date=(x[i].getElementsByTagName("date")[0].childNodes[0].nodeValue);
				
				//if the new id is the next or there are no selector then add it
				if( (id==parseInt(jQuery(".hidden-id").last().text())+1) || (jQuery(".hidden-id").length==0) ){
				
				
					//if there are 50 messages in the chat delete the first element
					if(jQuery(".hidden-id").length==50){
						jQuery(".lc-single-message:eq(0)").remove();
						jQuery(".hidden-id:eq(0)").remove();
					}					
					
					//set avatar based on hidden based on wp options
					if (parseInt(jQuery("#livechatshowavatar").val())==1){						
						if (parseInt(jQuery("#livechatdirection").val())==0){
							identicon='<img class="lc-img-left" src="'+blog_url+'/wp-content/plugins/live-chat/identicon/identicon.php?size=32&#38;hash='+jQuery.md5(htmlSpecialCharsJs(username))+'">';
							messageAlternativeStyle='';
						}else{
							identicon='<img class="lc-img-left" style="left: none; right: 0; margin: 0 0 0 4px;" src="'+blog_url+'/wp-content/plugins/live-chat/identicon/identicon.php?size=32&#38;hash='+jQuery.md5(htmlSpecialCharsJs(username))+'">';
							messageAlternativeStyle='style="padding: 0 36px 0 0px !important; float: right !important;"';
						}
						
					}else{
						identicon='';
						if (parseInt(jQuery("#livechatdirection").val())==0){
							messageAlternativeStyle='style="padding: 0 4px !important;"';
						}else{
							messageAlternativeStyle='style="padding: 0 0 0 4px !important; float: right !important;"';
						}
					}
					
					//adding message to the chat
					hiddenId='<div class="hidden-id">'+id+'</div>';
					jQuery('.lc-chat-middle').append(hiddenId+'<div class="lc-single-message clearfix" id="new-message-'+id+'" style="display: none !important;">'+identicon+'<div class="lc-msg-right" '+messageAlternativeStyle+' ><span class="lc-message-username">'+htmlSpecialCharsJs(username)+' </span><span class="lc-message-date">'+date+'</span><br />'+htmlSpecialCharsJs(message)+'</div>');
					jQuery('#new-message-'+id).fadeIn(500);
					
					//set the scroll variable to true
					scroll=true;
				}		

			}
			//if some messages are added scroll the scrollbar to the max bottom
			if(scroll==true){
				jQuery('.lc-chat-middle').scrollTop(100000);
			}
				  
		}
		
	});
    
    return false;
}

function lcSetUsername(){
	
	username=jQuery("#lc-set-username").val();
	
	//set the hidden field used as status flag flag
	jQuery("#livechatusername").val(username);	
	
	if(username=='Your Name' || username.length==0){
		jQuery("#lc-set-username").focus();
		return false;
	}
	
	jQuery("#lc-username").val(username);
	
	jQuery("#lc-set-username-container").fadeOut(0);
	jQuery(".lc-chat-middle").fadeIn(0);
	jQuery(".lc-chat-bottom").fadeIn(0);
	
	lcReturnMessages();
	
	username=jQuery("#lc-username").val();
	var url=blog_url+"/wp-content/plugins/live-chat/ajax/set_username_cookie.php";
    jQuery.post(url,{newusername:username},function(result){
		if(result=="true"){
			//nothing
		}      
    });	
	
	return false;
	
}

function htmlSpecialCharsJs(str){
	str=str.replace(/&/g, "&amp;");
	str=str.replace(/\"/g, "&quot;");
	str=str.replace(/\'/g, "&#039;");
	str=str.replace(/</g, "&lt;");
	str=str.replace(/>/g, "&gt;");
	return str;
}

//collapse the chat and send an ajax request that set the livechatstatus to closed
function lcCollapse(){
	
	//set the hidden field used as status flag
	jQuery("#livechatstatus").val("closed");
	
	//change the chat visualization
	jQuery('.lc-chat-middle').fadeOut(0);
	jQuery('.lc-chat-bottom').fadeOut(0);
	jQuery('#lc-set-username-container').fadeOut(0);
	jQuery('#lc-chat').css("height","22px");

}

//expand the chat and send an ajax request that set the livechatstatus to open
function lcExpand(){
	
	//set the hidden field used as status flag
	jQuery("#livechatstatus").val("open");
	
	//open the chat
	jQuery('#lc-chat').css("height","286px");
	
	if(jQuery("#livechatusername").val().length>0){		
		jQuery('.lc-chat-middle').fadeIn(0);
		jQuery('.lc-chat-bottom').fadeIn(0);		
		lcReturnMessages();	
	}else{
		jQuery('#lc-set-username-container').fadeIn(0);
	}
	
}

function getUnixtime(){
	var d = new Date();
	return parseInt((d.getTime()/1000));	
}

function userIsActive(){
	if( (getUnixtime()-jQuery("#livechatlastactivity").val()) < 120 ){
		return true;
	}else{
		return false;
	}
}
