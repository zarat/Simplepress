<?php

include("../load.php");
include("auth.php");

$system = new system();

$setting = $_GET['setting'];
$value = $_GET['value'];

if($setting == "site_theme") {
    $cfg=array("table"=>"settings","set"=>"value='$value' WHERE settings.key='$setting'");
    $system->update($cfg);
    //$db->query("UPDATE settings SET value='$value' WHERE settings.key = '$setting'");
}

elseif($setting == "startpage") {      
    $cfg=array("table"=>"object","set"=>"startpage=0 WHERE startpage=1");
    $system->update($cfg);
    //$db->query("UPDATE object SET startpage=0 WHERE startpage=1");
    $cfg=array("table"=>"object","set"=>"startpage=1 WHERE id=$value");
    $system->update($cfg);
    //$db->query("UPDATE object SET startpage=1 WHERE id=$value");
}

elseif($setting == "item_status") {
    $cfg=array("table"=>"object","set"=>"status=$value");
    $system->update($cfg);
    //$db->query("UPDATE object SET status=$value");
}

?>