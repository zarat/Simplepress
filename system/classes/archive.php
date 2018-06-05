<?php

/**
 * @author Manuel Zarat
 */

class archive extends system {

private $max_per_page = 10;
private $displayed_this_page = 0;
private $last = 0;
private $item_count = 0;

public $items = [];

    function archive_init() {    
        if( !empty( $this->request( 'last' ) ) ) {         
            $this->last = $this->request( 'last' );            
        }        
        $this->fill_items();         
    }
    
    /**
     * Fuellt das Array(posts) mit den gefundenen Items
     * 
     * @todo Suche & Blaettern
     */
    final function fill_items() {     
        if( $this->request( 'type' ) && $this->request( 'type' ) == 'category' ) {        
            if( $this->request( 'last' ) ) {   
                $this->items = $this->select( array( "select" => "*", "from" => "item", "where" => "status=1 AND type='post' AND category='" . $this->request( 'id' ) . "' AND id < " . $this->request( 'last' ) . " ORDER BY id ASC") );                             
            } else {                                   
                $this->items = $this->select( array( "select" => "*", "from" => "item", "where" => "status=1 AND type='post' AND category='" . $this->request( 'id' ) . "' ORDER BY id ASC") );                           
            }            
        } elseif( $this->request( 'type' ) && $this->request( 'type' ) == 'search' ) {             
            if( $this->request( 'last' ) ) {                      
                $this->items = $this->select( array( "select" => "*", "from" => "item", "where" => "status=1 AND type IN ('page','post') AND ( title LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' OR content LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' ) AND id < " . $this->request( 'last' ) . " ORDER BY id ASC") );                             
            } else {                   
                $this->items = $this->select( array( "select" => "*", "from" => "item", "where" => "status=1 AND type IN ('page','post') AND ( title LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' OR content LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' ) ORDER BY id ASC") );               
            }                                            
        } else {  
            if( $this->request( 'last' ) ) {    
                $this->items = $this->select( array( "select" => "*", "from" => "item", "where" => "status=1 AND type='post' AND id < " . $this->request( 'last' ) . " ORDER BY id ASC") );                  
            } else {  
                $this->items = $this->select( array( "select" => "*", "from" => "item", "where" => "status=1 AND type='post' ORDER BY id ASC") );                
            }  
        }  
        $this->post_count = sizeof( $this->items );   
    }

    function count_items() { 
        return ($this->items);        
    }

    function have_items() {                    
        if( $this->displayed_this_page >= $this->max_per_page ) {                
            $this->is_page_limit = true;                        
            return false;                        
        }            
        return ( count($this->items) > 0) ? true : false;            
    }
    
    function more() {    
        return ( count($this->items) > 0) ? true : false;        
    }

    function the_item( $strip_tags = false, $content_length = false ) {            
        if( $this->more() ) {        
            $post = array_pop( $this->items );                    
            $this->last = $post['id'];            
            $this->displayed_this_page++; 
            if($strip_tags ) {             
                $post['content'] = strip_tags($post['content']);                 
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

    function pagination() {      
        if( $this->more() ) {                       
            if( $this->request( 'type' ) == 'category' ) {                                      
                echo "<div class='sp-content-item'><div class='sp-content-item-head'><a rel='nofollow' href='../?type=category&id=" . $this->request( 'id' ) . "&last=" . $this->last . "'>&auml;ltere Beitr&auml;ge</a></div></div>";                                                                
            } else {                
                echo "<div class='sp-content-item'><div class='sp-content-item-head'><a rel='nofollow' href='../?last=" . $this->last . "'>&auml;ltere Beitr&auml;ge</a></div></div>";           
            }                              
        }                            
    }

}

?>
