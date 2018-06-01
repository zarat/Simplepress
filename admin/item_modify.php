<?php

/**
 * 
 * Bearbeitet ein Item aus der Datenbank. 
 * Wenn $_POST[] uebergeben wurde, wird es gespeichert und danach angezeigt, sonst nur angezeigt.
 * 
 */

if(!isset($_GET['id']) && !isset($_POST['id'])) { echo "<p>Keine ID gesetzt</p>"; exit(); }

echo "<div class=\"sp-content\">";

echo '<h3>' . $system->_t('item_modify') . '</h3>';

if (isset($_GET['id']) && isset($_POST['title'])) {

    $id=$_GET['id'];
    
    $title = htmlentities($_POST['title'], ENT_QUOTES, 'utf-8');      
    $keywords = htmlentities($_POST['keywords'], ENT_QUOTES, 'utf-8');    
    $description = htmlentities($_POST['description'], ENT_QUOTES, 'utf-8');
    $text = $_POST['text'];
    $category = $_POST['category'];
    $date = isset($_POST['date']) ? strtotime($_POST['date']) : $timestamp; 	
    
    $cfg = array("table" => "object","set" => "title='$title',keywords='$keywords', description='$description', content='$text', category='$category', date=$date WHERE id=$id");
    $system->update($cfg);
    
    $it = $system->single(array('id' => $id));
       
    echo "Deine &Auml;nderungen wurden gespeichert. <a href='../admin/?page=item_modify&id=$id'>Erneut bearbeiten</a> oder <a href='../?type=" . $it['type']. "&id=$id'>ansehen</a>";       	

} else {	
		
    //check ID!!!
    isset($_GET['id']) ? $id=$_GET['id'] : exit();
    
    $result = $system->single(array('a'=>'b','id'=>$id));  
    
    $res_id = $result['id'];
    
    $title = 		$result['title'];
    $keywords =     $result['keywords'];
    $description =  $result['description'];
    $text = htmlspecialchars($result['content']);
    $date = date("d.m.Y", $result['date']);
    
    $link = "../?type=$result[type]&id=$result[id]";
    
    echo "<form method=\"post\">";
    
    echo '<p>' . $system->_t('item_modify_title') . ' - <a onclick="toggle(\'more\');" href="#">weitere Optionen</a> - <a href="' . $link . '">ansehen</a></p>'; 
    echo "<p><input name=\"title\" type=\"text\" value=\"$title\"></p>";
    
    echo "<div id=\"more\" style=\"display:none;\">";
    
        echo '<p>' . $system->_t('item_modify_date') . '</p>'; 
        echo "<p><input type=\"text\" name=\"date\" class=\"datepicker\" value=\"$date\"></p>";
        
        echo '<p>' . $system->_t('item_modify_category') . '</p>';
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
        
        echo '<p>' . $system->_t('item_modify_keywords') . '</p>'; 
        echo "<p><input name=\"keywords\" type=\"text\" value=\"$keywords\"></p>";
        
        echo '<p>' . $system->_t('item_modify_description') . '</p>'; 
        echo "<p><input name=\"description\" type=\"text\" value=\"$description\"></p>";    
        
    echo "</div>"; // close #more
    
    echo '<p>' . $system->_t('item_modify_content') . '</p>';
    echo "<p><textarea cols=\"40\" rows=\"20\" name=\"text\" id=\"ckeditor\" class=\"ckeditor\">$text</textarea></p>";
    
    echo "<p><input type=\"submit\" value=\"speichern\"></p>";
    echo "<div style=\"clear:both;\"></div>";
    echo "</form>";
	
    echo "<div>
    <input type=\"text\" id=\"customfieldKey\" placeholder=\"Keyword\">
    <input type=\"text\" id=\"customfieldValue\" placeholder=\"Value\">
    <a style=\"cursor:pointer;\" onclick=\"savecustomfield('" . $id . "')\">Speichern</a>
    </div>
    <br>
    <div id=\"customfieldsList\"></div>
    <script>
    function mycustomfunc() {
        getcustomfields('" . $id . "');
    }
    window.setTimeout(mycustomfunc, 2000);
    </script>";

}

echo "</div>"; // close sp-content
 
?>

  <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>
