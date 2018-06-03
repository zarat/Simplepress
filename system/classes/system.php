<?php

/**
 * @author Manuel Zarat
 */

class system extends core {
    
    /**
     * @todo Header Hotfix
     */
    private $current_item = false;    

    /**
     * Mit Hilfe von Hooks koennen zu bestimmten Punkten der Laufzeit - zuvor in theme/settings.php definierte - Funktionen aufgerufen werden.
     */
    private $hooks = false;
    
    /**
     * Main function
     */
    final function init() { 
        $this->setup_theme();
        if($this->has_action(__FUNCTION__)) { 
            $this->do_action(__FUNCTION__); 
        }   
        $this->theme->render();     
    }
    
    /**
     * Prueft, ob theme/functions.php vorhanden ist und wenn ja - includen
     */
    final function theme_functions() {
        if( is_file( $theme_functions = ABSPATH . 'content' . DS . 'themes' . DS . $this->settings('site_theme') . DS . 'functions.php' ) ) {
            include $theme_functions;
        }
    } 
    
    final function setup_theme() {
        if(is_file($custom_theme_file = ABSPATH . 'content' . DS . 'themes' . DS . $this->settings('site_theme') . DS . 'theme.php')) {
            include $custom_theme_file;      
            $custom_theme = $this->settings('site_theme');                       
            $this->theme = new $custom_theme;        
        } else {          
            $this->theme = new theme();                     
        } 
        $this->theme_functions();
        if($this->has_action(__FUNCTION__)) {     
            $this->do_action(__FUNCTION__);       
        }              
    }
    
    /**
     * Uebersetzt einen String aus der Sprachdatei ../system/lang/*
     */
    final function _t($str) {    
        include ABSPATH . 'system' . DS . 'lang' . DS . 'lang.php';
        if( is_file( $langfile = ABSPATH . 'system' . DS . 'lang' . DS . 'lang-' . $this->settings('site_language') . '.php' ) ) {
            include $langfile;    
        }    
        return isset($lang[$str]) ? $lang[$str] : "Error: Language file is missing or corrupt.";    
    }
    
    /**
     * @todo Priority
     */
    public final function add_action( $hook, $action ) {       
        $this->hooks[$hook][] = $action;      
    }
    
    /**
     * Prueft, ob Hooks fuer eine Funktion registriert wurden
     * 
     */
    final function has_action( $hook ) {
        return isset( $this->hooks[$hook] );
    }
    
    /**
     * @todo Priority
     */
    final function do_action( $hook ) {    
        if( isset( $this->hooks[$hook] ) ) { 
            if( is_array( $this->hooks[$hook][0] ) ) {  
                call_user_func_array( $this->hooks[$hook][0][0], array( $this->hooks[$hook][0][1] ) );  
            } else { 
                call_user_func( $this->hooks[$hook][0] );
            }                    
        }           
    }   
    
    function set_current_item($item) {
        $this->current_item = $item;
    }
    
    function get_current_item() {
        return $this->current_item;
    }
    
    /**
     * Main Path
     */ 
    final function path() {            
        switch($this->request('type')) {
            case "post":
            case "page":
                $this->view = "single"; 
                break;
            case "category":
            case "tag":
            case "search":
                $this->view = "archive"; 
                break;
            default:
                $this->view = "default"; 
                break;
        }           
        ob_start();                     
        switch($this->view) {         
            case "single":            
                $include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single.php"; 
                $custom_include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->request('type') . ".php";                                                
                if( ( $item = $this->single( array('type' => $this->request('type'), 'id' => $this->request('id'), 'metadata' => true) ) ) == false ) { 
                    $this->error404(); 
                    break; 
                }                
                $this->set_current_item($item);                 
                if( is_file( $custom_include_file ) ) {                
                    include $custom_include_file;                    
                } else if( is_file( $include_file) ){                
                    include $include_file;                  
                } else {
                    /**
                     * @todo Kein Single Template vorhanden!
                     */
                    echo "<div class=\"sp-content\">\n";
                    echo "<div class='sp-content-item'>\n";
                    echo "<div class=\"sp-content-item-head\">" . $item['title']. "</div>\n";
                    echo "<div class=\"sp-content-item-body\">" . $item['content']. "</div>\n";
                    echo "</div>\n";
                    echo "</div>\n\n";                              
                }                                
            break;            
            case "archive":            
                $include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive.php";
                $custom_include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive-" . $this->request('type') . ".php";
                $archive = new archive();
                $archive->archive_init();
                if( $this->request( 'id' ) ) { 
                    $item = $this->single( array( 'id' => $this->request( 'id' ) ) );
                    $this->set_current_item($item);
                }           
                if( $archive->count_posts() < 1) { 
                    $this->error404(); 
                    break; 
                }                                           
                if( is_file( $custom_include_file ) ) {                
                    include $custom_include_file;                    
                } else if( is_file( $include_file ) ) {                
                    include $include_file;                  
                } else {
                    /**
                     * @todo Kein Single Template vorhanden!
                     */
                    echo "<div class='sp-content'>\n";
                    while( $archive-> have_posts() ) {
                        $item = $archive->the_post();
                        echo "<div class='sp-content-item'>\n";
                        echo "<div class='sp-content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title']. "</a></div>\n";
                        echo "<div class='sp-content-item-body'>" . $item['content']. "</div>\n";
                        echo "</div>\n";
                    }
                    echo "</div>\n\n";
                }               
            break;                        
            default:             
                /**
                 * Default wird ein Archiv der letzten Items (post) gezeigt.
                 */
                $latest = new archive();
                $latest->archive_init();                
                if( $latest->count_posts() < 1) { 
                    $this->error404(); 
                    break; 
                }  
                $include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";
                $custom_include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "homepage.php";
                if( is_file( $custom_include_file ) ) {                
                    include $custom_include_file;                    
                } else if( is_file( $include_file ) ) {                
                    include $include_file;                   
                } else {
                    /**
                     * @todo Kein Single Template vorhanden!
                     */
                    echo "<div class=\"sp-content\">\n";
                    while( $latest-> have_posts() ) {
                        $item = $latest->the_post();
                        echo "<div class='sp-content-item'>\n";
                        echo "<div class='sp-content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title']. "</a></div>\n";
                        echo "<div class='sp-content-item-body'>" . $item['content']. "</div>\n";
                        echo "</div>\n";
                    }
                    echo "</div>\n\n";
                }                
            break;                    
        }        
        $content = ob_get_contents();         
        ob_end_clean();         
        return $content;               
    }
    
    final function sidebar() {         
        $include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "sidebar.php"; 
        $custom_include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "sidebar-" . $this->request('type') . ".php";            
        if( is_file( $custom_include_file ) ) {                
            include $custom_include_file;                    
        } else if( is_file( $include_file ) ) {                
            include $include_file;                   
        } else {             
            echo "<div class='sp-sidebar'>";                   
                echo "<div class='sp-sidebar-item'>";
                    echo "<div class='sp-sidebar-item-head'>Suche</div>";
                    echo "<div class='sp-sidebar-item-box'>\n";
                        echo "<div class='sp-sidebar-item-box-body'><div class='container'><form><input type='hidden' name='type' value='search'><input type='text' name='term'></form></div></div>\n";
                    echo "</div>\n";
                echo "</div>";                 
                echo "<div class='sp-sidebar-item'>";
                    echo "<div class='sp-sidebar-item-head'>Kategorien</div>";
                    foreach($this->archive( array('select' => 'id,title','from' => 'item','where' => 'status=1 AND type="category"') ) as $cat) {
                        echo "<div class='sp-sidebar-item-box'>\n";
                            echo "<div class='sp-sidebar-item-box-head'><a href='../?type=category&id=$cat[id]'>$cat[title]</a></div>\n";
                        echo "</div>\n";
                    }
                echo "</div>"; 
            echo "</div>";                      
        }                              
    }

}

?>
