tinyMCEPopup.requireLangPack();

var insertMVTLink = {
	init : function() {
		var f = document.forms[0];

		f.mvtvideourl.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		var regex = new RegExp("(http.*)\\[.*");
		var match = regex.exec(f.mvtvideourl.value);
		
		if (match != null) {
			f.mvtvideourl.value = match[1];
		}
		
		f.mvtvideowidth = document.getElementById('mvtvideowidth').value;
		f.mvtvideoheight = document.getElementById('mvtvideoheight').value;
	
	},

	insert : function() {
		var f = document.forms[0];
		var options = "";
		
		if (f.mvtvideowidth.value != "") {
			options += " w=\"" + f.mvtvideowidth.value + "\"";
		}
		
		if (f.mvtvideoheight.value != "") {
			options += " h=\"" + f.mvtvideoheight.value + "\"";
		}
		
		var result = "[video" + options + "]" + f.mvtvideourl.value + "[/video]";

		tinyMCEPopup.editor.execCommand('mceInsertContent', false, result);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(insertMVTLink.init, insertMVTLink);
