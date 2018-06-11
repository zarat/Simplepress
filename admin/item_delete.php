<?php

/**
 * @author Manuel Zarat
 */

require "../load.php";
require_once "auth.php";

$system = new system();

$id = $_GET['id'];

/**
 * Das Item selbst
 */
$system->delete(array("from"=>"item","where"=>"id=$id"));

/**
 * und die Custom fields dazu entfernen.
 */
$system->delete(array("from"=>"item_meta","where"=>"meta_item_id=$id"));

?>
