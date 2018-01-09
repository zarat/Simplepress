<div>

<?php

$posttype = $_GET['type'];

echo "<h3>" . $posttype . " anlegen</h3>";

if(!empty($_POST['title'])) { 

$title = htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8');
$keywords = $_POST['keywords'];
$description = htmlentities($_POST['description']);

//DONNO?!
$list = get_html_translation_table(HTML_ENTITIES);
unset($list['"']);
//unset($list['<']);
//unset($list['>']);
unset($list['&']);

$text = $_POST['text'];
$text = strtr($text, $list);
    
$category = $_POST['category'];

/**
 * Custom fields
 * 
 * $custom_fields = array_combine($_POST['custom_field_key'], $_POST['custom_field_value']); 
 */

    
$date = time();

//$db = new connection();
			
//$query = "INSERT INTO object (type, title, content, description, keywords, status, category, date) VALUES ('$posttype','$title', '$text', '$description', '$keywords', 1, $category, '$date')";

$cfg = array("insert"=>"object (type, title, content, description, keywords, status, category, date)","values"=>"('$posttype','$title', '$text', '$description', '$keywords', 1, $category, '$date')");
$system->insert($cfg);
$last = $system->last_insert_id();

//$db->query($query);
  
//$ff = $db->last_insert_id();
  
echo "Dein Inhalt wurde gespeichert. Du kannst ihn <a href='../?type=$posttype&id=". $system->last_insert_id() . "'>hier ansehen</a> oder <a href='../admin/?page=item_modify&id=" . $system->last_insert_id() . "'>weiter bearbeiten</a>.";
	
} else {

?>  
    
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


    <p>Kategorien:</p>
    <p>
<select name="category">
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
</select>
    </p>
    

<p>Schlagworte (SEO):</p> 
    <p><input type="text" name="keywords" id="keywords"></p>

<p>Beschreibung (SEO):</p> 
    <p><input type="text" name="description" id="description" size="75" maxlength="200"></p>

</div>

<p><textarea cols="40" rows="20" name="text" id="ckeditor" class="ckeditor"></textarea></p>

<style>
.container1 input[type=text] {
padding:5px 0px;
margin:5px 5px 5px 0px;
}


.add_form_field
{
    background-color: #1c97f3;
    border: none;
    color: white;
    padding: 8px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
	border:1px solid #186dad;

}

input{
    border: 1px solid #1c97f3;
    height: 40px;
	margin-bottom:14px;
}

.delete{
    background-color: #fd1200;
    border: none;
    color: white;
    padding: 5px 5px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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
            $(wrapper).append('<div><input type="text" name="k[]"/> <input type="text" name="v[]"/> <a href="#" class="delete">Delete</a></div>'); //add input box
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
    <a class="add_form_field">Add New Field &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></a>
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
