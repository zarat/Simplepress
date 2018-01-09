<?php

// Wenn keine ID übergeben wurde, abbrechen!
if(!isset($_GET['id']) && !isset($_POST['id'])) {

		echo "<p>Keine ID gesetzt</p>";
    exit();
    
}

if (isset($_POST['id'])&&isset($_POST['title'])) {

$id=$_POST['id'];
$title = htmlentities($_POST['title'], ENT_QUOTES, 'utf-8');      
$keywords = htmlentities($_POST['keywords']);    
$description = htmlentities($_POST['description']);

$list = get_html_translation_table(HTML_ENTITIES);

//unset($list['<']);
//unset($list['>']);
unset($list['"']);
unset($list['&']);

$text = $_POST['text'];
$text = strtr($text, $list);
     
empty($_POST['category'])?$category=null:$category=$_POST['category']; 	

$cfg = array("table" => "object","set" => "title='$title',keywords='$keywords', description='$description', content='$text', category='$category' WHERE id=$id");
$system->update_object($cfg);
    
echo "Deine &Auml;nderungen wurden gespeichert. <a href='../admin/?page=item_modify&id=$id'>Erneut bearbeiten</a>";       	

} else {	

// ANZEIGEN
		
    //check ID!!!
    isset($_GET['id'])?$id=$_GET['id']:exit();
    
    $result = $system->single(array('a'=>'b','id'=>$id));  
    
    $res_id = $result['id'];
    
    $title = 		$result['title'];
    $keywords =     $result['keywords'];
    $description =  $result['description'];
    $text = 		($result['content']);
            
    switch($result['type']) {
      case ("page"):
      $link = "../?type=page&id=$result[id]";
      break;
      case ("post"):
      $link = "../?type=post&id=$result[id]";
      break;
      case ("category"):
      $link = "../?type=category&id=$result[id]";
      break;
    }
    
?>

<script type="text/javascript" src="../admin/js/admin.js"></script>

<form method="post"> 

<input name="id" type="hidden" value="<?php echo $result['id']; ?>">

<p>Titel - <a class="hide" onclick="toggle('more'); " href="#">weitere Optionen</a> - <a href="<?php echo $link; ?>">ansehen</a></p>
<p><input name="title" type="text" value="<?php echo $title; ?>"></p>

<div id="more" style="display:none;">

<p>Kategorie
<select name="category">
<?php                     
//$a = $system->the_categories();
//print_r($a);

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

<p>Metatags</p> 
<p><input type="text" name="keywords" id="keywords" value="<?php echo $keywords; ?>"></p>

<p>Metabeschreibung</p> 
<p><input type="text" name="description" id="description" value="<?php echo $description; ?>" size="75" maxlength="200"></p>

</div>

<p><textarea cols="60" rows="25" name="text" class="ckeditor"><?php echo html_entity_decode($text); ?></textarea></p>

<p><input type="submit" value="speichern"></p>

<div style="clear:both;"></div>

</form>



<?php } ?>	

</div>
<?php exit(); ?>					               
</body>
</html>
