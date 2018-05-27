<?php

/**
 *
 * Bearbeiten de Theme Dateien.
 * 
 * GET->file sagt, welche Datei bearbeitet werden soll.
 *
 * POST->[] wird gespeichert und danach nochmal ausgegeben.
 *
 */
 
require_once "../load.php";

/**
 *
 * Rekursives Auslesen eines Verzeichnisses
 * 
 */
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

echo "<div class=\"sp-content\">";
echo '<h3>' . $system->_t('welcome_to_theme_edit') . '</h3>';
echo '<p>' . $system->_t('theme_edit_description') . '</p>'; 

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
            <p><textarea name="filecontent" style="min-height:300px;resize:none;">' . $filecontent . '</textarea></p>
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
            <p><textarea name="filecontent" style="min-height:300px;resize:none;">' . $filecontent . '</textarea></p>
        </div>
        <input type="submit" name="save">
        <div style="clear:both;"></div>
    </form>';

} 

echo '</div>';
echo '<div class="sp-sidebar">';

$allfilesindir = getDirContents('../content/themes/'. $system->settings('site_theme') );

foreach($allfilesindir as $file) {

    echo "<a href='./?page=edit_file&filename=$file'>" . $file . "</a><hr>";
    
}

echo "</div>";

?>
