<?php 

require_once '../../load.php';

$system = new system();

function recursiveDelete($id,$system) {

    $query = $system->select( array( "select" => "*", "from" => "menu", "where" => "parent=$id" ) );
    print_r($query);
    //foreach($query as $i => $q) {
        //recursiveDelete($q['id'],$system);
    //}

    $system->delete( array( "from" => "menu", "where" => "id=$id" ) );
}

recursiveDelete($_POST['id'],$system);

?>
