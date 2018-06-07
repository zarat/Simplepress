<?php

/**
 * @author Manuel Zarat
 */

if( !@$_GET['type'] && !@$_POST['title'] ) { die("sorry, wrong query."); }

$posttype = $_GET['type'];

echo "<link rel=\"stylesheet\" href=\"../admin/css/datepicker.css\">\n";
echo "<link href=\"../admin/css/suneditor.css\" rel=\"stylesheet\" type=\"text/css\">\n";
echo "<script type=\"text/javascript\" src=\"../admin/js/suneditor.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"../admin/js/datepicker.js\"></script>\n";

/**
 * SP Content Anfang
 */
echo "<div class=\"sp-content\">";

echo '<h3>' . $system->_t('item_add') . '</h3>';

if(!empty($_POST['title'])) { 

    $title = htmlentities($_POST['title']);
    $keywords = htmlentities($_POST['keywords']);
    $description = htmlentities($_POST['description']);
    $text = $_POST['text'];   
    $category = $_POST['category'];         
    $date = !empty($_POST['date']) ? strtotime(str_replace(".", "-", $_POST['date'])) : time();
        
    $cfg = array("insert"=>"item (type, title, content, description, keywords, status, category, date)","values"=>"('$posttype','$title', '$text', '$description', '$keywords', 1, $category, '$date')");
    $system->insert($cfg);
    
    $last = $system->last_insert_id();
      
    echo "Dein Inhalt wurde gespeichert. Du kannst ihn <a href='../?type=$posttype&id=". $last . "'>hier ansehen</a> oder <a href='../admin/?page=item_modify&id=" . $last . "'>weiter bearbeiten</a>.";
	
} else {

    /**
     * FORM Anfang
     */
    echo "<form id=\"frm\" method=\"post\">";
    
    echo '<p>' . $system->_t('item_add_title') . ' <a onclick="toggle(\'more\');" href="#">weitere Optionen</a></p>'; 
    echo "<p><input name=\"title\" type=\"text\"></p>";
    
    /**
     * DIV More Anfang
     */
    echo "<div id=\"more\" style=\"display:none;\">";
    
        echo '<p>' . $system->_t('item_modify_date') . '</p>'; 
        echo "<p><input type=\"text\" name=\"date\" class=\"datepicker\"></p>";
        
        echo '<p>' . $system->_t('item_add_category') . '</p>';
        echo "<p><select name=\"category\">";
        $cfg = array('select'=>'*','from'=>'item','where'=>'type="category"');
        $a = $system->archive($cfg);
        for($i=0;$i<count($a);$i++) {
            if($result['category'] == $a[$i]['id']) {
                echo "<option value='" . $a[$i]['id'] . "' selected='selected'>" . $a[$i]['title'] . "</option>";
            } else {
                echo "<option value='" . $a[$i]['id'] . "'>" . $a[$i]['title'] . "</option>";
            }
        }
        echo "</select></p>";
        
        echo '<p>' . $system->_t('item_add_keywords') . '</p>'; 
        echo "<p><input name=\"keywords\" type=\"text\"></p>";
        
        echo '<p>' . $system->_t('item_add_description') . '</p>'; 
        echo "<p><input name=\"description\" type=\"text\"></p>";
    
    /**
     * DIV More Ende
     */
    echo "</div>";
    
    echo '<p>' . $system->_t('item_add_content') . '</p>';
    echo "<p><textarea cols=\"40\" rows=\"20\" name=\"text\" id=\"editor\"></textarea></p>";
    
    echo "<p><a style=\"cursor:pointer;\" onclick=\"sun_save();\">Item speichern</a></p>";
    
    /**
     * FORM Ende
     */
    echo "</form>";

}

/**
 * SP Content Ende
 */
echo "</div>";

echo "<div style=\"clear:both;\"></div>";

?> 
<script>
var suneditor = SUNEDITOR.create('editor', {
    //imageUploadUrl:"upload.php"
});
document.getElementById("datepicker").datepicker();
function sun_save() {
    suneditor.save();
    document.getElementById('frm').submit();
};
</script>
