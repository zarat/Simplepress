<?php

/**
 * Wenn $_GET && $_POST uebergeben wurden, wird es gespeichert und dann angezeigt, sonst nur angezeigt.
 */
 
if( !@$_GET['id'] && !@$_POST['title'] ) { die("sorry, wrong query."); }

if ( isset( $_GET['id'] ) && isset( $_POST['content'] ) ) {

    $id = $_GET['id'];      
    $title = !empty( $_POST['title'] ) ? htmlentities($_POST['title'], ENT_QUOTES, 'utf-8') : "";
    $keywords = !empty( $_POST['keywords'] ) ? htmlentities($_POST['keywords'], ENT_QUOTES, 'utf-8') : "";  
    $description = !empty( $_POST['description'] ) ? htmlentities($_POST['description'], ENT_QUOTES, 'utf-8') : "";
    $category = !empty( $_POST['category'] ) ? $_POST['category'] : 0;
    $date = !empty($_POST['date']) ? strtotime( $_POST['date'] ) : time();
    
    /**
     * Der Text wird base64 kodiert vom jswriter uebergeben
     */
    $text = base64_decode( $_POST['content'] ); 
    
	/**
	 * Strip htmlentities between <pre> Tags??
     *
	 * function strip_pre_content($matches) {
	 * return str_replace($matches[1],htmlentities($matches[1], ENT_QUOTES),$matches[0]);
	 * }
	 * $text = preg_replace_callback('/<pre>(.*?)<\/pre>/imsu','strip_pre_content', $_POST['text']);
     */
    
    $cfg = array("table" => "item","set" => "title='$title',keywords='$keywords', description='$description', content='$text', category=$category, date=$date WHERE id=$id");
    $system->update($cfg);

    /*
    Die Ausgabe hier ist eigentilich unnoetig, weil ueber ajax uebergeben wird
    aber man koennte ja trotzdem einen link oder so zurückgeben

    $item = $system->single(array('id' => $id));   
    echo "<div class=\"sp-content\">\n";
    echo "<div class=\"sp-content-item\">\n";
    echo "<div class=\"sp-content-item-head\">" . $system->_t('item_modify') . "</div>\n";
    echo "<div class=\"sp-content-item-body\">\n";   
    echo "Dein Inhalt wurde gespeichert. Du kannst ihn <a href='../?type=" . $item['type']. "&id=$item[id]'>hier ansehen</a>, <a href='../admin/?page=item_modify&id=$item[id]'>weiter bearbeiten</a> oder <a href=\"../admin/?page=item_add&type=$item[type]\">neu anlegen</a>.";
    echo "</div>\n";
    echo "</div>\n";
    echo "</div>\n";
    */     	

} else {	
		
    //check ID!!!
    isset($_GET['id']) ? $id = $_GET['id'] : exit();
    
    /**
     * Informationen zum Anzeigen holen
     */
    $result = $system->single(array('id'=>$id));      
    $res_id = $result['id'];    
    $title = $result['title'];
    $keywords = $result['keywords'];
    $description = $result['description'];
    /**
     * Fuer datepicker formatieren
     */
    $date = date("d.m.Y", $result['date']);
	/**
	 * Strip htmlentities between <pre> Tags ?!
	 * $text = preg_replace_callback('/<pre>(.*?)<\/pre>/imsu','strip_pre_content', $result['content']);
	 */
    $text = $result['content'];       

    echo "<script type=\"text/javascript\" src=\"../admin/js/admin.js\"></script>\n"; 
    echo "<script type=\"text/javascript\" src=\"../admin/js/jswriter.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"../admin/js/datepicker.js\"></script>\n";
    
    echo "<link rel=\"stylesheet\" href=\"../admin/css/jswriter.css\">\n";
    echo "<link rel=\"stylesheet\" href=\"../admin/css/datepicker.css\">\n";

?>

<div class="sp-content">
<div class="sp-content-item">
<div class="sp-content-item-head"><?php echo $system->_t('item_modify'); ?></div>
<div class="sp-content-item-body">

<form id="frm" method="post">
    <p>Titel <a onclick="toggle('more');" href="#">weitere Optionen</a> - <a href="../?type=post&id=<?php echo $id; ?>">ansehen</a></p>    
    <p><input name="title" type="text" value="<?php echo $title; ?>" id="title"></p>    
    <div id="more" style="display:none;">    
        <p>Datum</p>
        <p><input type="text" name="date" class="datepicker" value="<?php echo $date; ?>" id="date"></p>        
        <p>Kategorie</p>
        <p><select name="category">
            <?php
            $cfg = array('select'=>'*','from'=>'item','where'=>'type="category"');
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
        <p>Schlagworte</p>
        <p><input name="keywords" type="text" value="<?php echo $keywords; ?>" id="keywords"></p>        
        <p>Beschreibung</p><p><input name="description" type="text" value="<?php echo $description; ?>" id="description"></p>   
    </div>   
    <p>Inhalt</p>
    <p><div id="editor" style="width:100% !important;"  id="content"></div></p>
    TXT:<div id="text-output"></div>
    <br><br>
    HTML:<div id="html-output"></div>
    <p><a style="cursor:pointer;" onclick="save();">Item speichern</a></p>    
</form>

</div>
</div>
</div>

<div class="sp-sidebar">
<div class="sp-sidebar-item">
<div class="sp-sidebar-item-head">Custom fields</div>

<div class="sp-sidebar-item-body">

<p>Custom Key</p>
<p><input type="text" id="customfieldKey" placeholder="Keyword"></p>
<p>Custom Value</p>
<p><input type="text" id="customfieldValue" placeholder="Value"></p>
<a style="cursor:pointer;" onclick="savecustomfield('<?php echo $res_id; ?>')">Feld hinzuf&uuml;gen</a>
<br>
<div id="customfieldsList"></div>
<script>
function customfields() {
    getcustomfields("<?php echo $res_id; ?>");
}
window.setTimeout(customfields, 2000);
</script>

</div>
</div>
</div>

<div style="clear:both;"></div>


<script>
  var editor = window.jswriter.init({
    element: document.getElementById("editor"),
    defaultParagraphSeparator: "p",
    styleWithCSS: false,
    ic: "<?php echo base64_encode(html_entity_decode($text)); ?>",
    onChange: function (html) {
      document.getElementById("text-output").textContent = html
      document.getElementById("html-output").innerHTML = html
    }
  })  

  function save() {
    var title = document.getElementById("title").value;
    var date = document.getElementById("date").value;
    var keywords = document.getElementById("keywords").value;
    var description = document.getElementById("description").value;
    var content = btoa(document.getElementById("text-output").innerHTML);
    ajaxpost("index.php?page=item_modify&id=<?php echo $res_id; ?>", "title=" + title + "&date=" + date + "&keywords=" + keywords + "&description=" + description + "&category=1&content=" + content );
  }
</script>

<?php } ?>
