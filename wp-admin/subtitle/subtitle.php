<style type="text/css">
    .revoptions{
        /*float:left;
        width: auto;*/
        margin-bottom: 5px;
        background-color: #C3C3C3;
    }
    
    .revoptions:hover{
        /*float:left;
        width: auto;*/
        margin-bottom: 5px;
        background-color: #8A7C60;
    }
    .revisions{
        float:left;
        width: 550px;
        
    }
    .sline{
        float:left;
        width: 550px;
        
    }
    
    .frm{
        float:left;
        width: 550px;
        
    }
</style>
 <?
 /*wp_enqueue_script('jquery-ui', 
                     'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', 
                     array('jquery'), 
                     '1.8.16');*/
 ?>
<script>
function selectRev(line,rev){
    var input=document.getElementById(line);
    var rev=document.getElementById(line+"_"+rev);
    input.value=rev.innerHTML;
}

function revToggle(){
    jQuery('.revisions').toggle();
}

/*jQuery(document).ready(function(){
  jQuery("#revtog").click(function(){
    jQuery("#revisions").toggle();
  });
});*/
</script>
<?php
//s
if($_SERVER['PHP_SELF']=="/wp/wp-admin/subtitle/load.php")
    die("Nereye birader?");
if ( !current_user_can( 'manage_subtitle' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        
require_once('../wp-load.php' );
global $wpdb;

?>
<a href="<?php echo $_SERVER['PHP_SELF']?>?page=sub-man">Proje  Sayfasına Dön</a><br/>
<br/>

<?php
if(isset ($_REQUEST["pid"])&& $_REQUEST["pid"]>0) {
    
    $pid=$_REQUEST["pid"];
    if(isset ($_REQUEST["paging"])&& $_REQUEST["paging"]>0)
        $page=$_REQUEST["paging"];
    else
        $page=0;
    if(count($_POST)>0){
        foreach ($_POST as $key=>$value){
            $value=wp_filter_nohtml_kses($value);
            if($value!=NULL){
                $sql="SELECT `line`,`interval`,`text`,`texttr`,rev,project_id FROM subtitle WHERE project_id=$pid AND `line`=$key";
                $rev_info=$wpdb->get_row($sql,ARRAY_A);
                if($value!=$rev_info["texttr"]){
                $rev_info["rev"]=$rev_info["rev"]+1;
                $rev_info["uid"]=get_current_user_id();
                $wpdb->insert("subtitle_revisions",$rev_info);
                
                if($wpdb->update("subtitle",array("texttr"=>$value,"rev"=>$rev_info["rev"]),array("project_id"=>$pid,"line"=>$key)))
                        echo $key."->".stripslashes ($value)."<br/>";
                else
                    echo "";
                }
            }
        }
    }
        
    $sql="SELECT count(*) as cnt FROM subtitle WHERE project_id=$pid ";
    $count=$wpdb->get_results($sql);
    
    echo "< <a href='".$_SERVER['PHP_SELF']."?page=sub-man&pid=$pid&paging="
            .($page>0?$page-1:$page)."'>Önceki Sayfa</a> -";
    echo " <a href='".$_SERVER['PHP_SELF']."?page=sub-man&pid=$pid&paging="
            .(($page+1)*100<$count[0]->cnt?$page+1:$page)."'>Sonraki Sayfa</a> ><br/> ";
    
    echo " <a href='".$_SERVER['PHP_SELF']."?page=sub-man&download=1&pid=$pid'>İndir(EN)</a> ><br/> ";
    echo " <a href='".$_SERVER['PHP_SELF']."?page=sub-man&download=2&pid=$pid'>İndir(TR)</a> ><br/> ";
    $limit="LIMIT ".($page*100).",100";
    $sql="SELECT `line`,`interval`,`text`,`texttr` FROM subtitle WHERE project_id=$pid $limit";
    $result=$wpdb->get_results($sql);
    if(!$result)
        echo "Olmadı!";
    echo "<form class='frm' action='".$_SERVER['PHP_SELF']."?page=sub-man&pid=$pid&paging=$page' method='post' >";
    echo "<input type='submit' /><input type='button' id='revtog' value='Hide/Show Revs' onclick='revToggle()'/><br/>";
    foreach ($result as $row) {
        echo "<div class='sline'>";
        echo $row->line."<br>".$row->text."<br>".$row->texttr."<br>";
        echo "<textarea id='$row->line' name='$row->line' rows='1' cols='150' >".stripslashes($row->texttr)."</textarea><br>";
        echo "<div class='revisions'><ul>";
        $rev=get_revisions($pid, $row->line);
        //var_dump($rev);
        foreach($rev as $r)
            echo "<li id=".$row->line."_".$r->rev." class='revoptions' onclick='selectRev(".$row->line.",".$r->rev.")'>".stripslashes($r->texttr)."</li>";
        echo "</ul></div><br>";
        echo "</div>";
        
    }
    echo "<input type='submit' >Gönder</input><br/>";
    echo "</form>";
}

function get_revisions($pid,$line){
    global $wpdb;

    $sql="SELECT `texttr`,rev FROM subtitle_revisions WHERE project_id=$pid AND `line`=$line ORDER BY rev DESC LIMIT 10";
    $rev=$wpdb->get_results($sql);
    
    return $rev;
}
?>