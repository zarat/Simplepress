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
 * Eine Taxonomie kann mehrere Terms referenzieren, z.B "Allgemein" und "Speziell" -> siehe term.php
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
    
    /**
     * Alle Taxonomien die in der Tabelle term_taxonomy existieren und kein parent haben
     */
    function top_taxonomies() {
        $query = "
            select id, taxonomy 
            from term_taxonomy
            where parent = 0
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;    
    }
    
    /**
     * Alle untergeordneten Taxonomien einer Taxonomie->Id
     * 
     * Frage: Was ist alles eine Art von "category"?
     * 
     * Antwort: 
     */
    function child_taxonomies( $taxonomy_id ) {
        $query = "
            select * 
            from term_taxonomy
            where parent = $taxonomy_id
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;    
    }
    
    /**
     * Alle Terms, die einem Taxonomy->Name zugewiesen wind
     * 
     * Antwort: "Allgemeines" ist der taxonomy(category) zugewiesen
     * Antwort: "Spezielles" ist der taxonomy(category) zugewiesen
     * Antwort: "Spezielles" ist der taxonomy(post_tag) zugewiesen
     */
    function terms_by_taxonomy_name( $taxonomy_name) {
        $query = "
            select id, name
            from term
            where id in (
            	select term_id from term_relation
                where taxonomy_id in (
                	select id from term_taxonomy where taxonomy = '$taxonomy_name'
                )
            )
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;
    }
    
    /**
     * Alle Terms, die einer Taxonomy->Id zugewiesen wind
     * 
     * Antwort: "Allgemeines" ist der taxonomy(1) zugewiesen
     * Antwort: "Spezielles" ist der taxonomy(1) zugewiesen
     * Antwort: "Spezielles" ist der taxonomy(2) zugewiesen
     */
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

    /**
     * Alle Taxonomien, die Term->Namen zugewiesen sind
     *  
     * Frage: Was ist dem Term "Allgemeines" zugewiesen?
     * 
     * Antwort: "Allgemeines" ist der taxonomy(1) zugewiesen
     * Antwort: "Spezielles" ist der taxonomy(1) zugewiesen
     * Antwort: "Spezielles" ist der taxonomy(2) zugewiesen
     * 
     */
    function taxonomies_by_term_name( $term_name ) {    
        $query = "
            select id, taxonomy 
            from term_taxonomy
            where id in(
                select taxonomy_id from term_relation where term_id IN (
                        select id from term where name = '$term_name'
                )
            )
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;            
    }
    
    /**
     * Alle Taxonomien, die Term->Ids zugewiesen sind
     *  
     * Frage: Was ist dem Term mit der ID 1 zugewiesen?
     * 
     * Antwort: Dem Term 1 ("Allgemeines") ist der taxonomy(category) zugewiesen
     * Antwort: Dem Term 2 ("Spezielles") ist der taxonomy(category) zugewiesen
     * Antwort: Dem Term 2 ("Spezielles") ist der taxonomy(post_tag) zugewiesen
     * 
     */
    function taxonomies_by_term_id( $term_id ) {     
        $query = "
            select id, taxonomy 
            from term_taxonomy
            where id in(
                select taxonomy_id from term_relation where term_id = $term_id
            )
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;
    }
 
    /**
     * Alle Taxonomien, denen ein DB Item angehoert.
     */
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
 
    /**
     * um die bereits verlinkten taxonomy->term relations anzuzeigen!
     */
    function terms_by_item_id( $item_id, $taxonomy_id ) {
        $query = "
            select tt.taxonomy, t.id, t.name 
            from term t
            inner join term_relation tr on t.id=tr.term_id
            inner join term_taxonomy tt on tt.id=tr.taxonomy_id
            where tr.object_id=$item_id and tr.taxonomy_id=$taxonomy_id
            group by name
            ";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;
    }
    
}

?>
