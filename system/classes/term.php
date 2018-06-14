<?php

/**
 * Simplepress Term Klasse Beta
 * 
 * Ein Term kann mehreren Taxonomien zugewiesen werden.
 * Er kann also die Taxonomie "category" oder "post_tag" oder etwas anderes sein.
 * 
 * @author Manuel Zarat
 */
 
class term extends system {

    /**
     * Alle Terms die in der Tabelle term existieren
     */
    function get_existing_terms() {
        $query = "
            select id, name 
            from term
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;    
    }
    
    /**
     * Alle Taxonomien, die einem Term->Name zugewiesen wind
     * 
     * Antwort: "Allgemeines" ist der taxonomy(category) zugewiesen
     * Antwort: "Allgemeines" ist der taxonomy(post_tag) zugewiesen
     */
    function get_all_taxonomies_of_term_name( $term_name) {
        $query = "
            select term_taxonomy.id, term_taxonomy.taxonomy
            from term_taxonomy
            inner join term_relation tr on tr.taxonomy_id = term_taxonomy.id 
            where tr.term_id = ( 
                select id from term where name = 'Allgemeines'
            )
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;
    }

    /**
     * Alle Taxonomien, die einer Term->Id zugewiesen wind
     * 
     * Antwort: Term 1 ist der taxonomy(category) zugewiesen
     * Antwort: Term 1 ist der taxonomy(post_tag) zugewiesen
     */
    function get_all_taxonomies_of_term_id( $term_id) {
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
    
}

?>
