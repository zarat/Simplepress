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
                } else {                
                    include $include_file;                   
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
                } else {                
                    include $include_file;                   
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
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";                
            break;                    
        }        
        $content = ob_get_contents();         
        ob_end_clean();         
        return $content;               
    }

}

?>
