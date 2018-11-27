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
        if( $this->request( 'last' ) ) { $this->last = intval( $this->request( 'last' ) ); }               
        $this->fill_items( $config );         
    }
    
    /**
     * Fuellt das Array(items) mit den gefundenen Items
     * 
     * @return void
     */
    final function fill_items( $config = false ) {        
        global $hooks;                                   
        if( $this->request( 'search' ) ) {
            $s = "%" . htmlentities( $this->request( 'search' ) ) . "%";            
            $search_query = "SELECT item.id, item.title, item.content, item.status, item.date,";                   
            $search_query .= "GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( t.id ) ) AS type_int, ";
            $search_query .= "GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( t.name ) ) AS type_str ";
            $search_query .= "FROM item ";
            $search_query .= "INNER JOIN term_relation tr ON tr.object_id=item.id ";                    
            $search_query .= "INNER JOIN term t on t.id=tr.term_id ";
            $search_query .= "WHERE item.status=1 ";
            $search_query .= "GROUP BY item.id ";
            $search_query .= "HAVING ( item.title LIKE ('$s') OR item.content LIKE ('$s') ) ";
            if( $this->request('last') ) $search_query .= "AND item.date < " . $this->request('last') . " ";
            $search_query .= "ORDER BY item.date ASC"; 
            //echo $search_query;                       
            $the_items = array();
            $result = $this->query( $search_query ); 
            while ( $row = $result->fetch_assoc() ) {
                if( !empty($row['id']) ) {
                    $the_items[] = $row;
                }              
            } 
            $this->items = $the_items;
            $this->item_count = count($this->items);
            $this->is_archive = true; 
            $this->is_search = true;
            return;                     
        } else {
            if( $this->request( 'id' ) ) {                
                $id = $this->request( 'id' );
                $single_query = "SELECT item.id, item.title, item.content, item.status, item.date,"; 
                $single_query .= "GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( t.id ) ) AS type_int, ";
                $single_query .= "GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( t.name ) ) AS type_str ";
                $single_query .= "FROM item ";
                $single_query .= "INNER JOIN term_relation tr ON tr.object_id=item.id ";                    
                $single_query .= "INNER JOIN term t on t.id=tr.term_id ";
                $single_query .= "WHERE item.status=1 AND item.id=$id ";
                // echo $single_query;
                $the_items = array();
                $result = $this->query( $single_query ); 
                while ( $row = $result->fetch_assoc() ) {
                    if( !empty($row['id']) ) {
                        $the_items[] = $row;
                    }              
                }
                $this->items = $the_items;
                $this->item_count = count($this->items);
                $this->is_archive = false;
                $this->is_single = true;
                return;
            } else if( $this->request() && 'last' != key( $this->request() ) ) {                
                $custom_query = "SELECT item.id, item.title, item.content, item.status, item.date, "; 
                $custom_query .= "GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( t.id ) ) AS type_int, ";
                $custom_query .= "GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( t.name ) ) AS type_str ";
                $custom_query .= "FROM item ";
                $custom_query .= "INNER JOIN term_relation tr ON tr.object_id=item.id ";                    
                $custom_query .= "INNER JOIN term t on t.id=tr.term_id "; 
                $custom_query .= "WHERE item.status=1 ";
                if( $this->request('last') ) $custom_query .= "AND item.date < " . $this->request('last') . " ";
                $custom_query .= "GROUP BY item.id ";
                $custom_query .= "HAVING type_str like (?) or type_int like (?) ";           
                $custom_query .= "ORDER BY item.date ASC ";                
                $data = array();                
                $statement = $this->db->prepare( $custom_query );
                $stop = array('id','last');                
                foreach( $this->request() as $k => $v ) {                 
                    if( in_array( $k, $stop ) ) continue;
                    $param = "%" . $k . "_" . $v . "%"; 
                    $statement->bind_param("ss", $param, $param);
                    $statement->execute();
                    $result = $statement->get_result();
                    while( $row = $result->fetch_assoc() ) { 
                        $data[] = $row; 
                    }                    
                }
                $this->items = array_map( "unserialize", array_unique( array_map("serialize", $data) ) );
                //echo "<pre>";print_r( $this->items );echo "</pre>";            
                $this->item_count = count($this->items);
                $this->is_archive = true;
                return;                
            } else {                                                      
                $homepage_query = "SELECT item.id, item.title, item.content, item.status, item.date,"; 
                $homepage_query .= "GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( t.id ) ) AS type_int, ";
                $homepage_query .= "GROUP_CONCAT( ( SELECT taxonomy FROM term_taxonomy WHERE id=tr.taxonomy_id ), '_', ( t.name ) ) AS type_str ";
                $homepage_query .= "FROM item ";
                $homepage_query .= "INNER JOIN term_relation tr ON tr.object_id=item.id ";                    
                $homepage_query .= "INNER JOIN term t on t.id=tr.term_id "; 
                $homepage_query .= "WHERE item.status=1 ";
                $homepage_query .= "GROUP BY item.id ";
                
                /** Klammer = Wichtig! */               
                $custom_homepage_query = "HAVING ( type_int LIKE ('%type_post%') OR type_str LIKE ('%type_post%') ) ";
                $custom_homepage_query = $hooks->apply_filters('archive_init_homepage', $custom_homepage_query);
                $homepage_query .= $custom_homepage_query;
                
                if( $this->request('last') ) {
                    $homepage_query .= "AND item.date < " . $this->request('last') . " ";
                }
                $homepage_query .= " ORDER BY item.date ASC";
                //echo $homepage_query;
                $the_items = array();
                $result = $this->query( $homepage_query ); 
                while ( $row = $result->fetch_assoc() ) {
                    if( !empty($row['id']) ) {
                        $the_items[] = $row;
                    }              
                }
                $this->items = $the_items;
                $this->item_count = count($this->items);     
                $this->is_default = true; 
                return;                
            }                                                              
        }                   
    }

    /**
     * Gibt aus, ob noch Items vorhanden sind die AUF DIESER SEITE angezeigt werden!
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
     */
    function more() {    
        return $this->item_count < 1 ? false : true;        
    }

    /**
     * Gibt das aktuelle Item aus.
     */
    function the_item( $config = false ) {
        $metadata = true; $content_length = false; $html = true; $strip_tags = false;
        if( $config ) { extract( $config ); }
        if( $this->more() ) {        
            $this->item_count--; 
            $item = array_pop( $this->items );
            $this->last = $item['date']; 
            $this->last_timestamp = $item['date'];           
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
                /** Woerter und HTML Tags ganz lassen */
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
        $url_ = $ps = array();
        $url = "?";
        if( $this->request() ) {
            foreach( $this->request() as $k => $v ) { 
                if( $k == 'last' ) continue;
                $url_[] = "$k=$v"; 
            }
            $url .= implode("&", $url_);
        }       
        if( $this->last ) {
            $url .= "&last=" . $this->last;
        }            
        if( $this->more() ) {                                                  
            echo "<!-- BeginNoIndex --><div class='sp-content-item'>\n<div class='sp-content-item-head'>";
            if( !$this->is_single ) {
                echo "<a rel='nofollow' href='$url'>&auml;ltere Beitr&auml;ge</a>";
            }
            echo "</div>\n</div>\n<!-- EndNoIndex -->\n";
        }                                                              
    }                                         

}

?>
