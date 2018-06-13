<?php

/**
 * @author Manuel Zarat
 */
 
include "header.php";

function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);
    foreach($files as $key => $value){    
        $path = realpath($dir . DS . $value);
        /* Dont add directories */        
        if(!is_dir($path)) {        
            $pi = pathinfo($path);
            $results[] = $pi['dirname'] . DS . $pi['basename'];            
        } else if($value != "." && $value != "..") {        
            getDirContents($path, $results);            
        }        
    }
    return $results;    
}       

/**
 * SP Content Start
 */
echo "<div class=\"sp-content\">";

/**
 * SP Content Item Start
 */
echo "<div class=\"sp-content-item\">";

/**
 * SP Content Item Head 
 */
echo "<div class=\"sp-content-item-head\">" . $system->_t('theme_manager') . "</div>"; 
echo "<div class=\"sp-content-item-head-secondary\">" . $system->_t('theme_manager_description') . "</div>";

/**
 * SP Content Item Body Start
 */
echo "<div class=\"sp-content-item-body\">";

$aktuelles_theme = $system->settings('site_theme');
$filepath = dirname('..' . DS . 'content' . DS . 'themes' . DS . $aktuelles_theme . DS);
$filename = "theme.php";

if(isset($_POST['filename']) && isset($_POST['filecontent'])) {

    $filename = $_POST['filename'];  
    $filecontent = $_POST['filecontent'];
    
    $f = @fopen($filename, 'w');
    if (!$f) {
            // @ToDo
    } else {
            $bytes = fwrite($f, $filecontent);
            fclose($f);
    }
    
    $filecontent = file_get_contents($filename);
    
    echo '<form method="post">
        <div style="min-height:300px;">
            <input type="hidden" name="filename" value="' . $filename . '">
            <p><textarea name="filecontent" rows="20" cols="40" style="overflow-y:scroll;white-space: pre">'.($filecontent).'</textarea></p>
        </div>
        <input type="submit" name="save">
        <div style="clear:both;"></div>
    </form>';

} else {

    $filename = @$_GET['filename'];
    
    if(empty($filename)) {
        $get_filedir = ('..' . DS . 'content' . DS . 'themes' . DS . $aktuelles_theme . DS);
        $filename = realpath($get_filedir . "theme.php");
    }
    
    $filecontent = file_get_contents($filename);
    
    echo '<form method="post">
        <div style="min-height:300px;">
            <input type="text" name="filename" value="' . $filename . '">
            <p><textarea name="filecontent" rows="20" cols="40" style="overflow-y:scroll;white-space: pre">'.($filecontent).'</textarea></p>
        </div>
        <input type="submit" name="save">
        <div style="clear:both;"></div>
    </form>';

} 

/**
 * SP Content Item Body Ende
 */
echo "</div>";

/**
 * SP Content Item Ende
 */
echo "</div>";  

/**
 * SP Content Ende
 */
echo "</div>";

/**
 * Sidebar Anfang
 */
echo '<div class="sp-sidebar">';

/**
 * Sidebar Item Anfang
 */
echo '<div class="sp-sidebar-item">';

$allfilesindir = getDirContents('../content/themes/'. $system->settings('site_theme') );

foreach($allfilesindir as $file) {

    echo "<a href='../admin/theme.php?filename=$file'>" . $file . "</a><hr>";
    
}

/**
 * Sidebar Item Ende
 */
echo "</div>";

/**
 * Sidebar Ende
 */
echo "</div>";

include "footer.php";

?>
