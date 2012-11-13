<?php
header('Content-type: application/text');

header('Content-Disposition: attachment; filename="downloaded.srt"');

/*if ($_SERVER['PHP_SELF'] == "/wp/wp-admin/subtitle/download.php")
    die("Nereye birader?");*/
/*if (!current_user_can('manage_categories')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}*/

require_once('../../wp-load.php' );
global $wpdb;
if (isset($_REQUEST["pid"]) && $_REQUEST["pid"] > 0) {
    

    $pid=$_REQUEST["pid"];
    $sql = "SELECT `line`,`interval`,`text`,`texttr` FROM subtitle WHERE project_id=$pid";
    //echo $sql;
    $result = $wpdb->get_results($sql);
    if (!$result)
        echo "Olmadı!";
    foreach ($result as $row) {
        if (isset($_REQUEST["download"]) && $_REQUEST["download"] == 1)
            echo $row->line . "\n" . $row->interval . "" . $row->text . "\n";
        else
            echo $row->line . "\n" . $row->interval . "" . $row->texttr . "\n";
    }
}else
    die("Sometings is wrong!!")
?>