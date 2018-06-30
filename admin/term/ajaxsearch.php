<?php

/**
 * Taxonomy Widget Ajax Input
 * 
 * @author Manuel Zarat
 */
require "../../load.php";
 
$system = new system();

if( !$system->auth() ) header("Location: ../login.php");

if(!empty($_GET['term'])) { 

    $the_term = $_GET['term'];
    $the_taxonomy = $_GET['taxonomy'];
    $item_id = $_GET['item_id'];
    
    /**
     * Finde den Term
     */
    $allterms = $system->fetch_all_assoc( $system->query( "select id,name from term where name like ('%$the_term%') GROUP BY id" ) );
    
    if( !$allterms ) { echo $system->_t('no_items_to_display'); exit(); }
    
    foreach( $allterms as $term) {
    
        echo "<input type=\"checkbox\" onclick=\"ajaxget('../admin/taxonomy.php','action=add_relation&item_id=$item_id&term=$term[id]&taxonomy=$the_taxonomy')\"> $term[name]<br>"; 
    
    }
	
}

?>