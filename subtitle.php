<?php

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
//require('./wp-blog-header.php');
require_once( dirname(__FILE__) . '/wp-load.php' );
$srt=fopen("wp-content/uploads/subtitles/zeitgeist_1.srt", "r");
if($srt){
    echo "OK<br>";
}else
    die("Shit");
$entry=array("line"=>0,"interval"=>'',"text"=>'');
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
            $entry["text"].="ŞiüğIı".$line;
        $rcounter++;
    }
}
fclose($srt);
?>
