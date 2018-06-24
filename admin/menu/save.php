<?php 

require_once '../../load.php';

$data = json_decode($_POST['data']);

$system = new system();

if( !$system->auth() ) header("Location: ../login.php");

function parseJsonArray($jsonArray, $parentID = 0) {

  $return = array();
  foreach ($jsonArray as $subArray) {
    $returnSubSubArray = array();
    if (isset($subArray->children)) {
 		$returnSubSubArray = parseJsonArray($subArray->children, $subArray->id);
    }

    $return[] = array('id' => $subArray->id, 'parentID' => $parentID);
    $return = array_merge($return, $returnSubSubArray);
  }
  return $return;
}

$readbleArray = parseJsonArray($data);

$i=0;
foreach($readbleArray as $row){

  $i++;
  
	//$db->query("update menu set parent = '".$row['parentID']."', sort = '".$i."' where id = '".$row['id']."' ");
  $system->update(array("table" => "menu", "set" => "parent=".$row['parentID'].", sort=".$i." where id=".$row['id']));
}


?>
