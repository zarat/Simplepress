<?php

/**
 * @author Manuel Zarat
 * 
 */

if(!isset($_GET['type'])) {
		echo "ERROR: missing GET param 'type'";
    exit();    
}

$posttype = $_GET['type'];

echo "<div class=\"content\">";

echo '<h3>' . $system->_t('item_add') . '</h3>';

if(!empty($_POST['title'])) { 

    $title = htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8');
    $keywords = htmlentities($_POST['keywords'], ENT_QUOTES, 'UTF-8');
    $description = htmlentities($_POST['description'], ENT_QUOTES, 'UTF-8');
    
    /** Der Text wurde vom CKEditor aufbereitet */
    $text = $_POST['text'];   
    $category = $_POST['category'];     
    $date = time();
    
    /** Save object */
    $cfg = array("insert"=>"object (type, title, content, description, keywords, status, category, date)","values"=>"('$posttype','$title', '$text', '$description', '$keywords', 1, $category, '$date')");
    $system->insert($cfg);
    $last = $system->last_insert_id();
    
    /** save custom fields */
    $custom_fields = array_combine($_POST['custom_field_key'], $_POST['custom_field_value']);
    $insertcfg = array();
    $insert = "object_meta (`meta_item_id`, `meta_key`, `meta_value`)";
    $values = "";
    $cc = count($custom_fields);
    $c = 1;
    foreach($custom_fields as $k => $v) {
        $values .= "($last, '$k', '$v')";
        if($c<$cc) {
            $values .= ",";
        }
        $c++;
    }
    $insertcfg['insert'] = $insert;
    $insertcfg['values'] = $values;
      
    echo "Dein Inhalt wurde gespeichert. Du kannst ihn <a href='../?type=$posttype&id=". $last . "'>hier ansehen</a> oder <a href='../admin/?page=item_modify&id=" . $last . "'>weiter bearbeiten</a>.";
	
} else {

$system->_t('item_add'); // add new item

echo "<form method=\"post\">";

echo '<p>' . $system->_t('item_add_title') . ' <a onclick="toggle(\'more\');" href="#">weitere Optionen</a></p>'; 
echo "<p><input name=\"title\" type=\"text\"></p>";

echo "<div id=\"more\" style=\"display:none;\">";
    
    echo '<p>' . $system->_t('item_add_category') . '</p>';
    echo "<p><select name=\"category\">";
    $cfg = array('select'=>'*','from'=>'object','where'=>'type="category"');
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

echo "</div>"; // close #more

echo '<p>' . $system->_t('item_add_content') . '</p>';
echo "<p><textarea cols=\"40\" rows=\"20\" name=\"text\" id=\"ckeditor\" class=\"ckeditor\"></textarea></p>";

$js ='<script>'."\n";
$js.='$(document).ready(function() {'."\n";
$js.='    var max_fields      = 10;'."\n";
$js.='    var wrapper         = $(".container1"); '."\n";
$js.='    var add_button      = $(".add_form_field"); '."\n";
$js.='    '."\n";
$js.='    var x = 1; '."\n";
$js.='    $(add_button).click(function(e){ '."\n";
$js.='        e.preventDefault();'."\n";
$js.='        if(x < max_fields){ '."\n";
$js.='            x++; '."\n";
$js.='            $(wrapper).append(\'<div><input type="text" name="custom_field_key[]"/> <input type="text" name="custom_field_value[]"/> <a href="#" class="delete">Delete</a></div>\'); //add input box'."\n";
$js.='        } else {'."\n";
$js.='		        alert(\'You Reached the limits\')'."\n";
$js.='		    }'."\n";
$js.='    });    '."\n";
$js.='    $(wrapper).on("click",".delete", function(e){ '."\n";
$js.='        e.preventDefault(); $(this).parent(\'div\').remove(); x--;'."\n";
$js.='    })'."\n";
$js.='});'."\n";
$js.='</script>';

echo $js;

?> 

<div> 

<p><a onclick="toggle('custom_fields'); " href="#">Custom fields</a></p>

<div id="custom_fields" style="display:none;">
<div class="container1">
    <a class="add_form_field"><span>Neues Feld</span></a>
    <div><input type="text" name="custom_field_key[]"> <input type="text" name="custom_field_value[]"><a href="#" class="delete">Delete</a></div>
</div>
</div>

<p><input type="submit" value="speichern"></p>

<div style="clear:both;"></div>

</form>

<?php } ?>

</div>

</div>