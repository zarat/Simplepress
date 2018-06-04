<?php

/**
 * @author Manuel Zarat
 */

class system extends core {

    private $current_item = false;    

    private $hooks = false;

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
     * Regtistriert eine Action, die bei Aufruf eines Hooks getriggert wird.
     */
    public final function add_action( $hook, $action = false, $params = false ) {       
        if( !$action ) {
            $this->hooks[$hook] = array();
            return true; 
        }
        if( $params ) { 
            $this->hooks[$hook][] = array( $action, $params ); 
        } else {
            $this->hooks[$hook][] = $action;
        } 
        return true;  
    }
    
    /**
     * Prueft, ob Hooks zu einer bestimmten Action registriert wurden.
     */
    final function has_action( $hook ) {
        return isset( $this->hooks[$hook] );
    }
    
    /**
     * Ruft alle registrierten Actions eines Hooks auf.
     */
    final function do_action( $hook ) {        
        if( isset( $this->hooks[$hook] ) ) {         
            if( is_array( $this->hooks[$hook][0] ) ) {              
                call_user_func_array( $this->hooks[$hook][0][0], array( $this->hooks[$hook][0][1] ) );                  
            } else {             
                call_user_func( $this->hooks[$hook][0], $this );                
            }                                
        }           
    }   
    
    function set_current_item($item) {
        $this->current_item = $item;
    }
    
    function get_current_item() {
        return $this->current_item;
    }

}

?>
