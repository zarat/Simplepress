<?php

/**
 * @author Manuel Zarat
 */

if( !@$_GET['type'] && !@$_POST['title'] ) { die("sorry, wrong query."); }

$posttype = $_GET['type'];

if(!empty($_POST['title'])) { 
	
    $title = !empty( $_POST['title'] ) ? htmlentities($_POST['title'], ENT_QUOTES, 'utf-8') : "";
    $keywords = !empty( $_POST['keywords'] ) ? htmlentities($_POST['keywords'], ENT_QUOTES, 'utf-8') : "";  
    $description = !empty( $_POST['description'] ) ? htmlentities($_POST['description'], ENT_QUOTES, 'utf-8') : "";
    $category = !empty( $_POST['category'] ) ? $_POST['category'] : 0;
    $date = !empty($_POST['date']) ? strtotime( $_POST['date'] ) : time();
    
	/**
	 * Strip htmlentities between <pre> Tags??
     *
	function strip_pre_content($matches) {
		return str_replace($matches[1],htmlentities($matches[1], ENT_QUOTES),$matches[0]);
	}
	$text = preg_replace_callback('/<pre>(.*?)<\/pre>/imsu','strip_pre_content', $_POST['text']);
    */
    
    $text = base64_decode( $_POST['text'] );
        
    $cfg = array("insert"=>"item (type, title, content, description, keywords, status, category, date)","values"=>"('$posttype','$title', '$text', '$description', '$keywords', 1, $category, $date)");
    $system->insert($cfg);
	
} else {

echo "<script type=\"text/javascript\" src=\"../admin/js/admin.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"../admin/js/jswriter.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"../admin/js/datepicker.js\"></script>\n";
echo "<link rel=\"stylesheet\" href=\"../admin/css/datepicker.css\">\n";
echo "<link rel=\"stylesheet\" href=\"../admin/css/jswriter.css\">\n";

?>

<div class="sp-content">
<div class="sp-content-item">
<div class="sp-content-item-head"><?php echo $system->_t('item_add'); ?></div>
<div class="sp-content-item-body">

<form id="frm" method="post">
    <p>Titel <a onclick="toggle('more');" href="#">weitere Optionen</a></p>    
    <p><input name="title" type="text" value="" id="title"></p>    
    <div id="more" style="display:none;">    
        <p>Datum</p>
        <p><input type="text" name="date" class="datepicker" value="" id="date"></p>        
        <p>Kategorie</p>
        <p><select name="category">
            <?php
            $cfg = array('select'=>'*','from'=>'item','where'=>'type="category"');
            $a = $system->archive($cfg);
            for($i=0;$i<count($a);$i++) {
                echo "<option value='" . $a[$i]['id'] . "'>" . $a[$i]['title'] . "</option>";
            }
            ?>
        </select></p>        
        <p>Schlagworte</p>
        <p><input name="keywords" type="text" value="" id="keywords"></p>        
        <p>Beschreibung</p><p><input name="description" type="text" value="" id="description"></p>   
    </div>   
    <p>Inhalt</p>
    <p><div id="editor"></div></p>
    TXT:<div id="text-output"></div>
    <br><br>
    HTML:<div id="html-output"></div>
    <p><a style="cursor:pointer;" onclick="save();">Item speichern</a></p>    
</form>

</div>
</div>
</div>

<div style="clear:both;"></div>

<script>
  var editor = window.jswriter.init({
    element: document.getElementById('editor'),
    defaultParagraphSeparator: 'p',
    styleWithCSS: false,
    onChange: function (html) {
      document.getElementById('text-output').textContent = html
      document.getElementById('html-output').innerHTML = html
    }
  })  

  function save() {
    var title = document.getElementById('title').value;
    var date = document.getElementById('date').value;
    var keywords = document.getElementById('keywords').value;
    var description = document.getElementById('description').value;
    var content = btoa(document.getElementById('text-output').innerHTML);
    ajaxpost("index.php?page=item_add&type=<?php echo $posttype; ?>", "title=" + title + "&date=" + date + "&keywords=" + keywords + "&description=" + description + "&category=1&text=" + content );
  }
</script>

<?php } ?>
