// Quick Chat 4.10 - admin
function quick_chat_clean_private(){jQuery.post(quick_chat_admin.ajaxurl,{action:"quick-chat-ajax-clean-private"},function(){alert(quick_chat_admin.i18n.clean_private_done)})}
jQuery(window).load(function(){jQuery("a#quick_chat_clean_private").bind("click",function(a){a.preventDefault();confirm(quick_chat_admin.i18n.clean_private_confirm)&&quick_chat_clean_private()});jQuery("a.quick_chat_show_hide").bind("click",function(a){a.preventDefault();"Show"==jQuery(this).text()?jQuery(this).text("Hide").siblings("textarea").slideDown("slow"):jQuery(this).text("Show").siblings("textarea").slideUp("slow")})});