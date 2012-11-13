<?php
global $current_user;
get_currentuserinfo();

	
nmFileUploader::makeUploadDirectory();

/*
 delete file
*/
if(isset($_GET['fid']))
{
	if(nmFileUploader::deleteFile(intval($_GET['fid'])))
	{
		echo "<div class=\"red\">". get_option('nm_file_deleted_msg') ."</div>";
	}

}

/*$wpdb->show_errors();
 $wpdb->print_error(); */

/*
 save file
*/
if(isset($_POST['nm-submit']))
{
	nmFileUploader::$file_name 	= sanitize_text_field($_POST['file-name']);
	nmFileUploader::$desc		= sanitize_text_field($_POST['description']);
	nmFileUploader::$file_type	= "." . nmFileUploader::getFileExtension(sanitize_text_field($_POST['file-name']));

	if(nmFileUploader::saveFile())
	{
		echo "<div class=\"green\">". get_option('nm_file_uploaded_msg') ."</div>";
	}

}
?>



<div id="nm-upload-container">
<div id="error"></div>
<form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])?>" method="post" onSubmit="return validate()">
<input type="hidden" name="file-name" id="file-name">


<p class="nm-uploader-area">

	<span><?php _e('Select file(s) to upload, once files uploaded click on Save button below', 'nm_file_uploader_pro')?></span>
  <input id="file_upload" name="file_upload" type="file" />
  <span id="upload-response"></span>
</p>


<ul class="nm-file-meta">
	<li class="caption">File description</li>
    <li class="inputs">
    <ul class="desc">
      		<li>
            	<textarea name="description" style="width:90%;height:70px" class="" id="description"></textarea>
            </li>
      	
            
    </ul>
    </li>
</ul>
 
<ul class="nm-file-meta">
	<li class="caption">&nbsp;</li>
    <li class="inputs">
    <input class="nm-submit-button" type="submit" value="<?php _e('Save', 'nm_file_uploader_pro')?>" name="nm-submit" id="nm-upload">
    <div id="working-area" style="display:none">
		<?php
			echo "<img src=".plugins_url( 'images/loading.gif' , __FILE__)." />";
		?>
    </div>	
    </li>
</ul>

</form>
</div>

<script type="text/javascript">
		fileuploader_vars.current_user = '<?php echo $current_user -> user_nicename?>';
		setupUploader();
</script>


<?php
//file list
//check if displayFiles is True
	$list_path = dirname(__FILE__).'/_template_uploader.php';
	include ($list_path);

?>