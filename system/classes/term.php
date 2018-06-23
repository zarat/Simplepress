<?php

/**
 * Simplepress Term Klasse Beta
 * 
 * Ein Term wird einer Taxonomie zugewiesen.
 * 
 * @author Manuel Zarat
 */
 
class term extends system {

    /**
     * Alle Terms die in der Tabelle term existieren
     */
    function terms() {
        $query = "
            select id, name 
            from term
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;    
    }
    
    function terms_by_taxonomy_id( $taxonomy_id ) {
        $query = "
            select id, name
            from term
            where taxonomy_id = $taxonomy_id
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;
    }
    
    function terms_by_item_id( $item_id, $parent_taxonomy ) {
        $query = "
            select * from term
            inner join term_relation tr on tr.object_id=$item_id
            inner join term_taxonomy tt on term.taxonomy_id=tt.id
            where term.taxonomy_id=$parent_taxonomy 
 
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;
    }
    
}

?>
