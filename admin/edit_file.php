<?php

/*
 *
 * Bearbeiten des Themes.
 * 
 * Parameter file sagt, welche Datei bearbeitet werden soll
 *
 */
 
require_once "../load.php";
?>

<div class="sp-content">

<h3>Bearbeite dein Design</h3>

<p>Ich empfehle, vorher eine Sicherungskopie zu machen ;) </p>

<br>

<?php 

function getDirContents($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
    
        $path = realpath($dir.DS.$value);
        
        if(!is_dir($path)) {
            $pi = pathinfo($path);
            $results[] = $pi['dirname'] . DIRECTORY_SEPARATOR . $pi['basename'];
            
        } else if($value != "." && $value != "..") {
        
            getDirContents($path, $results);
            //$results[] = $path;
            
        }
    }

    return $results;
}

$sett = $system->settings();

$aktuelles_theme = $sett['site_theme'];

$filepath = dirname('..' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $aktuelles_theme . DIRECTORY_SEPARATOR);

//default file = functions.php
$filename = "theme.php";

if(isset($_POST['filename']) && isset($_POST['filecontent'])) {

$filename = $_POST['filename'];  
$filecontent = $_POST['filecontent'];

// in Datei schreiben
$f = @fopen($filename, 'w');
if (!$f) {
        //return false;
} else {
        $bytes = fwrite($f, $filecontent);
        fclose($f);
        //return $bytes;
}

$filecontent = file_get_contents($filename);

?>

<form method="post">

<div style="min-height:300px;">
<input type="hidden" name="filename" value="<?php echo $filename; ?>">
<p><textarea name="filecontent" style="min-height:300px;resize:none;"><?php echo $filecontent; ?></textarea></p>
</div>

<input type="submit" name="save">

<div style="clear:both;"></div>

</form>

<?php

} else {

//$filepath = dirname('..' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $aktuelles_theme . DIRECTORY_SEPARATOR);
$filename = @$_GET['filename'];
if(empty($filename)) {
$get_filedir = ('..' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $aktuelles_theme . DIRECTORY_SEPARATOR);
$filename = $get_filedir . "theme.php";
}

$filecontent = file_get_contents($filename);

?>

<form method="post">

<div style="min-height:300px;">
<input type="text" name="filename" value="<?php echo $filename; ?>">
<p><textarea name="filecontent" style="min-height:300px;resize:none;"><?php echo $filecontent; ?></textarea></p>
</div>

<input type="submit" name="save">

<div style="clear:both;"></div>

</form>

<?php } ?>

</div>

<div class="sp-sidebar">
<?php
$allfilesindir = getDirContents('../content/themes/'. $system->settings('site_theme') );

foreach($allfilesindir as $file) {
        echo "<a href='./?page=edit_file&filename=$file'>" . $file . "</a><hr>";
}

?>
</div>
