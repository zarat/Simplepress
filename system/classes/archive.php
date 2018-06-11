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
     * Fuellt das Array(posts) mit den gefundenen Items
     * 
     * @todo Suche & Blaettern
     * 
     * @param $config array optional see $system->select()
     * 
     * @return void
     */
    final function fill_items( $config = false ) {                               
        if( false !== $config ) {            
            $this->items = $this->select( $config );            
        } else {        
            $where = "status=1"; 
            if ( $this->request( 'type' ) == 'category' ) {
                $where .= " AND type IN ('page','post') AND category=" . $this->request( 'id' );
            } else if( $this->request( 'type' ) == 'search' ) {
                $where .= " AND type IN ('page','post') AND ( title LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' OR content LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' ) ";
            } else if( $this->request( 'type' ) == 'post' || $this->request( 'type' ) == 'page' ) {
                $where .= " AND id=" . $this->request( 'id' );
            } else {
                $where .= " AND type='post' ";
            }
            if ( $this->request( 'last' ) ) {
                $where .= " AND date < " . $this->request( 'last' );
            } 
            $where .= " ORDER BY date ASC";               
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
     * @param bool $strip_tags Sollen HTML Tags entfernt werden?
     * @param int $content_length Auf wie viele Zeichen soll der Inhalt gekuerzt werden.
     * 
     * @return array()|bool Das Item als Array oder false
     */
    function the_item( $strip_tags = false, $content_length = false ) {            
        if( $this->more() ) {        
            $post = array_pop( $this->items );                    
            $this->last = $post['id']; 
            $this->last_timestamp = $post['date'];           
            $this->displayed_this_page++; 
            
            $post['content'] = html_entity_decode( $post['content'] );
            
            if($strip_tags ) {             
                $post['content'] = strip_tags( $post['content'] );                 
            }                    
            if($content_length) {            
                $line=$post['content'];                
                if (preg_match('/^.{1,'.$content_length.'}\b/s', $post['content'], $match)) {                
                    $post['content'] = $match[0];                   
                }               
            }                               
            return $post;             
        }        
        return false;               
    }

    /**
     * Zeigt Links zu der naechsten Seite eines Archives an.
     * 
     * @return html 
     */
    function pagination() {      
        if( $this->more() ) {                       
            if( $this->request( 'type' ) == 'category' ) {                                      
                echo "<div class='sp-content-item'><div class='sp-content-item-head'><a rel='nofollow' href='../?type=category&id=" . $this->request( 'id' ) . "&last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a></div></div>";                                                                
            } else {                
                echo "<div class='sp-content-item'><div class='sp-content-item-head'><a rel='nofollow' href='../?last=" . $this->last_timestamp . "'>&auml;ltere Beitr&auml;ge</a></div></div>";           
            }                              
        }                            
    }

}

?>
