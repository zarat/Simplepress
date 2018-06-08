<?php

/**
 * Wenn $_GET && $_POST uebergeben wurden, wird es gespeichert und dann angezeigt, sonst nur angezeigt.
 */
 
if( !@$_GET['id'] && !@$_POST['title'] ) { die("sorry, wrong query."); }

if ( isset( $_GET['id'] ) && isset( $_POST['title'] ) ) {

    $id = $_GET['id'];
    
    //$title = htmlentities($_POST['title'], ENT_QUOTES, 'utf-8');      
    $title = htmlentities($_POST['title']);
    $keywords = htmlentities($_POST['keywords'], ENT_QUOTES, 'utf-8');    
    $description = htmlentities($_POST['description'], ENT_QUOTES, 'utf-8');
    $text = $_POST['text'];
    $category = $_POST['category'];
    $date = isset($_POST['date']) ? strtotime(str_replace(".", "-", $_POST['date'])) : date();
    
    if(!isset($_POST['date'])) {
        $date = time();
    } else {
        $date = strtotime( str_replace( ".", "-", $_POST['date'] ) );
    } 	
    
    $cfg = array("table" => "item","set" => "title='$title',keywords='$keywords', description='$description', content='$text', category='$category', date=$date WHERE id=$id");
    $system->update($cfg);
    
    $it = $system->single(array('id' => $id));



    echo "<div class=\"sp-content\">\n";
    echo '<h3>' . $system->_t('item_modify') . '</h3>\n';       
    echo "Dein Inhalt wurde gespeichert. Du kannst ihn <a href='../?type=" . $it['type']. "&id=$id'>hier ansehen</a> oder <a href='../admin/?page=item_modify&id=$id'>weiter bearbeiten</a>.";
    echo "</div>\n";     	

} else {	
		
    //check ID!!!
    isset($_GET['id']) ? $id = $_GET['id'] : exit();
    
    $result = $system->single(array('id'=>$id));  
    
    $res_id = $result['id'];
    
    $title = 		$result['title'];
    $keywords =     $result['keywords'];
    $description =  $result['description'];
    $text = htmlspecialchars($result['content']);
    $date = date("d.m.Y", $result['date']);
    
    $link = "../?type=$result[type]&id=$result[id]";
    
    echo "<link rel=\"stylesheet\" href=\"../admin/css/datepicker.css\">\n";
    echo "<link href=\"../admin/css/suneditor.css\" rel=\"stylesheet\" type=\"text/css\">\n";
    echo "<script type=\"text/javascript\" src=\"../admin/js/suneditor.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"../admin/js/datepicker.js\"></script>\n";
                                        
    /**
     * Content Anfang
     */
    echo "<div class=\"sp-content\">\n";
    
    echo "<div class=\"sp-content-item\">\n";

    echo "<div class='sp-content-item-head'>" . $system->_t('item_modify') . "</div>";
    
    echo "<div class='sp-content-item-body'>"; // Content Item Body Anfang
    
    echo "<form id=\"frm\" method=\"post\">";
    
        echo '<p>' . $system->_t('item_modify_title') . ' <a onclick="toggle(\'more\');" href="#">weitere Optionen</a> - <a href="' . $link . '">ansehen</a></p>'; 
        echo "<p><input name=\"title\" type=\"text\" value=\"$title\"></p>";
        
        /**
         * DIV MORE ANFANG
         */
        echo "<div id=\"more\" style=\"display:none;\">";
        
            echo '<p>' . $system->_t('item_modify_date') . '</p>'; 
            echo "<p><input type=\"text\" name=\"date\" class=\"datepicker\" value=\"$date\"></p>";
            
            echo '<p>' . $system->_t('item_modify_category') . '</p>';
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
            
            echo '<p>' . $system->_t('item_modify_keywords') . '</p>'; 
            echo "<p><input name=\"keywords\" type=\"text\" value=\"$keywords\"></p>";
            
            echo '<p>' . $system->_t('item_modify_description') . '</p>'; 
            echo "<p><input name=\"description\" type=\"text\" value=\"$description\"></p>";    
        
        /**
         * DIV MORE ENDE
         */    
        echo "</div>";
        
        echo '<p>' . $system->_t('item_modify_content') . '</p>';
        echo "<p><textarea cols=\"40\" rows=\"20\" name=\"text\" id=\"editor\" style=\"width:100% !important;\">$text</textarea></p>";
        
        echo "<p><a style=\"cursor:pointer;\" onclick=\"sun_save();\">Item speichern</a></p>";
    
    echo "</form>\n"; 
    
    echo "</div>"; // content item body ende
    
    echo "</div>";
   
    /**
     * Content Ende
     */
    echo "</div>\n";
    
    echo "<div class=\"sp-sidebar\">\n";
    
        echo "<div class=\"sp-sidebar-item\">\n";
    
            echo "<div class=\"sp-sidebar-item-head\">Custom fields</div>\n";
        
            echo "<div class=\"sp-sidebar-item-body\">\n";
            
            echo "
            <p>Custom Key</p>
            <p><input type=\"text\" id=\"customfieldKey\" placeholder=\"Keyword\"></p>
            <p>Custom Value</p>
            <p><input type=\"text\" id=\"customfieldValue\" placeholder=\"Value\"></p>
            <a style=\"cursor:pointer;\" onclick=\"savecustomfield('" . $id . "')\">Feld hinzuf&uuml;gen</a>
            <br>
            <div id=\"customfieldsList\"></div>
            <script>
            function customfields() {
                getcustomfields('" . $id . "');
            }
            window.setTimeout(customfields, 2000);
            </script>";
            
            echo "</div>\n"; // sidebar item body ende
        
        echo "</div>\n"; // sidebar item ende
        
     echo "</div>\n"; // sidebar ende
     
     echo "<div style=\"clear:both;\"></div>\n";

echo "</div>\n"; 
 
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

<?php } ?>
