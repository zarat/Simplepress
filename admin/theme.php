<?php

/**
 * @author Manuel Zarat
 */
 
include "header.php";   

$filename = false;   

?>

<div class="sp-content">
<div class="sp-content-item">
<div class="sp-content-item-head"><?php echo $system->_t('theme_manager'); ?></div>
<div class="sp-content-item-head-secondary"><?php echo $system->_t('theme_manager_description'); ?></div>
<div class="sp-content-item-body">


<?php

/**
 * Datei speichern
 */
if(isset($_POST['filename']) && isset($_POST['filecontent'])) {
    $filename = $_POST['filename'];  
    $filecontent = $_POST['filecontent'];
    $f = @fopen($filename, 'w');
    if ($f) {
        $bytes = fwrite($f, $filecontent);
        fclose($f);
    }    
    echo "Datei wurde gespeichert.";
} 

$filename = @$_GET['filename'];

/**
 * Wenn kein Dateiname dann theme.php anzeigen
 */
if( !$filename ) {
    $filename = realpath(THEME_DIR . $system->settings('site_theme') . DS . "theme.php");
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

?>

</div>
</div>
</div>

<div class="sp-sidebar">

<div class="sp-sidebar-item">

<?php

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

$allfilesindir = getDirContents('../content/themes/'. $system->settings('site_theme') );
foreach($allfilesindir as $file) {
    echo "<a href='../admin/theme.php?filename=$file'>" . $file . "</a><hr>";    
}

?>

</div>
</div>

<?php include "footer.php"; ?>
