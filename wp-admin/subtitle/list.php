<?php
//s
if($_SERVER['PHP_SELF']=="/wp/wp-admin/subtitle/load.php")
    die("Nereye birader?");
if ( !current_user_can( 'manage_subtitle' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        
require_once('../wp-load.php' );
global $wpdb;
echo "Projelerimiz <br> ";
?>
<a href="<?php echo $_SERVER['PHP_SELF']?>?page=sub-man&new=1">Yeni Ekle</a><br/>
<?php
$sql="SELECT id,name,ep,uid FROM subtitle_project LIMIT 0,10";
$result=$wpdb->get_results($sql);
if(!$result)
    echo "Olmadı!";
foreach ($result as $row) {
    $user_list=explode(",", $row->uid);
    //var_dump($user_list);
    if(in_array(get_current_user_id(), $user_list)|| get_current_user_id()==1){
    echo '<a href="'.$_SERVER['PHP_SELF'].'?page=sub-man&pid='.$row->id.'">';
    echo $row->name."(Bölüm ".$row->ep.")</a><br>";
    }
}
?>
