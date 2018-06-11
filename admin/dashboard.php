<?php

/**
 * 
 * Dashboard. 
 * 
 */

echo "<script type='text/javascript'>
    function chg(k,id) { 
        go_on = confirm('Diesen Inhalt wirklich entfernen?');
        if (go_on) { 
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open('GET','../admin/update_settings.php?setting='+k+'&value='+id,true);
            xmlhttp.send();
        }
    }
</script>";

/**
 * SP COntent Start
 */
echo "<div class=\"sp-content\">";

/**
 * SP COntent Item Start
 */
echo "<div class=\"sp-content-item\">";

/**
 * SP COntent Item Head
 */
echo "<div class=\"sp-content-item-head\">" . $system->_t('welcome_to_dashboard') . "</div>";

/**
 * SP COntent Item BodyStart
 */
echo "<div class=\"sp-content-item-body\">";

if (isset($_POST['submit'])) {

    if(!empty($_POST['site_title'])) {
        $new_sitename = htmlentities($_POST['site_title']);
        $cfg = array("table"=>"settings","set"=>"value='$new_sitename' WHERE settings.key = 'site_title'");
        $system->update($cfg);
    }    
    if(!empty($_POST['site_keywords'])) {
        $new_site_keywords = htmlentities($_POST['site_keywords']);
        $cfg = array("table"=>"settings","set"=>"value='$new_site_keywords' WHERE settings.key = 'site_keywords'");
        $system->update($cfg);
    }   
    if(!empty($_POST['site_description'])) {
        $new_site_description = htmlentities($_POST['site_description']);
        $cfg = array("table"=>"settings","set"=>"value='$new_site_description' WHERE settings.key = 'site_description'");
        $system->update($cfg);
    }    
    if(isset($_POST['site_subtitle'])) {
        $new_site_subtitle = htmlentities($_POST['site_subtitle']);
        $cfg = array("table"=>"settings","set"=>"value='$new_site_subtitle' WHERE settings.key = 'site_subtitle'");
        $system->update($cfg);
    }    
    if(isset($_POST['site_theme'])) {
        $new_site_theme = htmlentities($_POST['site_theme']);
        $cfg = array("table"=>"settings","set"=>"value='$new_site_theme' WHERE settings.key = 'site_theme'");
        $system->update($cfg);
    }    
    
    echo "<p>Konfiguration wurde erfolgreich gespeichert. <b>Weiterleitung..</b></p>\n";    
    echo "<script type=\"text/javascript\">function Redirect() {window.location = './index.php?page=dashboard';}setTimeout('Redirect()', 1000);</script>";

} else {

    echo "<form enctype=\"multipart/form-data\" method=\"post\">";
    echo '<p>' . $system->_t('change_website_title') . '</p>';
    echo "<p><input type=\"text\" name=\"site_title\" value=\"" . $system->settings('site_title') . "\"></p>";
    echo '<p>' . $system->_t('change_website_subtitle') . '</p>';
    echo "<p><input type=\"text\" name=\"site_subtitle\" value=\"" . $system->settings('site_subtitle') . "\"></p>";
    echo '<p>' . $system->_t('change_website_theme') . '</p>';
    echo "<p><select name=\"site_theme\" class=\"input\" onchange=\"chg('site_theme',this.value)\">";
    foreach($system->installed_themes() as $theme) {
        if($theme == $system->settings('site_theme')) {
            echo "<option value=\"$theme\" selected=\"selected\">$theme</option>";
        } else {
            echo "<option value=\"$theme\">$theme</option>";
        }
    }
    echo "</select></p>";
    echo '<p>' . $system->_t('change_website_keywords') . '</p>';
    echo "<p><input type=\"text\" name=\"site_keywords\" value=\"" . $system->settings('site_keywords') . "\"></p>";
    echo '<p>' . $system->_t('change_website_description') . '</p>';
    echo "<p><input type=\"text\" name=\"site_description\" value=\"" . $system->settings('site_description') . "\"></p>";
    echo "<p><input type=\"submit\" value=\"speichern\" name=\"submit\">";
    echo "</form>";

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

?>
