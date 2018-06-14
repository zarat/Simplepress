<?php

/**
 * @author Manuel Zarat
 */

require_once "../../load.php";

$system = new system();
$term = new term();

/**
 * Zeige ale Terms
 */ 
echo "<p>choose a term to associate with</p>";
echo "<select name=''>";
foreach( $term->get_existing_terms() as $term ) {
    echo "<option name='' value='" . $term['id'] . "'>" . $term['name'] . "</option>";
}  
echo "</select>";

echo "<p>save the relation!</p>";

?>
