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
private $item_count = -1;
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
            $query = "";         
            /**
             * Suche ist eine hierarchische Taxonomie.. Mehr dazu kommt noch 
             */
            if( $this->request( 'search' ) ) {

                $stmt = $this->db->prepare( "SELECT item.id, item.title, item.content, item.status, item.date, 
                        GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( SELECT id FROM term WHERE id=tr.term_id ) ) AS type
                        FROM item
                        INNER JOIN term_relation tr ON tr.object_id=item.id
                        WHERE item.title LIKE (?) OR item.content LIKE (?)" );    
                $s = "%" . htmlentities( $this->request( 'search' ) ) . "%";
                $stmt->bind_param( "ss", $s, $s ); 
                $this->is_archive = true; 
                $this->is_search = true;
                         
            /**
             * Alles andere
             */
            } else {
                                       
                if( $this->request( 'id' ) ) {
 
                    $stmt = $this->db->prepare( "
                        SELECT item.id, item.title, item.content, item.status, item.date, 
                        GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( SELECT name FROM term WHERE id=tr.term_id ) ) AS type
                        FROM item
                        INNER JOIN term_relation tr ON tr.object_id=item.id
                        WHERE item.id=?" );    
                    $s = $this->request( 'id' );
                    $stmt->bind_param( "i", $s );                    
                    $this->is_archive = false;
                    $this->is_single = true;
                     
                } else if( $this->request() && 'last' != key( $this->request() ) ) {
                
                    $key = key( $this->request() );
                    $val = $this->request( $key );
                    $numparam = is_numeric( $val );
                    $param_ = "%" . $key . "_" . $val . "%";                                        
                    $custom_query= "                    
                        SELECT item.id, item.title, item.content, item.status, item.date, 
                        GROUP_CONCAT( 
                            ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), 
                            '_',";                     
                    if( $numparam ) { 
                        $custom_query .= "( t.id )"; 
                    } else { 
                        $custom_query .= "( t.name )"; 
                    }                                       
                    $custom_query .= ") AS type
                        FROM item
                        JOIN term_relation tr ON tr.object_id=item.id
                        join term t on t.id=tr.term_id
                        GROUP BY item.id";                        
                    $custom_query .= " HAVING type LIKE (?)";

                    if ( $this->request( 'last' ) ) {
                        $custom_query .= " AND item.date < " . $this->request( 'last' );
                    }                    
                    $custom_query .= " AND item.status=1 ORDER BY item.date ASC";
                    $stmt = $this->db->prepare( $custom_query );
                    $stmt->bind_param( "s", $param_ );                    
                    $this->is_archive = true;
                    
                } else {
                                                          
                    $homepage = "                    
                        SELECT item.id, item.title, item.content, item.status, item.date, 
                        GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( SELECT name FROM term WHERE id=tr.term_id ) ) AS type                        
                        FROM item
                        JOIN term_relation tr ON tr.object_id=item.id
                        GROUP BY item.id
                    ";
                    if ( $this->request( 'last' ) ) {
                        $homepage .= " AND item.date < " . $this->request( 'last' );
                    }
                    $homepage .= " ORDER BY item.date ASC";
                    $stmt = $this->db->prepare( $homepage );     
                    $this->is_default = true; 
                                
                } 
                                                                 
            }          
            
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = array(); //echo "<pre>";
            while ( $row = $result->fetch_assoc() ) {

                $rows[] = $row;
              
            }

            $this->items = $rows;
            //print_r( $rows );              
                      
        }         
        if( $this->items ) {        
            $this->item_count = count($this->items);
        }          
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
        return $this->item_count < 1 ? false : true;        
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
            $item['link'] = "../".$this->request('type')."/".$item['id'];          
            /**
             * Wieviele Items auf dieser Seite schon angezeigt wurden
             */
            $this->displayed_this_page++;            
            if( $metadata ) {
                $tmp_data = $this->single_meta( $item['id'] );
                if( $tmp_data ) {
                    foreach( $tmp_data as $k => $v ) {
                        $item[$k] = $v;
                    }
                }
            }
            if( $content_length ) {                        
                /**
                 * Woerter und HTML Tags ganz lassen
                 */
                $tmp = strip_tags( html_entity_decode( $item['content'] ) );
                if ( strlen( $tmp ) > $content_length ) {
                    $item['content'] = preg_replace("/[^ ]*$/", '', substr( $tmp , 0, $content_length) ) . " ..."; 
                }                                                                                                 
            }            
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
        /**
         * Nur anzeigen wenn es noch Items gibt.
         */
        if( $this->more() ) {                                                  
            if( !$this->is_single ) { 
                echo "<!-- BeginNoIndex --><div class='sp-content-item'>\n<div class='sp-content-item-head'>";             
                if( $this->request('search') ) {
                    echo "<a rel='nofollow' href='?search=".$this->request('search')."&last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a>";
                } else if( $this->request('category') ) {
                    echo "<a rel='nofollow' href='?category=" . $this->request('category') . "&last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a>";    
                } else if( $this->request('tag') ) {                               
                    echo "<a rel='nofollow' href='?tag=" . $this->request('tag') . "&last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a>";                
                } else if( $this->request() ) {
                    $key = @key( $this->request() );
                    if( $key == 'last' ) {
                        echo "<a rel='nofollow' href='?last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a>";        
                    } else {
                        $val = $this->request( $key );
                        echo "<a rel='nofollow' href='?$key=$val&last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a>";
                    }                    
                } else {
                    echo "<a rel='nofollow' href='?last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a>";
                }
                echo "</div>\n</div>\n<!-- EndNoIndex -->\n";
            }                                           
        }                                                                       
    }                                         

}

?>
