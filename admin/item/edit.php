<?php

/**
 * Die Datei wird ueber index.php eingebunden deshalb ist $system schon definiert.
 * Asynchron eingebundene Dateien muessen load.php einbinden und auch $system deklarieren!
 *
 * @author Manuel Zarat
 */
if( !$system->auth() ) header("Location: ../login.php"); 

if ( isset( $_GET['id'] ) && isset( $_POST['title'] ) ) {

    $id = $_GET['id'];      
    $title = !empty( $_POST['title'] ) ? htmlentities($_POST['title'], ENT_QUOTES, 'utf-8') : "";
    
    $_date = !empty($_POST['date']) ? $_POST['date'] : date('d.m.Y');
    $_time = !empty($_POST['time']) ? $_POST['time'] : date('H:i');
    $date = strtotime( $_date . " " . $_time );    
    
    $keywords = !empty( $_POST['keywords'] ) ? htmlentities($_POST['keywords'], ENT_QUOTES, 'utf-8') : "";  
    $description = !empty( $_POST['description'] ) ? htmlentities($_POST['description'], ENT_QUOTES, 'utf-8') : "";    
    $content = !empty( $_POST['content'] ) ? htmlentities($_POST['content'], ENT_QUOTES, 'utf-8') : "";
    
    $stmt = $system->db->prepare( "update item set title=?, date=?, keywords=?, description=?, content=? WHERE id=?" );    
    $stmt->bind_param( "sisssi" , $title, $date, $keywords, $description, $content, $id );
    $stmt->execute();

    echo "<div class=\"sp-content\">\n";
    echo "<div class=\"sp-content-item\">\n";
    echo "<div class=\"sp-content-item-head\">" . $system->_t('item_modify') . "</div>\n";
    echo "<div class=\"sp-content-item-body\">\n";   
    echo "Dein Inhalt wurde gespeichert. Du kannst ihn <a href='../?id=$id'>hier ansehen</a>, <a href='../admin/item.php?action=edit&id=$id'>weiter bearbeiten</a> oder <a href=\"../admin/item.php?action=add\">neu anlegen</a>.";
    echo "</div>\n";
    echo "</div>\n";
    echo "</div>\n";     	

} else {	
		
    //check ID!!!
    isset($_GET['id']) ? $id = $_GET['id'] : exit();
    
    $item = $system->fetch( $system->query( "select * from item where id=$id " ) );  
    
    $id = $item['id'];    
    $title = $item['title'];
    $keywords = $item['keywords'];
    $description = $item['description'];

    $date = date("d.m.Y", $item['date']);
    $_hours = date("H", $item['date']);
    $_minutes = date("i", $item['date']);
    
    $content = $item['content'];
    
echo "<script type=\"text/javascript\" src=\"../admin/js/datepicker.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"../admin/js/timepicker.js\"></script>\n";
echo "<link rel=\"stylesheet\" href=\"../admin/css/datepicker.css\">\n";
echo "<link rel=\"stylesheet\" href=\"../admin/css/timepicker.css\">\n";                                   
    
?>

<script src="../admin/js/tinymce.min.js"></script>

<div class="sp-content">

    <div class="sp-content-item">
    
        <div class="sp-content-item-head"><?php echo $system->_t('item_modify'); ?></div>
        
        <div class="sp-content-item-body">
        
            <form id="frm" method="post">
            
                <p><?php echo $system->_t('item_modify_title'); ?> <a onclick="toggle('more');" href="#">weitere Optionen</a></p>    
                <p><input name="title" type="text" value="<?php echo $title; ?>" id="title"></p>  
                  
                <div id="more" style="display:none;">
                    
                    <p><?php echo $system->_t('item_modify_date'); ?></p>
                    <p><input type="text" name="date" class="datepicker" value="<?php echo $date; ?>" id="date"></p> 
                    
                    <p><?php echo $system->_t('item_add_time'); ?></p>
                    <p><input type="text" name="time" data-toggle="timepicker"></p>
                           
                    <p><?php echo $system->_t('item_modify_keywords'); ?></p>
                    <p><input name="keywords" type="text" value="<?php echo $keywords; ?>" id="keywords"></p> 
                           
                    <p><?php echo $system->_t('item_modify_description'); ?></p>
                    <p><input name="description" type="text" value="<?php echo $description; ?>" id="description"></p> 
                      
                </div> 
                  
                <p><?php echo $system->_t('item_modify_content'); ?></p>
                <p><textarea name="content" style="width:100% !important;" rows="20"><?php echo $content; ?></textarea></p>
                
                <p><input type="submit" value="speichern"></p> 
                   
            </form>
        
        </div>
    
    </div>

</div>

<div class="sp-sidebar">

    <div class="sp-sidebar-item">
    
        <div class="sp-sidebar-item-head">Relations</div>
        
        <div class="sp-sidebar-item-body">

            <script>
            function show_in_div(data) {
                document.getElementById("ret").innerHTML = data;
            }
            function select_taxonomy(val) {
                console.log(val);
                if( val == 0 ) {
                    document.getElementById("ret").innerHTML = "";
                    return;
                }
                ajaxpost('../admin/taxonomy/widget.php?id='+val, 'item_id=<?php echo $id;?>', show_in_div );
            }
            </script>
            
            <p>Taxonomy</p>
            <p><select onchange="select_taxonomy(this.value);"> 
            <option value="0" selected="selected">Waehle</option>       
            <?php

            $taxonomies = $system->taxonomies();
            foreach( $taxonomies as $taxonomy) {
            echo "<option value='" . $taxonomy['id'] . "'>" . $taxonomy['taxonomy'] . "</option>";
            }
            ?>       
            </select></p>
                        
            
            <div id="ret"></div>

        </div>
    
    </div> 

    <div class="sp-sidebar-item">
    
        <div class="sp-sidebar-item-head">Custom fields</div>
        
        <div class="sp-sidebar-item-body">
        
            <p>Custom Key</p>
            <p><input type="text" id="customfieldKey" placeholder="Keyword"></p>
            
            <p>Custom Value</p>
            <p><input type="text" id="customfieldValue" placeholder="Value"></p>
            
            <a style="cursor:pointer;" onclick="savecustomfield('<?php echo $id; ?>')">Feld hinzuf&uuml;gen</a>
            
            <br>
            
            <div id="customfieldsList"></div>
            
            <script>
            function customfields() {
                getcustomfields("<?php echo $id; ?>");
            }
            window.setTimeout(customfields, 2000);
            </script>
        
        </div>
    
    </div>

</div>

<div style="clear:both;"></div>

<script>
    document.getElementById("datepicker").datepicker();
</script>

<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    timepicker.load({
    interval: 1,
    defaultHour: <?php echo $_hours; ?>,
    defaultMinute: <?php echo $_minutes; ?>
  });
});
</script>

<script>
tinymce.init({
    selector: 'textarea',
    plugins: 'image link media lists textcolor imagetools code fullscreen',  
    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | image link media | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code fullscreen',
    entity_encoding : "raw",
    image_advtab: true,
    mobile: { theme: 'mobile' },    
    relative_urls : false,
    images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '../admin/upload.php');
        xhr.onload = function() {
            var json;
            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            json = JSON.parse(xhr.responseText);
            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            success(json.location);
        };
        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);
    },   
    image_list: "../admin/uploads.php"       
});
</script>

<?php } ?>
