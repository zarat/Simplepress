<?php

/**
 * Simplepress Archiv
 *
 * Ein Archiv aller Items bzw. nach etwas gefiltert um es in einem Loop auszugeben.
 *
 * @author Manuel Zarat
 * @version 0.2.0
 * @link https://github.com/zarat/simplepress   
 * @since 06/2018 
 */

class archive extends system {

private $max_per_page = 10;
private $displayed_this_page = 0;
private $last = 0;
private $item_count = 0;
public $items = array();

public $is_single = false;
public $is_archive = false;    
public $is_default = false;
public $is_search = false;

    /**
     * Wird aufgerufen nachdem das Objekt erzeugt wurde. 
     * 
     * @return void
     */
    function archive_init( $config = false ) {          
        if( !empty( $this->request( 'last' ) ) ) {         
            $this->last = $this->request( 'last' );            
        }                
        $this->fill_items( $config );         
    }
    
    /**
     * Fuellt das Array(items) mit den gefundenen Items
     * 
     * @return void
     */
    final function fill_items( $config = false ) {        
        global $hooks;                                   
        if( false !== $config ) {            
            $this->items = $this->select( $config );            
        } else {                    
            /**
             * Nur aktive  Items!!
             */
            $where = "";             
            /**
             * Wenn eine Kategorie abgerufen wird..
             * Aufteilen auf Array um dynamischer filtern zu koennen
             */
            if ( $this->request( 'type' ) == 'category' ) {
                $where_category = "select * from item WHERE type IN ('page','post') ";
                $where_category = $hooks->apply_filters('archive_init_category', $where_category);
                $where_category .= " AND category=" . $this->request( 'id' );
                $where .= $where_category; 
                $this->is_archive = true;           
            /**
             * Wenn gesucht wird..
             * Aufteilen auf ein Array um dynamischer filtern zu koennen??
             */
            } else if( $this->request( 'type' ) == 'search' ) {
                $where_search = "select * from item WHERE type IN ('page','post') AND ( title LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' OR content LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' ) ";
                $where_search = $hooks->apply_filters('archive_init_search', $where_search);
                $where .= $where_search;  
                $this->is_archive = true; 
                $this->is_search = true;          
            /**
             * Wenn ein bestimmter Type, aber NICHT CATEGORY ODER SEARCH abgerufen wird..
             * aufteilen auf ein array?? Derzeit noch unnoetig
             */
            } else {
            
                if( $this->request( 'type' ) ) {
                
                    $custom_query= "                    
                            select item.* from item 
                            inner join term_relation tr on tr.object_id=item.id
                            inner join term_taxonomy tt on tt.id=tr.taxonomy_id
                            inner join term t on t.id=tr.term_id
                            where tr.taxonomy_id=(
                            	select id from term_taxonomy where taxonomy='type'
                            )
                            AND t.name='" . $this->request( 'type' ) . "'
                            ";
                    $where .= $custom_query;
                    $this->is_archive = true;
                
                } else { 
                
                    if( $this->request( 'id' ) ) {
                        $where_id = "select * from item WHERE id=" . $this->request( 'id' );
                        $where_id = $hooks->apply_filters('archive_init_where_id', $where_id);
                        $where .= $where_id;
                        $this->is_archive = false;
                        $this->is_single = true;
                        
                    } else {                          
                        $homepage = "select * from item WHERE type IN ('page','post')";
                        //$homepage = $hooks->apply_filters('archive_init_homepage', $homepage);
                        $where .= $homepage; 
                        $this->is_default = true;             
                    }
                    
                }  
            
            }          

            /**
             * Wenn geblaettert wird..
             * String kann mit Hook gefiltert werden
             * DATE und PARAM(last) aufteilen auf ein Array um beides separat zu filtern??
             */
            if ( $this->request( 'last' ) ) {
                $last = " AND item.date < " . $this->request( 'last' );
                $last = $hooks->apply_filters('archive_init_last', $last);
                $where .= $last;
            } 
                        
            /**
             * Sortieren nach..
             * String kann mit einem Hook gefiltert werden
             * DATE und SORT_ORDER in array??
             */
            if( !$this->is_single ) {
                $order = " ORDER BY item.date ASC"; 
                $order = $hooks->apply_filters('archive_init_order_by', $order);
                $where .= $order;  
            }
                                                                                       
            $this->items = $this->fetch_all_assoc( $this->query( $where ) ); 
        
        } 
      
        $this->item_count = $this->count_items(); 
                                                            
    }

    /**
     * Zaehlt wieviele Items i mErgebnis vorhanden sind.
     * 
     * @return int Anzahl an Items
     */
    function count_items() { 
        return count($this->items);        
    }

    /**
     * Prueft, ob noch weitere Items vorhanden sind die AUF DIESER SEITE angezeigt werden!
     * 
     * @return bool true|false
     */
    function have_items() {                    
        if( $this->displayed_this_page >= $this->max_per_page ) {                
            $this->is_page_limit = true;                        
            return false;                        
        }            
        return $this->more();           
    }
    
    /**
     * Prueft, ob noch weitere Items NACH DEN AUF DIESER SEITE AUSZUGEBENDEN vorhanden sind.
     * Wenn ja wird ein Link zum blaettern angezeigt.
     * 
     * @see $this->pagination()
     * 
     * @return bool true|false
     */
    function more() {    
        return $this->count_items() > 0 ? true : false;        
    }

    /**
     * Gibt das ktuelle Item im Loop zurueck.
     * 
     * @param array $config Konfig uebergeben?
     * 
     * @return array()|bool Das Item als Array oder false
     */
    function the_item( $config = false ) {
        
        /**
         * Default Config
         */ 
        $metadata = true;
        $content_length = false;
        $html = true;
        $strip_tags = false;
        
        if( $config ) {
            extract( $config );
        }

        /**
         * Nur weitermachen wenn noch Items vorhanden sind
         */
        if( $this->more() ) {
            $this->item_count--;                                    
            /**
             * Erstes vom Stack poppen..
             */
            $item = array_pop( $this->items );                    
            /**
             * Zuletzt angezeigte ID
             */
            $this->last = $item['id']; 
            /**
             * Zuletzt angezeigter Timestamp
             */
            $this->last_timestamp = $item['date'];           
            /**
             * Wieviele Items auf dieser Seite schon angezeigt wurden
             */
            $this->displayed_this_page++;                       
            return $item;  
        } 
        return false; 
    }

    /**
     * Zeigt Links zu der naechsten Seite eines Archives an.
     * 
     * @todo Suche!!!! und Hooks (variablen aendern)
     * 
     * @return html 
     */
    function pagination() {
        
        /**
         * Nur anzeigen wenn es noch Items gibt.
         */
        if( $this->more() ) {                          
            echo "<!-- BeginNoIndex --><div class='sp-content-item'>\n<div class='sp-content-item-head'>";            
            if( $this->is_archive ) {
                if( $this->is_search ) {
                    echo "<a rel='nofollow' href='?type=".$this->request('type')."&term=".$this->request('term')."&last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a>";
                } else {
                    echo "<a rel='nofollow' href='?type=".$this->request('type')."&last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a>";    
                }
            } else {
                echo "<a rel='nofollow' href='?last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a>";
            }
            echo "</div>\n</div>\n<!-- EndNoIndex -->\n";                                                               
        } 
                                           
    }

}

?>
