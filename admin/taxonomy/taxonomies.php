<?php

/**
 * @author Manuel Zarat
 */

require_once "../../load.php";

$system = new system();
$tax = new taxonomy();

/**
 * Zeige ale Terms zu einer Taxonomy
 */
$taxonomy_id = $_GET['id']; 

echo "<p>choose one</p>";
echo "<select name=''>";
foreach( $tax->get_all_terms_of_taxonomy_id( $taxonomy_id ) as $term ) {
    echo "<option name='' value='" . $term['id'] . "'>" . $term['name'] . "</option>";
}  
echo "</select>";
echo "<p>create one</p>";
?>