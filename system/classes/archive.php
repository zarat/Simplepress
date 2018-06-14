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
public $items = [];
    
    /**
     * Wird aufgerufen nachdem das Objekt erzeugt wurde. Eventuell den Kontruktor aus Core ueberschreiben?
     * 
     * @see core->__construct()
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
     * Kann mit Hooks gefiltert werden ALPHA!!!!!!!!!!!!!!! wegen Pagination
     * 
     * @param $config array optional see $system->select()
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
            $where = "status=1";             
            /**
             * Wenn eine Kategorie abgerufen wird..
             * Aufteilen auf Array um dynamischer filtern zu koennen
             */
            if ( $this->request( 'type' ) == 'category' ) {
                $where_category = " AND type IN ('page','post') AND category=" . $this->request( 'id' );
                $where_category = $hooks->apply_filters('archive_init_category', $where_category);
                $where .= $where_category;            
            /**
             * Wenn gesucht wird..
             * Aufteilen auf ein Array um dynamischer filtern zu koennen??
             */
            } else if( $this->request( 'type' ) == 'search' ) {
                $where_search = " AND type IN ('page','post') AND ( title LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' OR content LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' ) ";
                $where_search = $hooks->apply_filters('archive_init_search', $where_search);
                $where .= $where_search;            
            /**
             * Wenn ein bestimmter Type, aber NICHT CATEGORY ODER SEARCH abgerufen wird..
             * aufteilen auf ein array?? Derzeit noch unnoetig
             */
            } else if( $this->request( 'type' ) && ( $this->request( 'type' ) != 'category' && $this->request( 'type' ) != 'search' ) ) { 
                $custom_type = " AND type='" . $this->request( 'type' ) . "'";
                $custom_type = $hooks->apply_filters('archive_init_custom_type', $custom_type);
                $where .= $custom_type;            
            /**
             * Wenn nichts bestimmtes aufgerufen wird, also die Homepage sozusagen
             * String kann mit einem Hook gefiltert werden
             * Aufteilen auf ein array??
             */
            } else {              
                $homepage = " AND type IN ('page','post')";
                $homepage = $hooks->apply_filters('archive_init_homepage', $homepage);
                $where .= $homepage;
            }            
            /**
             * Wenn eine ID gesetzt ist..
             * Kann mit Hooks gefiltert werden
             * auf ein array aufteilen??
             */
            if( $this->request( 'id' ) && $this->request( 'type' ) != "category" ) {
                $where_id = " AND id=" . $this->request( 'id' );
                $where_id = $hooks->apply_filters('archive_init_where_id', $where_id);
                $where .= $where_id;
            }            
            /**
             * Wenn geblaettert wird..
             * String kann mit Hook gefiltert werden
             * DATE und PARAM(last) aufteilen auf ein Array um beides separat zu filtern??
             */
            if ( $this->request( 'last' ) ) {
                $last = " AND date < " . $this->request( 'last' );
                $last = $hooks->apply_filters('archive_init_last', $last);
                $where .= $last;
            }             
            /**
             * Sortieren nach..
             * String kann mit einem Hook gefiltert werden
             * DATE und SORT_ORDER in array??
             */
            $order = " ORDER BY date ASC"; 
            $order = $hooks->apply_filters('archive_init_order_by', $order);
            $where .= $order;                          
            $this->items = $this->select( array( "select" => "*", "from" => "item", "where" => $where ) );        
        }        
        $this->post_count = sizeof( $this->items );           
    }

    /**
     * Zaehlt wieviele Items i mErgebnis vorhanden sind.
     * 
     * @return int Anzahl an Items
     */
    function count_items() { 
        return ($this->items);        
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
        return ( count($this->items) > 0) ? true : false;            
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
        return ( count($this->items) > 0) ? true : false;        
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
        /**
         * und wenn Parameter uebergeben, dann ueberschreiben.
         */
        if( is_array( $config ) ) {
            extract( $config );
        }
        /**
         * Nur weitermachen wenn noch Items vorhanden sind
         */
        if( $this->more() ) {                
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
            /**
             * Sollen Metadaten mit ausgegeben werden, dann einfach an das array anhaengen
             * Zur Sicherheit $post statt $content
             */
            if( $metadata ) {
                $tmp_data = $this->single_meta( $item['id'] );
                if( $tmp_data ) {
                    foreach( $tmp_data as $k => $v ) {
                        $item[$k] = $v;
                    }
                }
            }
            /**
             * Inhalt kuerzen
             */
            if( $content_length ) {                        
                /**
                 * Woerter und HTML Tags dabei ganz lassen
                 */
                $tmp = strip_tags( html_entity_decode( $item['content'] ) );
                if ( strlen( $tmp ) > $content_length ) {
                    $item['content'] = preg_replace("/[^ ]*$/", '', substr( $tmp , 0, $content_length) ) . " ..."; 
                }                                                                                                 
            }            
            /**
             * HTML is default auf true, also staerker als plain!
             */
            if( $html ) { 
                $item['content'] = html_entity_decode( $item['content'] );
            } 
            if( $strip_tags ) {
                $item['content'] = strip_tags( $item['content'] );
            }                      
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
        if( $this->more() ) {              
            /**
             * Wenn in einer Kategorie geblaettert wird..
             */
            if( $this->request( 'type' ) == 'category' ) { 
                echo "<!-- BeginNoIndex -->\n<div class='sp-content-item'>\n<div class='sp-content-item-head'><a rel='nofollow' href='../?type=category&id=" . $this->request( 'id' ) . "&last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a></div>\n</div>\n<!-- EndNoIndex -->\n";            
            /**
             * Wenn gesucht wird
             */
            } else if( $this->request( 'type' ) == 'search' ) {
                echo "<!-- BeginNoIndex --><div class='sp-content-item'>\n<div class='sp-content-item-head'><a rel='nofollow' href='../?type=search&term=" . $this->request( 'term' ) . "&last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a></div>\n</div>\n<!-- EndNoIndex -->\n";            
            /**
             * Default 
             */
            } else {                            
                echo "<!-- BeginNoIndex --><div class='sp-content-item'>\n<div class='sp-content-item-head'><a rel='nofollow' href='../?last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a></div>\n</div>\n<!-- EndNoIndex -->\n";                       
            }                                          
        }                                    
    }

}

?>
