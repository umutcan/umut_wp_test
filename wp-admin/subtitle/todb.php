<?php

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool


/** Loads the WordPress Environment and Template */
//require('./wp-blog-header.php');
if($_SERVER['PHP_SELF']=="/wp/wp-admin/subtitle/todb.php")
    die("Nereye birader?");
require_once('../wp-load.php' );
if ( !current_user_can( 'manage_categories' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
if(!isset($_REQUEST["project"])&& !isset($_REQUEST["ep"])&& !isset($_REQUEST["file"]))
    wp_die("Yeniden deneyiniz");
global $wpdb;
$srt=fopen("../wp-content/uploads/subtitles/".$_REQUEST["file"].".srt", "r");
if($srt){
    echo "OK<br>";
}else
    die("Shit");
$project=array("name"=>$_REQUEST["project"],"ep"=>$_REQUEST["ep"],"uid"=>  ','.get_current_user_id().','.$_REQUEST["uid"]);
if($wpdb->insert("subtitle_project",$project))
        $project_id=$wpdb->insert_id;
else
    die("Yeniden Deneyiniz. Proje Eklenemedi.");

$entry=array("line"=>0,"interval"=>'',"text"=>'',"project_id"=>$project_id);
$rcounter=0;
while($line=  fgets($srt)){
    if($entry["line"]==0)
        $i=$line;
    if($line==$i+1){
        //echo $entry."<br>";
        //var_dump($entry);
        if($_REQUEST["lang"]=="tr")
            $entry["texttr"]=$entry["text"];
        $wpdb->insert("subtitle",$entry);
        $i=$line;
        $entry["line"]=$line;
        $entry["text"]='';
        $rcounter=1;
    }
    else{
        if($rcounter==0)
            $entry["line"]=$line;
        if($rcounter==1)
            $entry["interval"]=$line;
        if($rcounter>=2)
            $entry["text"].=$line;
        $rcounter++;
    }
}
fclose($srt);
?>
