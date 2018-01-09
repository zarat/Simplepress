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
    
$date = time();

//$db = new connection();
			
//$query = "INSERT INTO object (type, title, content, description, keywords, status, category, date) VALUES ('$posttype','$title', '$text', '$description', '$keywords', 1, $category, '$date')";

$cfg = array("insert"=>"object (type, title, content, description, keywords, status, category, date)","values"=>"('$posttype','$title', '$text', '$description', '$keywords', 1, $category, '$date')");
$system->insert($cfg);

//$db->query($query);
  
//$ff = $db->last_insert_id();
  
echo "Dein Inhalt wurde gespeichert.";
	
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

<p><input type="submit" value="speichern"></p>

<div style="clear:both;"></div>

</form>

<?php } ?>

</div>
    
</body>
</html>
