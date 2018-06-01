<?php

/**
 * @author Manuel Zarat
 */

class system extends core {
    
    /**
     * @todo Header Hotfix
     */
    private $current_item = false;    
    function set_current_item($item) {
        $this->current_item = $item;
    }
    function get_current_item() {
        return $this->current_item;
    }

    /**
     * Mit Hilfe von Hooks koennen zu bestimmten Punkten der Laufzeit - zuvor in theme/settings.php definierte - Funktionen aufgerufen werden.
     */
    private $hooks = false;
    
    /**
     * Das Theme wird zur Laufzeit initiiert, kann mit Hilfe der theme/functions.php Hooks registrieren. 
     */
    private $theme = false;
    
    /**
     * @todo Priority
     */
    public final function add_action( $hook, $function ) {       
        $this->hooks[$hook][] = $function;      
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
    final function do_action( $hook, $params=false ) {
        if( isset( $this->hooks[$hook] ) ) { 
            foreach( $this->hooks[$hook] as $action ) {
                call_user_func( $action, $params );         
            }          
        }   
    } 
    
    /**
     * Prueft, ob theme/functions.php vorhanden ist und wenn ja - includen
     */
    final function theme_functions() {
        if( is_file( $theme_functions = ABSPATH . 'content' . DS . 'themes' . DS . $this->settings('site_theme') . DS . 'functions.php' ) ) {
            include $theme_functions;
        }
    } 
    
    /**
     * @deprecated siehe Hooks
     */
    private $positions = array( 'header', 'footer' );
    
    /**
     * @deprecated siehe Hooks
     */
    final function set_include( $position, $include ) {
        $this->positions[$position][] = $include;  
    }
    
    /**
     * @deprecated siehe Hooks
     */
    final function get_includes( $position ) {
        if( isset( $this->positions[$position] ) ) {
            foreach( $this->positions[$position] as $include ) {
                $includes[] = $include;   
            }   
            return $includes;            
        }   
        return false; 
    }      
    
    /**
     * Die eigentliche Eintrittsfunktion
     */
    final function init() { 
        $this->setup_theme();
        if($this->has_action(__FUNCTION__)) { 
            $this->do_action(__FUNCTION__); 
        }   
        $this->theme->render();     
    }
    
    private function setup_theme() {
        if(is_file($custom_theme_file = ABSPATH . 'content' . DS . 'themes' . DS . $this->settings('site_theme') . DS . 'theme.php')) {
            include $custom_theme_file;      
            $custom_theme = $this->settings('site_theme');                       
            $this->theme = new $custom_theme;        
        } else {          
            $this->theme = new theme();                     
        } 
        $this->theme_functions();
        if($this->has_action(__FUNCTION__)) {     
            $this->do_action(__FUNCTION__, $this->theme);       
        }              
    }
    
    /**
     * Parst den Querystring und gibt ihn als Array zurueck - sonst false
     * Wenn ein Parameter uebergeben wird, wird dieser (wenn vorhanden) zurueckgegeben - sonst false
     */
    final function request($key=false) {
        if($_SERVER['QUERY_STRING']) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
            if(false !== $key) {
                if(!empty($parameters[$key])) {       
                    /** 
                    SQL Injection 
                    @todo HOTFIX 
                    */
                    if($key == 'id') {
                        return (int)$parameters[$key];
                    } else {
                        return $parameters[$key];
                    }
                } else {
                    return false;            
                }        
            } else {  
                return ($parameters) ? $parameters : false;    
            }     
        }   
    }
    
    /**
     * @todo Prueft nur, welche Ordner in ../content/themes/* enthalten sind.
     */
    function installed_themes() {
        $themes = null;     
        if ($files = opendir( ABSPATH . 'content' . DS . 'themes')) {     
            while (false !== ($file = readdir($files))) { 
                if ($file!='.' && $file!='..') { 
                    $themes[] = $file;     
                }              
            }                  
            closedir($files);               
        }           
        return $themes;          
    }
    
    /**
     * Uebersetzt einen String aus der Sprachdatei ../system/lang/*
     */
    function _t($str) {    
        include ABSPATH . 'system' . DS . 'lang' . DS . 'lang.php';
        if( is_file( $langfile = ABSPATH . 'system' . DS . 'lang' . DS . 'lang-' . $this->settings('site_language') . '.php' ) ) {
            include $langfile;    
        }    
        return isset($lang[$str]) ? $lang[$str] : "Error: Language file is missing or corrupt.";    
    }

}

?>
