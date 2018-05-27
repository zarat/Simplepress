<?php

/*
 *
 * Bearbeiten des Stylesheets.
 *
 */
?>

<div>

<h3>Bearbeite dein Design</h3>

<p>Ich empfehle, vorher eine Sicherungskopie zu machen ;) </p>

<br>

<?php 

$sett = $system->settings();

$aktuelles_theme = $sett['site_theme'];

if(isset($_POST['css'])) {

$filename = '../content/themes/' . $aktuelles_theme . '/css/style.css';  

$new_css = $_POST['css'];

        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $new_css);
            fclose($f);
            return $bytes;
        }

$the_css = file_get_contents('../content/themes/' . $aktuelles_theme . '/css/style.css');

?>

<form method="post">

<div style="min-height:300px;"><p><textarea name="css" style="min-height:300px;resize:none;"><?php echo $the_css; ?></textarea></p></div>

<input type="submit" name="save">

<div style="clear:both;"></div>

</form>

<?php

} else {

$the_css = file_get_contents('../content/themes/' . $aktuelles_theme . '/css/style.css');

?>

<form method="post">

<div style="min-height:300px;"><p><textarea name="css" style="min-height:300px;resize:none;"><?php echo $the_css; ?></textarea></p></div>

<input type="submit" name="save">

<div style="clear:both;"></div>

</form>

<?php } ?>

</div>
