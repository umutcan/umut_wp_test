<?php

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool


/** Loads the WordPress Environment and Template */
//require('./wp-blog-header.php');
require_once( dirname(__FILE__) . '/../wp-load.php' );
if(!isset($_REQUEST["project"])&& !isset($_REQUEST["ep"])&& !isset($_REQUEST["file"]))
    die("Yeniden deneyiniz");

$srt=fopen("../wp-content/uploads/subtitles/".$_REQUEST["file"].".srt", "r");
if($srt){
    echo "OK<br>";
}else
    die("Shit");
$project=array("name"=>$_REQUEST["project"],"ep"=>$_REQUEST["ep"]);
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
