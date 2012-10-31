<?php

if(!isset($_POST['newusername'])){exit();}
$username=trim(addslashes($_POST['newusername']));

//set cookie
setcookie("livechatusername", $username, time()+86400,"/"); //24 hours

echo 'true';

?>
