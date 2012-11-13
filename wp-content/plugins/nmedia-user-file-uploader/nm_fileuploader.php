<?php
/*
Plugin Name: Nmedia File Uploader Plugin
Plugin URI: http://www.najeebmedia.com
Description: This Plugin is developed by NajeebMedia.com
Version: 2.1
Author: Najeeb Ahmad
Author URI: http://www.najeebmedia.com/
*/

/* ini_set('display_errors',1);
error_reporting(E_ALL); */


class nmFileUploader {
	
	var $fileupload_db_version = "2.0";
	
	/*
	data vars
	*/
	
	static $title;
	static $file_name;
	static $desc;
	static $file_type;
	static $file_size;
	static $pathUploads;
		
	
	static $tblName = 'userfiles';
	
	static $short_name = 'nm_file_uploader_pro';
	
	/*
	 ** pagination vars
	*/
	
	static $uploader_row_count;
	static $files_per_page = 5;
	static $total_pages;
	static $total_files;
	
	/**
	* constructor
	*/	 
	function nmFileUploader() {
		
		//$this -> loadJS();
	}
	
	

	function renderUserArea()
	{
		global $wpdb ;
		global $user_ID;
		global $current_user;
		get_currentuserinfo();
		
		
		if ( is_user_logged_in() ) { 
			ob_start();
	         nmFileUploader::renderForm();
			$output_string = ob_get_contents();
			ob_end_clean();
			return $output_string;
		}
		else
		{
		
			/*wp_redirect( home_url() ); exit;*/
			echo '<script type="text/javascript">
			window.location = "'.wp_login_url( get_permalink() ).'"
			</script>';
		}
		
	}
	
	/*
	This function is making directory in follownig path
	wp-content/uploads/user_uploads
	*/
	
	function makeUploadDirectory()
	{
		global $current_user;
		get_currentuserinfo();
		
		$upload_dir = wp_upload_dir();
		nmFileUploader::$pathUploads = $upload_dir['basedir'].'/subtitles/';
		

		if(!is_dir(nmFileUploader::$pathUploads))
		{
			if(mkdir(nmFileUploader::$pathUploads, 0777, true))
				return true;
			else
				return false;
		}
		else
		{
			return true;
		}
	}
	
	
	/*
	Getting file extension
	*/
	function getFileExtension($file_name)
	{
		//echo substr(strrchr($file_name,'.'),1);
	 	return substr(strrchr($file_name,'.'),1);
	}
	
	public function fileuploader_install() {
		global $wpdb;
		global $fileupload_db_version;
	
		$table_name = $wpdb->prefix . nmFileUploader::$tblName;
		  
		/* $sql = "CREATE TABLE `$table_name` 
				(`fileID` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`userID` INT( 7 ) NOT NULL ,
				`fileTitle` VARCHAR( 250 ) NULL ,
				`fileName` VARCHAR( 250 ) NULL ,
				`fileSize` INT( 15 ) NULL ,
				`fileDescription` MEDIUMTEXT NULL ,
				`fileType` VARCHAR( 15 ) NULL ,
				`fileUploadedOn` DATETIME NOT NULL )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"; */
		
		$sql = "CREATE TABLE `$table_name`
		(`fileID` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`userID` INT( 7 ) NOT NULL ,
		`fileTitle` VARCHAR( 250 ) NULL ,
		`fileDescription` MEDIUMTEXT NULL ,
		`fileName` VARCHAR( 250 ) NULL ,
		`fileSize` INT(12) NULL ,
		`fileType` VARCHAR( 15 ) NULL ,
		`fileFor` INT( 5 ) NULL ,
		`userRole` VARCHAR(200) NULL,
		`bucketName` 		VARCHAR( 250 ) 	NULL ,
		`fileMeta` VARCHAR(500) NULL,
		`fileUploadedOn` DATETIME NOT NULL )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
	
	   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	   dbDelta($sql);
	 
	   add_option("fileupload_db_version", $fileupload_db_version);
	}
	
	
	/*
	 * unistalling table
	*/
	
	function fileuploaderUninstall(){
	
		global $wpdb;
		global $fileupload_db_version;
	
	
		//Delete any options thats stored also?
		delete_option($fileupload_db_version);
		$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix . nmFileUploader::$tblName);
			
	}


	/*
	form uploader
	*/
	public function renderForm()
	{
		$file = dirname(__FILE__).'/upload-form.php';
		include($file);
	}
	
	
	/*
	Listing user files in admin
	*/
	public function renderListings()
	{
		$file = dirname(__FILE__).'/listings.php';
		include($file);
	}
	

  
  
	public function saveFile()
	{
		global $current_user, $wpdb;
		get_currentuserinfo();
		
		$user_name = '';
		$user_id = '';
		if(is_user_logged_in())
		{
			$user_name = $current_user -> user_nicename;
			$user_id = $current_user -> ID;
		}
		
		$upload_dir = wp_upload_dir();
		$filePath = $upload_dir['basedir'].'/subtitles/'.nmFileUploader::$file_name;
		
		
		$dt = array(	'fileName'			=> nmFileUploader::$file_name,
						'fileDescription'	=> nmFileUploader::$desc,
						'userID'			=> $user_id,
						'fileType'			=> nmFileUploader::$file_type,
						'fileSize'			=> '10Mb',//filesize($filePath),
						'fileUploadedOn'	=> current_time('mysql')
					 );
					 
					 
		//var_dump($dt);
		
		$wpdb -> insert($wpdb->prefix . nmFileUploader::$tblName,
						$dt, 
				array('%s', '%s', '%d', '%s', '%d', '%s')		
						);
		
		if($wpdb->insert_id)
			return true;
		else
			return false;
						
		//$wpdb->print_error(); 
	}
	
	/*
	deleting file
	*/
	public function deleteFile($fileid)
	{
		global $wpdb;
		global $current_user;
		get_currentuserinfo();
		
		
		$rsObj = $wpdb->get_row("SELECT fileName FROM ".$wpdb->prefix . nmFileUploader::$tblName." WHERE fileID = ".$fileid);
		$res = $wpdb->query(
						"
						DELETE FROM ".$wpdb->prefix . nmFileUploader::$tblName."
						WHERE fileID = $fileid"
					);
		if($res){
			$upPath=wp_upload_dir();
			$path=$upPath['basedir']."/user_uploads/".$current_user -> user_nicename.'/'.$rsObj->fileName;
			$del=@unlink($path);
		}
		
		//print_r($res);
		return $res;
	}
	
	
	/*
	** Get Files Detail
	*/
	
	function getUserFiles()
	{
		//echo "hello";
		global $wpdb;
		
		if(@$_REQUEST['user_id'] != '')
		{
			$userID = $_REQUEST['user_id'];
		}
		else
		{
			global $user_ID;
			$userID = $user_ID;
		}
		
		$myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix . nmFileUploader::$tblName."
						  			   where userID = $userID
									   ORDER BY fileUploadedOn DESC");
	   return $myrows;
	}
	
	
	/*
	** Get All Files Detail
	*/
	
	function getAllUserFiles()
	{
		global $wpdb;
		
		$myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix . nmFileUploader::$tblName."
						  			   ORDER BY fileUploadedOn DESC");
	   return $myrows;
	}
	
	
	function nm_user_upload_admin(){
	
		$user_id = $_REQUEST['user_id'];
	
		nmFileUploader::renderListings();	
	
	}
	
	
	
	/*
	** listing all files uploaded by users
	*/
	
	function listUserFiles()
	{
		$file = dirname(__FILE__).'/listings-all.php';
		include($file);	
	}
	
	
	/*
	 * upload file
	*/
	
	function uploadFile($username){
	
	
		$upload_dir = wp_upload_dir();
		$path_folder = $upload_dir['basedir'].'/subtitles/';
	
		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $path_folder;
			$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
			if(move_uploaded_file($tempFile,$targetFile)){
				echo '1';
			}
	
			else{
				echo 'Error in file uploading';
			}
		}
	}
	
	/*
	 ** making file name with URL
	*/
	function makeFileDownloadable($files, $filesize, $user_dir='', $date)
	{
		$arrFiles = explode(',', $files);
		$uploads = wp_upload_dir();
	
		$html = '';
		foreach($arrFiles as $f)
		{
			if(!file_exists($uploads['basedir'] . '/user_uploads/'. $f))
					$urlDownload = $uploads['baseurl'] . '/user_uploads/'.$user_dir.'/' . $f;
				else
					$urlDownload = $uploads['baseurl'] . '/user_uploads/'. $f;
			
			$html .= '<a href="'.$urlDownload.'" title="'.$f.'" class="nm-link-title" target="_blank">'.$f.' ('.nmFileUploader::sizeInKB($filesize).')</a>';
		}
	
		$html .= ' - <span class="nm-file-meta-more">'.nmFileUploader::time_difference($date).'</span>';
			
		return $html;
	}
	
	
	/*
	 * getting size in KBs
	 */
	function sizeInKB($size)
	{
		return round($size / 1024, 2) .' KiB';
	}
	
	
	/*
	 * time elapsed
	 */
	
	function time_difference($date)
	{
		if(empty($date)) {
			return "No date provided";
		}
	
		$periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths         = array("60","60","24","7","4.35","12","10");
	
		$now             = time();
		$unix_date         = strtotime($date);
	
		// check validity of date
		if(empty($unix_date)) {
			return "Bad date";
		}
	
		// is it future date or past date
		if($now > $unix_date) {
			$difference     = $now - $unix_date;
			$tense         = "ago";
	
		} else {
			$difference     = $unix_date - $now;
			$tense         = "from now";
		}
	
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
	
		$difference = round($difference);
	
		if($difference != 1) {
			$periods[$j].= "s";
		}
	
		return "$difference $periods[$j] {$tense}";
	}
	
	/*
	 ** to fix url re-occuring, written by Naseer sb
	*/
	
	function fixRequestURI($vars){
		$uri = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
		$parts = explode("?", $uri);
	
		$qsArr = array();
		if(isset($parts[1])){	////// query string present explode it
			$qsStr = explode("&", $parts[1]);
			foreach($qsStr as $qv){
				$p = explode("=",$qv);
				$qsArr[$p[0]] = $p[1];
			}
		}
	
		//////// updatig query string
		foreach($vars as $key=>$val){
			if($val==NULL) unset($qsArr[$key]); else $qsArr[$key]=$val;
		}
	
		////// rejoin query string
		$qsStr="";
		foreach($qsArr as $key=>$val){
			$qsStr.=$key."=".$val."&";
		}
		if($qsStr!="") $qsStr=substr($qsStr,0,strlen($qsStr)-1);
		$uri = $parts[0];
		if($qsStr!="") $uri.="?".$qsStr;
		return $uri;
	}
	
	
  
  
}

register_activation_hook(__FILE__, array('nmFileUploader','fileuploader_install'));
register_deactivation_hook(__FILE__, array('nmFileUploader','fileuploaderUninstall'));




function load_fileuploader_script() {
	/*wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', plugins_url('js/jquery-1.4.4.min.js', __FILE__));
	wp_enqueue_script( 'jquery' );*/
	
	wp_enqueue_script("jquery");
	
	wp_register_script('swfobject_script', plugins_url('js/uploadify/swfobject.js', __FILE__));
	wp_enqueue_script('swfobject_script');
	
	wp_register_script('uploadify_script', plugins_url('js/uploadify/jquery.uploadify.v2.1.4.min.js', __FILE__));
	wp_enqueue_script('uploadify_script');
	
	
     wp_register_script( 'fileuploader_custom_script', plugins_url('js/fileuploader_custom.js', __FILE__), 
	 					array('uploadify_script'));
	 wp_enqueue_script('fileuploader_custom_script');
	 
	 $nonce= wp_create_nonce  ('fileuploader-nonce');
	
	wp_enqueue_script( 'fileuploader_ajax', plugin_dir_url( __FILE__ ) . 'js/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'fileuploader_ajax', 'fileuploader_vars', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'fileuploader_token'	=> $nonce,
			'fileuploader_plugin_url' => plugin_dir_url( __FILE__ ),
			'current_user'	=> ''
	) );
}    

add_action('wp_enqueue_scripts', 'load_fileuploader_script');

add_shortcode( 'nm-wp-file-uploader', array('nmFileUploader', 'renderUserArea'));
add_action('wp_print_styles', 'nm_fileuploader_style');

//edit user action
add_action( 'edit_user_profile', array('nmFileUploader', 'nm_user_upload_admin'));
add_action( 'show_user_profile', array('nmFileUploader', 'nm_user_upload_admin'));

/*
 * ajax action callback to upload file
* defined in js/ajax.js
*/
add_action( 'wp_ajax_fileuploader_file', 'fileuploader_post_file' );
add_action( 'wp_ajax_nopriv_fileuploader_file', 'fileuploader_post_file' );
function fileuploader_post_file(){

	nmFileUploader::uploadFile($_REQUEST['username']);

	die(0);
}

/*
* Enqueue style-file, if it exists.
*/

function nm_fileuploader_style() {
	//uploadify css
	wp_register_style('fileuploader_stylesheet', plugins_url('js/uploadify/uploadify.css', __FILE__));
    wp_enqueue_style( 'fileuploader_stylesheet');
		
	wp_register_style('plugin_fileuploader_stylesheet', plugins_url('nm_fileuploader_style.css', __FILE__));
    wp_enqueue_style( 'plugin_fileuploader_stylesheet');    

    //loading tempalte style
	wp_register_style('_uploader_stylesheet', plugins_url('css/styles.css', __FILE__));
	wp_enqueue_style( '_uploader_stylesheet');
}

$options_file = dirname(__FILE__).'/file-upload-options.php';
include ($options_file);


?>