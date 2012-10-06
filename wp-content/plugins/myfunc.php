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

function get_related_posts_list($isPerson) {
    $zc = zc_get_linked_posts("post"); //(get_the_ID());
    $temp = array();
    $list = array();
    $i = 0;
    foreach ($zc as $postid) {
        $p = get_post($postid);
        //var_dump($p);
        $role = zc_get_linkdata($postid);
        $temp[$i]["link"] = "<a href='" . $p->guid . "'>" . $p->post_title . "</a>" . " as " . $role['role'];
        $temp[$i]["order"] = $role["order"];
        $i++;
    }
    if($isPerson){
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

?>
