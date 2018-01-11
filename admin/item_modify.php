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
unset($list['"']);
//unset($list['<']);
//unset($list['>']);
unset($list['&']);
$text = $_POST['text'];
$text = strtr($text, $list);
     
empty($_POST['category'])?$category=null:$category=$_POST['category']; 	

$cfg = array("table" => "object","set" => "title='$title',keywords='$keywords', description='$description', content='$text', category='$category' WHERE id=$id");
$system->update($cfg);

/**
 * hol alle existierenden custom fields
 * 
 * @todo Was wenn weniger neue felder uebergeben wurden als vorher bestanden haben?
 * 
 */ 
$existing_custom_fields = false;
if($existing_custom_fields = $system->single_meta($id)) {

    foreach($existing_custom_fields as $field => $value) {
        $existing_custom_fields[$value[0]] = $value[1];
    }
    
}
 
/**
 * Custom fields
 * 
 * @todo bestehende mit uebergebenen abgleichen
 * 
 */
if(isset($_POST['custom_field_key']) && isset($_POST['custom_field_value'])) {

    $posted_custom_fields = array_combine($_POST['custom_field_key'], $_POST['custom_field_value']);

    if(1==2) {
    echo "posted cust fields";
    echo "<pre>";
    print_r($posted_custom_fields);
    echo "</pre>";

    echo "exist cust fields";
    echo "<pre>";
    print_r($existing_custom_fields);
    echo "</pre>";
    }
    
    /**
     * es wurden Weniger gepostet als es davor waren
     * 
     * @deprecated 
     */
    if($posted_custom_fields<$existing_custom_fields) { }
    
    $insertcfg = array();
    $insert = "object_meta (`meta_item_id`, `meta_key`, `meta_value`)";
    $values = "";
    $posted_custom_field_count = count($posted_custom_fields);
    $count = 1;    
    foreach($posted_custom_fields as $k => $v) { 
       
        if( empty($k) || empty($v) ) { break; } 
           
        if(isset($existing_custom_fields[$k]) && $existing_custom_fields[$k] == $v) {        
        
            /**
             * @todo doppelte keys mit den selben values
             */
                                  
        } elseif(isset($existing_custom_fields[$k]) && $existing_custom_fields[$k] != $v) {        
            
            /**
             * @todo doppelte keys mit unterschiedlichen values. Derzeit werden sie einfach nocheinmal angelegt - also bei der Ausgabe ueberschrieben.
             */ 
                       
        } else { 
               
            $values .= "($id, '$k', '$v')";
            if($count<$posted_custom_field_count) { $values .= ","; } 
                       
        }        
        $count++;        
    }    
    $insertcfg['insert'] = $insert;
    $insertcfg['values'] = $values;
    $system->insert($insertcfg);    
}
    
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

<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="../admin/js/admin.js"></script>

<form method="post"> 

<input name="id" type="hidden" value="<?php echo $result['id']; ?>">

<p>Titel - <a class="hide" onclick="toggle('more'); " href="#">weitere Optionen</a> - <a href="<?php echo $link; ?>">ansehen</a></p>
<p><input name="title" type="text" value="<?php echo $title; ?>"></p>

<div id="more" style="display:none;">

<p>Kategorie
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

<p>Metatags</p> 
<p><input type="text" name="keywords" id="keywords" value="<?php echo $keywords; ?>"></p>

<p>Metabeschreibung</p> 
<p><input type="text" name="description" id="description" value="<?php echo $description; ?>" size="75" maxlength="200"></p>

</div>

<p><textarea cols="60" rows="25" name="text" class="ckeditor"><?php echo html_entity_decode($text); ?></textarea></p>

<?php
/**
 * Custom fields 
 * 
 */ 
?>

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
    <a class="add_form_field">Neues Feld</a>
    <?php
    /**
     * @todo Standardfeld ueberschreiben und alle bestehenden einfuegen
     * 
     */
     $custom_fields = $system->single_meta($_GET['id']);

     if($custom_fields) {
         foreach($custom_fields as $field) {
            echo "<div><input type='text' name='custom_field_key[]' value='$field[k]'> <input type='text' name='custom_field_value[]' value='$field[v]'><a href='#' class='delete'>Delete</a></div>";
         }
     } else {
         echo "<div><input type='text' name='custom_field_key[]'> <input type='text' name='custom_field_value[]'><a href='#' class='delete'>Delete</a></div>";
     }
    ?>
</div>
</div>

<p><input type="submit" value="speichern"></p>

<div style="clear:both;"></div>

</form>



<?php } ?>	

</div>
<?php exit(); ?>					               
</body>
</html>
