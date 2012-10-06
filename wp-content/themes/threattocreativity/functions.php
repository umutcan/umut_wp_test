<?php
if ( function_exists('register_sidebar') )
    register_sidebar();
?>
<?php function post_thumb() {
$files = get_children('post_parent='.get_the_ID().'&post_type=attachment&post_mime_type=image');
  if($files) :
    $keys = array_reverse(array_keys($files));
    $j=0;
    $num = $keys[$j];
    $image=wp_get_attachment_image($num, 'thumbnail', false);
    $imagepieces = explode('"', $image);
    $imagepath = $imagepieces[1];
    $thumb=wp_get_attachment_image($num, 'thumbnail', false);
    print "$thumb";
  endif;
}
?>