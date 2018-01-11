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

if(!empty($_POST['title'])) { 

$title = htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8');
$keywords = $_POST['keywords'];
$description = htmlentities($_POST['description']);

//DONNO?!
$list = get_html_translation_table(HTML_ENTITIES);
unset($list['"']);
unset($list['&']);
$text = $_POST['text'];
$text = strtr($text, $list);
    
$category = $_POST['category']; 
    
$date = time();

$cfg = array("insert"=>"object (type, title, content, description, keywords, status, category, date)","values"=>"('$posttype','$title', '$text', '$description', '$keywords', 1, $category, '$date')");
$system->insert($cfg);
$last = $system->last_insert_id();

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

?> 

<script type="text/javascript" src="./js/jquery.js"></script>

<div> 
    
<form method="post">

<script type="text/javascript">
    function toggle(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
</script>

<p>Titel - <a onclick="toggle('more'); " href="#">weitere Optionen</a></p> 

<p><input name="title" type="text"></p>

<div id="more" style="display:none;">

<p>Kategorien:</p><p><select name="category">
<?php
$cfg = array('select'=>'*','from'=>'object','where'=>'type="category"');
$a = $system->archive($cfg);
for($i=0;$i<count($a);$i++) {
    if($result['category'] == $a[$i]['id']) {
        echo "<option value='" . $a[$i]['id'] . "' selected='selected'>" . $a[$i]['title'] . "</option>";
    } else {
        echo "<option value='" . $a[$i]['id'] . "'>" . $a[$i]['title'] . "</option>";
    }
}
?>
</select></p>
    

<p>Schlagworte:</p><p><input type="text" name="keywords" id="keywords"></p>

<p>Kurzbeschreibung:</p><p><input type="text" name="description" id="description" size="75" maxlength="200"></p>

</div>

<p><textarea cols="40" rows="20" name="text" id="ckeditor" class="ckeditor"></textarea></p>

<script>
$(document).ready(function() {
    var max_fields      = 10;
    var wrapper         = $(".container1"); 
    var add_button      = $(".add_form_field"); 
    
    var x = 1; 
    $(add_button).click(function(e){ 
        e.preventDefault();
        if(x < max_fields){ 
            x++; 
            $(wrapper).append('<div><input type="text" name="custom_field_key[]"/> <input type="text" name="custom_field_value[]"/> <a href="#" class="delete">Delete</a></div>'); //add input box
        }
		else
		{
		alert('You Reached the limits')
		}
    });
    
    $(wrapper).on("click",".delete", function(e){ 
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
</script>

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
    
</body>
</html>
