<?php 

require_once '../../load.php';

$system = new system();

if($_POST['id'] != ''){

	//$db->exec("update menu set label = '".$_POST['label']."', link  = '".$_POST['link']."' where id = '".$_POST['id']."' ");
  $system->update(array("table" => "menu", "set" => "label='".$_POST['label']."', link ='".$_POST['link']."' where id=".$_POST['id']));

	$arr['type']  = 'edit';
	$arr['label'] = $_POST['label'];
	$arr['link']  = $_POST['link'];
	$arr['id']    = $_POST['id'];

} else {


	//$db->query("insert into menu (label,link,menu_id) values ('".$_POST['label']."', '".$_POST['link']."', '".$_POST['menu_id']."')");
  $insert_id = $system->insert(array("insert" => "menu (label,link,menu_id)", "values" => "('".$_POST['label']."', '".$_POST['link']."', ".$_POST['menu_id'].")"));

	$arr['menu'] = '<li class="dd-item dd3-item" data-id="'.$insert_id.'" >
	                    <div class="dd-handle dd3-handle"></div>
	                    <div class="dd3-content"><span id="label_show'.$insert_id.'">'.$_POST['label'].'</span>
	                        <span class="span-right"><span id="link_show'.$insert_id.'">'.$_POST['link'].'</span> &nbsp;&nbsp; 
	                        	<a class="edit-button" id="'.$insert_id.'" label="'.$_POST['label'].'" link="'.$_POST['link'].'" ><i class="fa fa-pencil"></i></a>
                           		<a class="del-button" id="'.$insert_id.'"><i class="fa fa-trash"></i></a>
	                        </span> 
	                    </div>';

	$arr['type'] = 'add';

}

print json_encode($arr);

?>
