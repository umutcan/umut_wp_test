<?php
/**
 * @package Umut_Making
 * @version 1.6
 */
/*
  Plugin Name:Toplu Bölüm Ekleme
  Plugin URI: http://wordpress.org/extend/plugins/hello-dolly/
  Description: My custom functions...
  Author: Matt Mullenweg
  Version: 1.6
  Author URI: http://ma.tt/
 */

add_action( 'admin_menu', 'multi_add_menu' );

function multi_add_menu() {
	add_menu_page( 'Toplu Ekleme', 'Toplu Ekleme', 'manage_options', 'multi-add', 'multi_add_options' );
        
        
}

function multi_add_options() {
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        if (isset($_REQUEST["title"]) && isset($_REQUEST["main"])) {
        $maxep=0;
        $data = $_POST;
        $zc= new ZigConnect();
        $zcid=zc_get_connection_by_slug("episode");
        $zcfid=3;
        $episodes=array();
        $content='';
        echo $data["title"] . "<br>";
        echo $data["main"] . "<br>";
        for ($i = 0; $i < 20; $i++) {
            if ($data["epno-" . $i] > 0) {
                if($maxep>0){
                    if($maxep<$data["epno-" . $i])
                        $maxep=$data["epno-" . $i];
                }else{
                    $maxep=$data["epno-" . $i];
                }
                global $user_ID;
                $cat=get_category_by_slug("episode");
                $videoList=explode(",", $data["url-" . $i]);
                foreach ($videoList as $v) {
                    if(stripos($v,"vk.com")>0)
                            $content.="<iframe width='675' height='375' src='".$v."' ></iframe><br>";
                    else if(stripos($v,"viki.com")>0)
                            $content.="[video]".$v."[/video]<br>";
                    else
                            $content.="[video]".$v."[/video]<br>";
                }
                
                $new_post = array(
                    'post_title' => $data["title"].' Bolum '.$data["epno-" . $i],
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_author' => $user_ID,
                    'post_type' => 'post'
                    //'post_category' => array($cat->cat_ID)
                );
                
                $post_id = wp_insert_post($new_post);
                //wp_set_post_terms($post_id, "Bölüm",'category');
                wp_set_post_categories($post_id, array($cat->cat_ID));
                $thumbnail_id= get_post_thumbnail_id($data["main"]);
                set_post_thumbnail($post_id, $thumbnail_id);
                $episodes[$data["epno-" . $i]]=$post_id;
                $zc->SaveLink($zcid,$post_id,$data["main"]);
                //$zc->SaveLink($zcid,$data["main"],$post_id);
                $zc_link_id = $zc->GetLinkIDByPosts($post_id, $data["main"]);
                $zc->SaveData($zcfid, $zc_link_id, $data["epno-" . $i]);
               // $zc_link_id = $zc->GetLinkIDByPosts($zcfid,$data["main"],$post_id);
                //$zc->SaveData($zcfid, $zc_link_id, $data["epno-" . $i]);
                echo $data["epno-" . $i] . "->" . $data["url-" . $i] . "<br>";
                $content='';
            }
        }
        var_dump($episodes);
        for($i=2;$i<$maxep+1;$i++){
            if (isset($episodes[$i - 1]) && isset($episodes[$i])) {
                $zc->SaveLink($zcid, $episodes[$i], $episodes[$i - 1]);
                $zc_link_id = $zc->GetLinkIDByPosts($episodes[$i], $episodes[$i - 1]);
                $zc->SaveData($zcfid, $zc_link_id, "e" . ($i - 1));
            } else if(isset($episodes[$i])){
                echo "d";
                $zcpost= zc_get_linked_posts_of_post($data["main"],"post",$zcid);
                foreach ($zcpost as $value) {
                    $role = zc_get_linkdata($value,$zcid,$data["main"]);
                    if($role["epno"]==$i-1){
                        $status=get_post_status($value);
                        if($status=='publish'){
                            echo "<br>".$value;
                        $zc->SaveLink($zcid, $episodes[$i], $value);
                        $zc_link_id = $zc->GetLinkIDByPosts($episodes[$i], $value);
                        $zc->SaveData($zcfid, $zc_link_id, "e" . ($i - 1));
                        break;
                        }
                    }
                }
            }
        }
    } else {
        printForm();
    }
       
}

function printForm(){
    echo "<form action='".$_SERVER['PHP_SELF']."?page=multi-add' method='post' >";
    echo "Title:<input type='text' name='title' ></input><br>";
    echo "Main:<input type='text' name='main' ></input><br>";
    for($i=0;$i<20;$i++){
        
        echo "EpNo:<input type='text' name='epno-$i' ></input>";
        echo "Url:<input type='text' name='url-$i' ></input>";
        echo "<br>";
    }
    echo "<input type='submit'/>";
    echo "</form>";
}
?>
