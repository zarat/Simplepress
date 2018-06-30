<?php

/**
 * @author Manuel Zarat
 */

require_once '../../load.php';

$system = new system();

if( !$system->auth() ) header("Location: ../login.php");

if( !empty( $_POST['id'] ) ) {

    $arr['type'] = "edit";
    $arr['label'] = $_POST['label'];
    $arr['link'] = $_POST['link'];
    $arr['id'] = $_POST['id'];
    
    $stmt = $system->db->prepare( "update menu set label=?, link=? where id=?" );    
    $stmt->bind_param( "ssi" , $arr['label'], $arr['link'], $arr['id'] );
    $stmt->execute();   

} else {

    $arr['type'] = "add";
    $arr['label'] = $_POST['label'];
    $arr['link'] = $_POST['link'];
    $arr['menu_id'] = $_POST['menu_id'];

    $stmt = $system->db->prepare( "insert into menu (label,link,menu_id) values (?,?,?)" );    
    $stmt->bind_param( "sii" , $arr['label'], $arr['link'], $arr['menu_id'] );
    $stmt->execute();
    $insert_id = $stmt->insert_id;
    
    $arr['menu'] = "<li class=\"dd-item dd3-item\" data-id=\"$insert_id\">
                        <div class=\"dd-handle dd3-handle\"></div>
                        <div class=\"dd3-content\"><span id=\"label_show$insert_id\">$arr[label]</span>
                            <span class=\"span-right\">
                            	<a class=\"edit-button\" id=\"$insert_id\" label=\"$arr[label]\" link=\"$arr[link]\">edit</a>
                             		<a class=\"del-button\" id=\"$insert_id\">delete</a>
                            </span> 
                        </div>";

}

print json_encode($arr);

?>
