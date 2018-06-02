<?php

/**
 * @author Manuel Zarat
 */

class archive extends system {

private $posts = [];
private $max_per_page = 10;
private $displayed_this_page = 0;
private $last = 0;
private $post_count = 0;

    function archive_init() {
        /**
         * Wird geblaettert?
         */
        if( !empty( $this->request( 'last' ) ) ) { 
            /**
             * Wenn ja, was war die letzte id?
             */
            $this->last = $this->request( 'last' );
        }
        $this->fill_posts(); 
    }
    
    /**
     * Fuellt das Array(posts) mit den gefundenen Items
     * 
     * @todo Archiv Pagination
     */
    final function fill_posts() { 
        /**
         * Wird in einem Archiv geblaettert, muss dieses im Query enthalten bleiben.
         * 
         * @todo Archiv Pagination
         */
        if( $this->request( 'type' ) && $this->request( 'type' ) == 'category' ) {
            if( $this->request( 'last' ) ) {      
                $this->posts = $this->select( array( "select" => "*", "from" => "object", "where" => "status=1 AND type='post' AND category='" . $this->request( 'id' ) . "' AND id < " . $this->request( 'last' ) . " ORDER BY id ASC") );                 
            } else {                   
                $this->posts = $this->select( array( "select" => "*", "from" => "object", "where" => "status=1 AND type='post' AND category='" . $this->request( 'id' ) . "' ORDER BY id ASC") );               
            } 
        } elseif( $this->request( 'type' ) && $this->request( 'type' ) == 'search' ) { 
            /**
             * @todo Suche mit AND, OR und IN
             */
            if( $this->request( 'last' ) ) {      
                $this->posts = $this->select( array( "select" => "*", "from" => "object", "where" => "status=1 AND type IN ('page','post') AND ( title LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' OR content LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' ) AND id < " . $this->request( 'last' ) . " ORDER BY id ASC") );                 
            } else {                   
                $this->posts = $this->select( array( "select" => "*", "from" => "object", "where" => "status=1 AND type IN ('page','post') AND ( title LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' OR content LIKE '%" . htmlentities( $this->request( 'term' ) ) . "%' ) ORDER BY id ASC") );               
            }                                
        } else {   
            if( $this->request( 'last' ) ) {                     
                $this->posts = $this->select( array( "select" => "*", "from" => "object", "where" => "status=1 AND type='post' AND id < " . $this->request( 'last' ) . " ORDER BY id ASC") );                  
            } else {                    
                $this->posts = $this->select( array( "select" => "*", "from" => "object", "where" => "status=1 AND type='post' ORDER BY id ASC") );                
            }  
        }  
        $this->post_count = sizeof( $this->posts );                   
    }
    
    /**
     * @deprecated Wird vom Theme verwendet
     */
    function count_posts() {    
        return ($this->posts);
    }

    /**
     * Wird im Loop verwendet.
     */
    function have_posts() {                
        if( $this->displayed_this_page >= $this->max_per_page )  {        
            $this->is_page_limit = true;            
            return false;            
        }    
        return ( count($this->posts) > 0) ? true : false;    
    }
    
    function more() {
        return ( count($this->posts) > 0) ? true : false;
    }
    
    /**
     * Wird im Loop verwendet.
     */
    function the_post( $strip_tags = false, $content_length = false ) {        
        if( $this->more() ) {
            $post = array_pop( $this->posts );        
            $this->last = $post['id'];
            $this->displayed_this_page++;         
            if($strip_tags ) { $post['content'] = strip_tags($post['content']); }        
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
     * Gibt die Pagination Links aus.
     * 
     * Hier koennte man noch die Funktion aus
     */
    function pagination() {  
        if( $this->more() ) {               
            if( $this->request( 'type' ) == 'category' ) {                          
                echo "<div class='sp-content-item'><div class='sp-content-item-head'><a href='../?type=category&id=" . $this->request( 'id' ) . "&last=" . $this->last . "'>&auml;ltere Beitr&auml;ge</a></div></div>";                                                    
            } else {
                echo "<div class='sp-content-item'><div class='sp-content-item-head'><a href='../?last=" . $this->last . "'>&auml;ltere Beitr&auml;ge</a></div></div>";
            }                      
        }                         
    }
}

?>
