<?php
//s
if($_SERVER['PHP_SELF']=="/wp/wp-admin/subtitle/load.php")
    die("Nereye birader?");
if ( !current_user_can( 'read' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        
require_once('wp-load.php' );
global $wpdb;
echo "Projelerimiz <br> ";
?>

<?php
$sql="SELECT id,name,ep FROM subtitle_project LIMIT 0,10";
$result=$wpdb->get_results($sql);
if(!$result)
    echo "Olmadı!";
foreach ($result as $row) {
    echo '<a href="'.  get_permalink().'?pid='.$row->id.'">';
    echo $row->name."(Bölüm ".$row->ep.")</a><br>";
}
?>
