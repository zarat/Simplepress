<?php

/**
 * @author Manuel Zarat
 */

if( !@$_GET['type'] && !@$_POST['title'] ) { die("sorry, wrong query."); }

$posttype = $_GET['type'];

if(!empty($_POST['title'])) { 

    $title = htmlentities($_POST['title']);
    $keywords = htmlentities($_POST['keywords']);
    $description = htmlentities($_POST['description']);
    $text = $_POST['text'];   
    $category = !empty($_POST['category']) ? $_POST['category'] : 0;         
    $date = !empty($_POST['date']) ? strtotime(str_replace(".", "-", $_POST['date'])) : time();
        
    $cfg = array("insert"=>"item (type, title, content, description, keywords, status, category, date)","values"=>"('$posttype','$title', '$text', '$description', '$keywords', 1, $category, '$date')");
    $system->insert($cfg);
    
    $last = $system->last_insert_id();
      
    echo "Dein Inhalt wurde gespeichert. Du kannst ihn <a href='../?type=$posttype&id=". $last . "'>hier ansehen</a> oder <a href='../admin/?page=item_modify&id=" . $last . "'>weiter bearbeiten</a>.";
	
} else {

echo "<link rel=\"stylesheet\" href=\"../admin/css/datepicker.css\">\n";
echo "<script type=\"text/javascript\" src=\"../admin/js/datepicker.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"../admin/js/admin.js\"></script>\n";
echo "<link href=\"../admin/css/jswriter.css\" rel=\"stylesheet\" type=\"text/css\">\n";
echo "<script type=\"text/javascript\" src=\"../admin/js/jswriter.js\"></script>\n";

/**
 * SP Content Anfang
 */
echo "<div class=\"sp-content\">";

    /**
     * SP Content Item Anfang
     */
    echo "<div class=\"sp-content-item\">";

    echo "<div class='sp-content-item-head'>" . $system->_t('item_add') . "</div>";

    /**
     * SP Content Item Body Anfang
     */
    echo "<div class=\"sp-content-item-body\">";

    /**
     * FORM Anfang
     */
    echo "<form id=\"frm\" method=\"post\">";
    
    echo '<p>' . $system->_t('item_add_title') . ' <a onclick="toggle(\'more\');" href="#">weitere Optionen</a></p>'; 
    echo "<p><input name=\"title\" type=\"text\" id=\"title\"></p>";
    
    /**
     * DIV More Anfang
     */
    echo "<div id=\"more\" style=\"display:none;\">";
    
        echo '<p>' . $system->_t('item_modify_date') . '</p>'; 
        echo "<p><input type=\"text\" name=\"date\" class=\"datepicker\" id=\"date\"></p>";
        
        echo '<p>' . $system->_t('item_add_category') . '</p>';
        echo "<p><select name=\"category\" id=\"category\">";
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
        echo "<p><input name=\"keywords\" type=\"text\" id=\"keywords\"></p>";
        
        echo '<p>' . $system->_t('item_add_description') . '</p>'; 
        echo "<p><input name=\"description\" type=\"text\"  id=\"description\"></p>";
    
    /**
     * DIV More Ende
     */
    echo "</div>";
    
        echo '<p>' . $system->_t('item_modify_content') . '</p>';
        echo "<p><div id=\"pell\" class=\"pell\"></div></p>";
        echo "<div id=\"text-output\"></div>";
    
    echo "<p><a style=\"cursor:pointer;\" onclick=\"save();\">Item speichern</a></p>";
    
    /**
     * FORM Ende
     */
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

echo "<div style=\"clear:both;\"></div>";

?> 

<script>
  var editor = window.jswriter.init({
    element: document.getElementById('pell'),
    defaultParagraphSeparator: 'p',
    styleWithCSS: false,
    onChange: function (html) {
      document.getElementById('text-output').innerHTML = html
      document.getElementById('html-output').textContent = html
    }
  })  

  function save() {
    var title = document.getElementById('title').value;
    var date = document.getElementById('date').value;
    var keywords = document.getElementById('keywords').value;
    var description = document.getElementById('description').value;
    var content = document.getElementById('text-output').innerHTML;
    ajaxpost("../admin/?page=item_add&type=<?php echo $posttype; ?>", "title=" + title + "&date=" + date + "&keywords=" + keywords + "&description=" + description + "&text=" + content );
  }
</script>
