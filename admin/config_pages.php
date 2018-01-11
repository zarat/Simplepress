<script language="JavaScript">
function chg(k,id) {
	go_on = confirm("Diesen Inhalt wirklich entfernen?");
	if (go_on)
	{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET","../admin/update_settings.php?setting="+k+"&value="+id,true);
    xmlhttp.send();
	}
}
</script>

<div>

<h3>Systemeinstellungen</h3>

<p>Hier kannst du alle Einstellungen deines Systemes vornehmen.</p>

<br>

<?php 

$system = new system();
$site_settings = $system->settings();

if (isset($_POST['submit'])) {

if(!empty($_POST['site_name'])) {

$new_sitename = htmlentities($_POST['site_name']);
$cfg = array("table"=>"settings","set"=>"value='$new_sitename' WHERE settings.key = 'site_name'");
$system->update($cfg);
//$mysqli->query("UPDATE settings SET value='$new_sitename' WHERE settings.key = 'site_name'");

}

if(!empty($_POST['site_keywords'])) {

$new_site_keywords = htmlentities($_POST['site_keywords']);
$cfg = array("table"=>"settings","set"=>"value='$new_site_keywords' WHERE settings.key = 'site_keywords'");
$system->update($cfg);
//$mysqli->query("UPDATE settings SET value='$new_site_keywords' WHERE settings.key = 'site_keywords'");

}

if(!empty($_POST['site_description'])) {

$new_site_description = htmlentities($_POST['site_description']);
$cfg = array("table"=>"settings","set"=>"value='$new_site_description' WHERE settings.key = 'site_description'");
$system->update($cfg);
//$mysqli->query("UPDATE settings SET value='$new_site_description' WHERE settings.key = 'site_description'");

}

if(isset($_POST['site_subtitle'])) {

$new_site_subtitle = htmlentities($_POST['site_subtitle']);
$cfg = array("table"=>"settings","set"=>"value='$new_site_subtitle' WHERE settings.key = 'site_subtitle'");
$system->update($cfg);
//$mysqli->query("UPDATE settings SET value='$new_site_subtitle' WHERE settings.key = 'site_subtitle'");

}

if(isset($_POST['site_theme'])) {

$new_site_theme = htmlentities($_POST['site_theme']);
$cfg = array("table"=>"settings","set"=>"value='$new_site_theme' WHERE settings.key = 'site_theme'");
$system->update($cfg);
//$mysqli->query("UPDATE settings SET value='$new_site_theme' WHERE settings.key = 'site_theme'");

}


echo "<p>Konfiguration wurde erfolgreich gespeichert. <b>Weiterleitung..</b></p>\n";

?>

<script type="text/javascript">
function Redirect()
{
    window.location = './index.php?page=config_pages';
}
setTimeout('Redirect()', 1000);
</script>

<?php

}else{

/*
$db1 = new connection();
$query ="SELECT * FROM object WHERE startpage = 1;";
$result = $db1->query($query);
$row = $db1->fetch_array($result);
$startpage = $row['startpage'];
$title = $row['title'];
$description = $row['description'];
$keywords = $row['keywords'];
*/

/*
$cfg = array('select' => '*', 'from' => 'object', 'where' => 'startpage=1');
if($s = $system->archive($cfg)) {
    foreach($s as $item) {
        print_r($item);
    }
}
*/

?>

<form enctype="multipart/form-data" method="post">

<p>Name deiner Seite</p>
<p><input type="text" name="site_name" value="<?php echo $site_settings['site_name']; ?>"></p>

<p>Slagline deiner Seite</p>
<p><input type="text" name="site_subtitle" value="<?php echo $site_settings['site_subtitle']; ?>"></p>

<p>Design w&auml;hlen</p>

<p><select name="site_theme" class="input" onchange="chg('site_theme',this.value)">

<?php

$themes = $system->installed_themes();
$aktuelles_theme = $site_settings['site_theme'];

foreach($themes as $theme) {

if($theme == $aktuelles_theme) {
print("<option value=\"$theme\" selected=\"selected\">$theme</option>");
} else {
print("<option value=\"$theme\">$theme</option>");
}


}		    
?>
</select>
</p>

<p>Allgemeine SEO Keywords</p>
<p><input type="text" name="site_keywords" value="<?php echo $site_settings['site_keywords']; ?>"></p>

<p>Allgemeine SEO Beschreibung</p>
<p><input type="text" name="site_description" value="<?php echo $site_settings['site_description']; ?>"></p>

<p><input type="submit" value="speichern" name="submit"></p>

<div style="clear:both;"></div>

</form>

<?php } ?>

</div>

</body>
</html>
