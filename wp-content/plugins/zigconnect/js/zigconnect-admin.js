

jQuery(document).ready(function(){
	jQuery('div.updated, div.error').click(function(){jQuery(this).slideUp();});
	jQuery('.zigconnect-metabox-searchbutton').click(function(){
		zc_conn_id = jQuery(this).attr('id'); // easy way to pass type into javascript so we can add it to element ids
		zc_conn_id = zc_conn_id.replace('zigconnect-metabox-searchbutton-', '');
		strQuery = jQuery('#zigconnect-metabox-search-' + zc_conn_id).val();
//		if (strQuery == '') {
//			jQuery('#zigconnect-metabox-searchresults-' + zc_conn_id).html('Nothing entered!');
//			return;
//		}
		jQuery('#zigconnect-metabox-searchresults-' + zc_conn_id).html('');
		jQuery('#zigconnect-metabox-loader-' + zc_conn_id).show();
		intThisPostID = jQuery('#zigconnect-metabox-search-postid-' + zc_conn_id).val();
		jQuery.get(location.href, {'zigaction' : 'zigconnect-ajax-search', 'strQuery' : strQuery, 'intThisPostID' : intThisPostID, 'zc_conn_id' : zc_conn_id}, function(objHTML){
			if (objHTML.toString().length == 0) {
				jQuery('#zigconnect-metabox-searchresults-' + zc_conn_id).html('Nothing found!');
			} else {
				jQuery('#zigconnect-metabox-searchresults-' + zc_conn_id).html(objHTML);
				jQuery('#zigconnect-metabox-addresult-' + zc_conn_id).click(function(){
					// do add here
					intOtherPostID = jQuery('#zigconnect-metabox-results-' + zc_conn_id).val();
					// here we add a further ajax call to get the TR markup for the other item, with extra TDs for fields etc.
					jQuery('#zigconnect-metabox-loader-' + zc_conn_id).show();
					jQuery.get(location.href, {'zigaction' : 'zigconnect-ajax-getrow', 'intThisPostID' : intThisPostID, 'intOtherPostID' : intOtherPostID, 'zc_conn_id' : zc_conn_id}, function(objHTML){
						// we then insert the markup at the end of the table
						jQuery('#zigconnect-metabox-table-' + zc_conn_id).append(objHTML);
						jQuery('#zigconnect-metabox-loader-' + zc_conn_id).hide();
						jQuery('#zigconnect-metabox-searchresults-' + zc_conn_id).html('');
					}, 'html');
				});
			}
			jQuery('#zigconnect-metabox-loader-' + zc_conn_id).hide();
			jQuery('#zigconnect-metabox-search-' + zc_conn_id).val('');
		}, 'html');
	});

	jQuery('.zigconnect-metabox-addallbutton').click(function(){
		zc_conn_id = jQuery(this).attr('id'); // easy way to pass type into javascript so we can add it to element ids
		zc_conn_id = zc_conn_id.replace('zigconnect-metabox-addallbutton-', '');
		intThisPostID = jQuery('#zigconnect-metabox-search-postid-' + zc_conn_id).val();
		jQuery('#zigconnect-metabox-loader-' + zc_conn_id).show();
		jQuery.get(location.href, {'zigaction' : 'zigconnect-ajax-addall', 'intThisPostID' : intThisPostID, 'zc_conn_id' : zc_conn_id}, function(objHTML){
			// we then insert the markup at the end of the table
			jQuery('#zigconnect-metabox-table-' + zc_conn_id).append(objHTML);
			jQuery('#zigconnect-metabox-loader-' + zc_conn_id).hide();
			jQuery('#zigconnect-metabox-addallbutton-' + zc_conn_id).hide();
		}, 'html');

	});

});

