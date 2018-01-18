<?php

/**
 * Simplepress Archiv
 * 
 * @author Manuel Zarat
 * 
 */

class archive extends system {

private $max_per_page = 5;
private $displayed_this_page = 0;
private $last = 0;

/**
 * Ein Array mit den Inhalten
 * 
 */
private $posts = array();

private $post_count = 0;

    function archive_init() {
        if( !empty( $this->request( 'last' ) ) ) {        
            $this->last = $this->request( 'last' );            
        }        
        $this->fill_posts();        
    }
    
    /**
     * Fuellt das Array $posts mit den gefundenen Eintraegen
     * 
     * @todo kategorien ausnehmen!!!!!
     * 
     */
    final function fill_posts() {        
        if( $this->request( 'last' ) ) {         
            $this->posts = $this->select( array( "select" => "*", "from" => "object", "where" => "type='post' AND category='" . $this->request( 'id' ) . "' AND id < " . $this->request( 'last' ) . " ORDER BY id ASC") );      
        } else {        
            $this->posts = $this->select( array( "select" => "*", "from" => "object", "where" => "type='post' AND category='" . $this->request( 'id' ) . "' ORDER BY id ASC") );    
        } 
        $this->post_count = count( $this->posts );       
    }

    function have_posts() {            
        if( $this->displayed_this_page >= $this->max_per_page )  {        
            $this->is_page_limit = true;            
            return false;            
        }        
        return ( count($this->posts) > 0) ? true : false;    
    }
    
    function the_post() {   
        $post = @array_pop( $this->posts );        
        $this->displayed_this_page++;        
        $this->last = $post['id'];        
        return $post;        
    }

    function pagination() {
        if( $this->displayed_this_page >= $this->max_per_page && $this->post_count > 1 ) {
            echo "<div class='sp-content-item'><div class='sp-content-item-head'><a href='../?type=" . $this->request( 'type' ) . "&id=" . $this->request( 'id' ) . "&last=" . $this->last . "'>&auml;ltere Beitr&auml;ge</a></div></div>"; 
        }         
    }

}

?>
