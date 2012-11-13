<?php
ob_start();
require_once('ajaxchat_config.php');
@header("Cache-Control: no-cache, must-revalidate");
@header("Pragma: no-cache");
@header("Content-type: text/javascript");
if(!session_id()) { @session_start(); }
global $current_user;
get_currentuserinfo();
?>
//begin JS

(function($) {
	$(document).ready(function() {
		window.ajaxchat = new AjaxChat();
	});

	window.AjaxChat = function(options) {
		var self = this;
		self.ajaxchat_init();
	};

	window.AjaxChat.prototype = {
		ajaxchat_interval: null,
		myName: <?php echo strlen($_SESSION['myName']) > 0 ? "'".$_SESSION['myName']."'" : 'null'; ?>,
		popOut: <?php echo isset($_SESSION['popOut']) ? $_SESSION['popOut'] : 'false'; ?>,
		open: false,
		messageReq: null,
		uc: 0,
		lastID: <?php echo isset($_SESSION['lastmsg']) ? $_SESSION['lastmsg'] : 0; ?>,
		$name_input: null,

		ajaxchat_init: function() {
			var self = this;
			var doc=document;
			var msgEl=doc.getElementById("msg");
			var nameEl=doc.getElementById("myName");
			self.$name_input = $("#myName");
			self.bindOpenOnline();
			self.bindNameKP();
			self.bindMsgKP();
			self.bindClose();
			self.bindPopToggle();
			$(window).unload(function() { if(self.popOut) { self.sendWinCoord(); } });
			self.pingIM();
			self.ajaxchat_interval = setInterval(function() { self.pingIM(); },1000);
	<?php if(isset($_SESSION['open']) && $_SESSION['open'] == 1) { echo "self.openOnline();\n"; } ?>
			if(self.popOut) { self.initPopOut(); }
			if(msgEl && self.open) { msgEl.focus(); }
			if(nameEl && self.myName.length) { nameEl.value=self.myName; }
			self.reload_list();
		},
	
		bindNameKP: function() {
			var self = this;
			$("#myName").on('keypress',function(e) {
				self.im_kp(e);
			});
			$("#nameBtn").on('click',function() {
				self.updateName($(this).val());
			});
		},
	
		bindMsgKP: function() {
			var self = this;
			$("#msg").on('keypress',function(e) {
				self.msg_kp(e);
			});
		},
	
		bindOpenOnline: function() {
			var self = this;
			var $el = $("#open-online");
			$el.on('click',function() {
				self.openOnline();
			});
		},
	
		bindClose: function() {
			var self = this;
			var $el = $("#closeX");
			$el.on('click',function() {
				self.openOnline();
			});
		},
	
		bindPopToggle: function() {
			var self = this;
			var $el = $("#pop-toggle");
			$el.on('click',function() {
				self.popToggle();
			});
		},
	
		sendWinCoord: function() {
			var win=$("#ac_window");
			if(!win.is(':visible')) { win.show(); var pos=win.position(); win.hide(); }
			else { var pos=win.position(); }
			pos.top-=$(window).scrollTop();
			//Request must be synchronous to complete before page unloads.
			$.ajax("<?php echo plugins_url('/',__FILE__); ?>ajaxchat_xml.php?action=sendCoord&left="+pos.left+"&top="+pos.top,{async:false});
		},
	
		im_kp: function(e) {
			var self = this;
			if(!e) { e=window.event; }
			if(e.keyCode==13) { self.updateName(); }
		},
	
		reload_list: function() {
			var self = this;
			var doc=document;
			var count=doc.getElementById("online_count");
			var list=doc.getElementById("online_list");
			var listReq=self.getXML();
			listReq.onreadystatechange=function() {
				if(listReq.readyState==4 && listReq.status==200) {
					var data=listReq.responseText.split("::");
					var listData=data[1];
					var lines=listData.split("\n");
					for(var i=0;i<lines.length;i++) {
						if(lines[i].indexOf("<strong>")!=-1) {
							var nameStart=lines[i].indexOf("<strong>");
							var nameLen=lines[i].indexOf("</strong>")-(nameStart+8);
							var nameTmp=lines[i].substring(nameStart+8,(nameStart+8)+nameLen);
							if(nameTmp!=self.myName) {
								$("#myName").val(nameTmp);
								self.myName=nameTmp;
							}
						}
					}
					list.innerHTML=data[1];
					count.innerHTML=data[0];
				}
			}
			listReq.open("GET","<?php echo plugins_url('/',__FILE__); ?>ajaxchat_xml.php?action=online_list&t="+new Date().getTime());
			listReq.send(null);
		},
	
		initPopOut: function() {
			var self = this;
			var pos={<?php echo $_SESSION['winCoord'];?>};
			var win=$("#ac_window");
			win.draggable();
			win.height($("#ac_window_table").height());
			win.css('left',pos.left);
			win.css('top',pos.top);
			console.log("Self.open: "+self.open);
			if(self.open) { win.fadeTo(1000,0.9,function() { }); }
			$("#ac_window_title").css('cursor','move');
		},
	
		//Function to toggle popping the window in and out of position, ie: to be able to drag it around.
		popToggle: function() {
			var self = this;
			var win=$("#ac_window");
			var title=$("#ac_window_title");
			if(!self.popOut) {
				win.height($("#ac_window_table").height());
				win.draggable();
				win.animate({top: '-=30', left: '-=30'},1000,function() { });
				win.fadeTo(1000,0.9,function() { });
				title.css('cursor','move');
				self.popOut=true;
				$.ajax("<?php echo plugins_url('/',__FILE__); ?>ajaxchat_xml.php?action=popout&status=true", {async:true});
			}
			else {
				win.draggable('destroy');
				win.height($("#ac_window_table").height());
				var newLeft,newTop,mainWin;
				mainWin=$(window);
				newLeft=mainWin.width()-450;
				newTop=mainWin.height()-win.height()-$("#ajaxIM")[0].offsetHeight;
				win.animate({left: newLeft, top: newTop},1000,function() { });
				win.fadeTo(1000,1.0,function() { });
				title.css('cursor','default');
				self.popOut=false;
				$.ajax("<?php echo plugins_url('/',__FILE__); ?>ajaxchat_xml.php?action=popout&status=false", {async:true});
			}
		},
	
		openOnline: function() {
			//Toggle ajaxchat window.
			var self = this;
			var doc=document;
			self.open=!self.open;
			var openReq=self.getXML();
			openReq.open("GET","<?php echo plugins_url('/',__FILE__); ?>ajaxchat_xml.php?action=open&val="+(self.open?'1':'0')+"&t="+new Date().getTime());
			openReq.send(null);
			var jqEl=$("#ac_window");
			var mainWin=$(window);
			var el=doc.getElementById("ac_window");
			var disp=el.style.display;
			var messages=doc.getElementById("messages");
			if(disp=="none" || disp=="") {
				if(!self.popOut) {
					jqEl.css('left',mainWin.width()-450);
					jqEl.css('top',mainWin.height()-jqEl.height()-$("#ajaxIM")[0].offsetHeight);
				}
				el.style.display="block";
				jqEl.height($("#ac_window_table").height());
				if(self.popOut) { jqEl.fadeTo(1000,0.9,function() { }); }
				if(doc.getElementById("msg")) { doc.getElementById("msg").focus(); }
			}
			else { el.style.display="none"; }
			if(self.open) { messages.scrollTop=messages.scrollHeight; }
		
		},
	
		updateName: function() {
			var self = this;
			var doc=document;
			var updateReq=self.getXML();
			var new_name = self.$name_input.val();
			updateReq.onreadystatechange=function() {
				if(updateReq.readyState==4 && updateReq.status==200) {
					var updateResp=updateReq.responseText;
					if(updateResp.match("OK")) {
						var tmp=updateResp;
						tmp=tmp.split(":");
						self.$name_input.val(tmp[1]);
						self.myName=tmp[1];
						doc.getElementById("msg").focus();
						self.reload_list();
					}
					else {
						if(updateResp.match("Error: ")) {
							var error=updateResp.split("Error: ")[1];
							alert(error);
						}
						if(self.myName.length) { self.$name_input.val(self.myName); }
					}
				}
			}
			updateReq.open("GET","<?php echo plugins_url('/',__FILE__); ?>ajaxchat_xml.php?action=updateName&name="+new_name);
			updateReq.send(null);
		},
	
		blurName: function(el) {
			var self = this;
			if(self.myName.length) { el.value=self.myName; }
		},
	
		ajaxchat_closed: function() {
			var self = this;
			self.open=false;
			var closeReq=self.getXML();
			closeReq.open("GET","<?php echo plugins_url('/',__FILE__); ?>ajaxchat_xml.php?action=open&val=0",function() { return false; });
			closeReq.send(null);
		},
	
		ajaxchat_open: function() {
			var self = this;
			self.open=true;
			var openReq=self.getXML();
			openReq.open("GET","<?php echo plugins_url('/',__FILE__); ?>ajaxchat_xml.php?action=open&val=1",function() { return false; });
			openReq.send(null);
		},
	
		loadEl: function(el, url, cb) {
			if(!el || !url) { return false; }
			var req=self.getXML();
			req.onreadystatechange=function() {
				if(req.readyState==4 && req.status==200) {
					el.innerHTML=req.responseText;
					cb();
				}
			};
			req.open("GET",url);
			req.send(null);
		},
	
		getXML: function() {
			var httpreq;
		try { httpreq=new XMLHttpRequest(); } catch(err) { try { httpreq=new ActiveXObject("Microsoft.XMLHTTP"); } catch(err) { try { httpreq=new ActiveXObject("Msxml2.XMLHTTP"); } catch(err) { } } }
		if(!httpreq) { alert("XMLHttpRequest not supported"); return false; }
		return httpreq;
		},
	
		loadMessages: function(info) {
			if(info == "0") {
				return false;
			}
			var self = this;
			var doc=document;
			var maxid=0;
			var el=doc.getElementById("messages");
			el.innerHTML+=info;
			el.scrollTop=el.scrollHeight;
			//Just in case, check to make sure no duplicates in messages div
			for(var i=0;i<el.childNodes.length;i++) {
				var tId=parseInt(el.childNodes[i].id);
				var tEls=doc.getElementsByName(tId.toString());
				if(tEls.length>1) {
					for(var x=1;x<tEls.length;x++) {
						var tmpel=tEls[x];
						tmpel.parentNode.removeChild(tmpel);
					}
				}
				if(tId>maxid) { maxid=tId; }
			}
			self.lastID=maxid;
		},
	
		pingIM: function() {
			var self = this;
			self.uc++;
			if(self.uc==10) { self.reload_list(); self.uc=0; }
			if(self.messageReq) { return false; }
			self.messageReq=self.getXML();
			self.messageReq.onreadystatechange=function() {
				if(self.messageReq.readyState==4) { //Seperate block so we always clear messageReq when the request is finished, regardless of status
					if(self.messageReq.status==200) {
							var respTxt=self.messageReq.responseText;
							if(respTxt.length) { self.loadMessages(respTxt); }
					}
					self.messageReq=null;
				}
			}
			self.messageReq.open("GET","<?php echo plugins_url('/',__FILE__); ?>ajaxchat_ping.php?lastid="+self.lastID+"&t="+new Date().getTime());
			self.messageReq.send(null);
		},
	
		msg_kp: function(e) {
			var self = this;
			if(!e) { e=window.event; }
			var $el = $("#msg");
			var val = $el.val();
			if(e.keyCode==13 && val.length) {
				self.sendMsg(val);
				$el.val('');
			}
		},
	
		sendMsg: function(txt) {
			var self = this;
			var sendReq=self.getXML();
			sendReq.onreadystatechange=function() {
				if(sendReq.readyState==4 && sendReq.status==200) {
					if(sendReq.responseText.match("ERROR")) { alert("There was an error sending your message."+sendReq.responseText); return false; }
				}
			}
			var params="msg="+encodeURI(txt);
			params=params.replace("+","%2B"); //Replace any remaining plus signs, spaces should have been converted to %20, remaining plus signs are user inputted.
			sendReq.open("POST","<?php echo plugins_url('/',__FILE__); ?>/ajaxchat_xml.php?action=send",true);
			sendReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=ISO-8859-1");
			sendReq.setRequestHeader("Content-length", params.length);
			sendReq.setRequestHeader("Connection", "close");
			sendReq.send(params);
		}
	}
})(jQuery);
