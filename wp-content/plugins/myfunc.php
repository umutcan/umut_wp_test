<?php

/**
 * @package Umut_Making
 * @version 1.6
 */
/*
  Plugin Name: My Custom Funcs
  Plugin URI: http://wordpress.org/extend/plugins/hello-dolly/
  Description: My custom functions...
  Author: Matt Mullenweg
  Version: 1.6
  Author URI: http://ma.tt/
 */
function get_hello_world() {
    echo "hello world!";
}

function get_related_posts_list($isShow) {
    $zcid=zc_get_connection_by_slug("cast");
    $zc = zc_get_linked_posts("post",$zcid); //(get_the_ID());
    $temp = array();
    $list = array();
    $i = 0;
    foreach ($zc as $postid) {
        $p = get_post($postid);
        //var_dump($p);
        $role = zc_get_linkdata($postid,$zcid);
        if(has_post_thumbnail($postid))
            $img=get_the_post_thumbnail($postid,array(100,100),array('class'=>'ftimg'));
        else
            $img="<img src='".get_template_directory_uri()."/images/slideshow/noftrdimg-222x160.jpg' height='100px' width='100px'/>";
        if($isShow)
            $temp[$i]["link"] = "<div class='ftimg'>".$img."</div><div><a href='" . $p->guid . "'>" . $p->post_title . "</a>" . " <br> (" . $role['role'].")</div>";
        else
            $temp[$i]["link"] = "<div class='ftimg'>".$img."</div><div style=' margin-top:30px;'><a href='" . $p->guid . "'>" . $p->post_title . "</a>" . " ( " . $role['role'].")</div>";
        $temp[$i]["order"] = $role["order"];
        $i++;
    }
    if($isShow){
    foreach ($temp as $row) {
        $list[$row["order"]] = $row["link"];
    }
    }
    else{
        $i=1;
        foreach ($temp as $row) {
            $list[$i]=$row["link"];
            $i++;
        }
    }
    return $list;

    ;
}

function get_episodes($isShow) {
    $zcid=zc_get_connection_by_slug("episode");
    $zc = zc_get_linked_posts("post",$zcid); //(get_the_ID());
    //var_dump($zc);
    $temp = array();
    $list = array();
    $i = 0;
    foreach ($zc as $postid) {
        $p = get_post($postid);
        //var_dump($p);
        $role = zc_get_linkdata($postid,$zcid);
  
        $temp[$i]["link"] = "<a href='" . $p->guid . "'>" . $p->post_title . "</a>" ;
        $temp[$i]["epno"] = $role["epno"];
        $i++;
    }
    if($isShow){
    foreach ($temp as $row) {

        $list[$row["epno"]] = $row["link"];
    }
    }
    else{
        $i=1;
        foreach ($temp as $row) {
            $list[]=array('link'=>$row["link"],'epno'=>$row["epno"]);
            
        }
    }
    return $list;

    
}

?>
