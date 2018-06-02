<?php 

/**
 * @author Manuel Zarat
 */

require_once '../../load.php';

$system = new system();

if( !empty( $_POST['id'] ) ) {

    $arr['type']  = 'edit';
    $arr['label'] = $_POST['label'];
    $arr['link']  = $_POST['link'];
    $arr['id']    = $_POST['id'];
    
    $system->update( array( "table" => "menu", "set" => "label='" . $arr['label'] . "', link ='" . $arr['link'] . "' where id=" . $arr['id'] ) );

} else {

    $insert_id = $system->insert(array("insert" => "menu (label,link,menu_id)", "values" => "('".$_POST['label']."', '".$_POST['link']."', ".$_POST['menu_id'].")"));
    $arr['menu'] = "<li class=\"dd-item dd3-item\" data-id=\"" . $insert_id . "\">
                        <div class=\"dd-handle dd3-handle\"></div>
                        <div class=\"dd3-content\"><span id=\"label_show\"" . $insert_id . "\">" . $_POST['label'] . "\"</span>
                            <span class=\"span-right\">
                            	<a class=\"edit-button\" id=\"" . $insert_id . "\" label=\"" . $_POST['label'] . "\" link=\"" . $_POST['link'] . "\">edit</a>
                             		<a class=\"del-button\" id=\"" . $insert_id . "\">delete</a>
                            </span> 
                        </div>";
    $arr['type'] = "add";

}

print json_encode($arr);

?>
