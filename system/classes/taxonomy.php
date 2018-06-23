<?php

/**
 * Simplepress Taxonomy Klasse Beta
 * 
 * Eine Taxonomy stellt eine Unterteilung von Items dar. Eine Taxonomie kann also vieles sein z.B
 * 
 *  - Kategorie
 *  - Schlagwort
 *  - u.s.w
 *
 * @author Manuel Zarat
 */
 
class taxonomy extends system {

    /**
     * Alle Taxonomien die in der Tabelle term_taxonomy existieren
     */
    function taxonomies() {
        $query = "
            select id, taxonomy 
            from term_taxonomy
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;    
    }

    function terms_by_taxonomy_id( $taxonomy_id) {
        $query = "
            select id, name
            from term
            where id in (
            	select term_id from term_relation
                where taxonomy_id = $taxonomy_id
            )
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;
    }

    function taxonomies_by_item_id( $item_id ) {
        $query = "
            select id,taxonomy 
            from term_taxonomy
            where id IN (
                select taxonomy_id from term_relation where object_id=$item_id
            )
            group by taxonomy
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;    
    }
    
}

?>
