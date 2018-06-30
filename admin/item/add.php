<?php

/**
 * Die Datei wird ueber index.php eingebunden deshalb ist $system schon definiert.
 * Asynchron eingebundene Dateien muessen load.php einbinden und auch $system deklarieren!
 *
 * @author Manuel Zarat
 */
if( !$system->auth() ) header("Location: ../login.php");

if(!empty($_POST['title'])) { 

    $title = !empty( $_POST['title'] ) ? htmlentities($_POST['title'], ENT_QUOTES, 'utf-8') : "";
    $date = !empty($_POST['date']) ? strtotime( $_POST['date'] ) : time();
    $keywords = !empty( $_POST['keywords'] ) ? htmlentities($_POST['keywords'], ENT_QUOTES, 'utf-8') : "";  
    $description = !empty( $_POST['description'] ) ? htmlentities($_POST['description'], ENT_QUOTES, 'utf-8') : "";    
    $content = !empty( $_POST['content'] ) ? htmlentities($_POST['content'], ENT_QUOTES, 'utf-8') : "";
    $status = 0;
    
    $stmt = $system->db->prepare( "insert into item (title, date, keywords, description, content, status) values (?,?,?,?,?,?)" );    
    $stmt->bind_param( "sisssi" , $title, $date, $keywords, $description, $content, $status );
    $stmt->execute();

    echo "<div class=\"sp-content\">\n";
    echo "<div class=\"sp-content-item\">\n";
    echo "<div class=\"sp-content-item-head\">" . $system->_t('item_modify') . "</div>\n";
    echo "<div class=\"sp-content-item-body\">\n";   
    echo "Inhalt wurde gespeichert.";
    echo "</div>\n";
    echo "</div>\n";
    echo "</div>\n";       
	
} else {

echo "<script type=\"text/javascript\" src=\"../admin/js/suneditor.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"../admin/js/datepicker.js\"></script>\n";
echo "<link rel=\"stylesheet\" href=\"../admin/css/suneditor.css\">\n";
echo "<link rel=\"stylesheet\" href=\"../admin/css/datepicker.css\">\n";

?>

<script src="../admin/js/tinymce.min.js"></script>

<div class="sp-content">

    <div class="sp-content-item">
    
    <div class="sp-content-item-head"><?php echo $system->_t('item_add'); ?></div>
    
    <div class="sp-content-item-body">
    
        <form id="frm" method="post">
        
            <p><?php echo $system->_t('item_add_title'); ?> <a onclick="toggle('more');" href="#">weitere Optionen</a></p>    
            <p><input name="title" type="text"></p> 
               
            <div id="more" style="display:none;">
                
                <p><?php echo $system->_t('item_add_date'); ?></p>
                <p><input type="text" name="date" class="datepicker"></p>        
                        
                <p><?php echo $system->_t('item_add_keywords'); ?></p>
                <p><input name="keywords" type="text"></p>  
                      
                <p><?php echo $system->_t('item_add_description'); ?></p>
                <p><input name="description" type="text"></p>
                   
            </div>
               
            <p><?php echo $system->_t('item_add_content'); ?></p>
            <p><textarea name="content" style="width:100% !important;" rows="20"></textarea></p>
            
            <p><input type="submit" value="speichern"></p> 
               
        </form>
    
    </div>
    
    </div>

</div>

<div style="clear:both;"></div>
 
<script>
document.getElementById("datepicker").datepicker();  
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
