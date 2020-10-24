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
     * @deprecated die spalte taxonomy_id
     */
    function terms() {
        $query = "
            select id, name 
            from term
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;    
    }

    function by_taxonomy_id( $taxonomy_id ) {
        $query = "
            select term.* from term
            join term_relation tr on tr.term_id =term.id
            where tr.taxonomy_id=$taxonomy_id
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
    
    /**
     * Wird im Widget bei item edit verwendet um die Formularfelder zu selektieren..
     * @deprecated
     */
    function terms_by_item_id( $item_id, $parent_taxonomy ) {
        $query = "
            select * from term where id in(
                select term_id from term_relation where object_id=$item_id and taxonomy_id=$parent_taxonomy
            )  
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;
    }
    
}

?>
