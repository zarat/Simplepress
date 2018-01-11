<?php

/**
 * @author Manuel Zarat
 * 
 */

class system extends core {

    /**
     * Ein THEME, das definiert, wie der Seitenaufbau ablaeuft und aussieht.
     * Das Theme muss zur Laufzeit initiiert werden, kommt aber aus der DB
     * 
     * @var object Theme Objekt
     * 
     */
    private $theme = false;
    
    /**
     * Eintrittsfunktion
     * Hier koennen Objekte global initiiert werden.
     * 
     */
    final function init() {    
        $this->setup_theme();
        $this->theme->render();    
    }
    
    /**
     * Wenn ein Theme File mit dem Namen wie das Theme heisst '<THEME-DIR>/<THEME-NAME>.php' existiert (was es sollte!) wird es hier eingebunden und initiiert.
     * 
     * Das ganze muss aber natuerlich auch laufen, wenn ein Theme kaputt oder geloescht ist.
     * 
     * Ansonsten wird ein Dummy Theme initiiert.
     * 
     * @todo Was wenn das Theme nicht funktioniert? Fallback?
     *
     * @return false
     *
     */
    private function setup_theme() {
        /**
         * Pruefe, ob Themedatei existiert
         * 
         */
        if(is_file($custom_theme_file=ABSPATH . 'content' . DS . 'themes' . DS . $this->settings('site_theme') . DS . 'theme.php')) {
            /**
             * Wenn ja, includen und Theme initiieren
             * 
             */
            include $custom_theme_file; 
            $custom_theme = $this->settings('site_theme');
            $this->theme = new $custom_theme;
        } else {
            /**
             * Wenn nicht, wird das Theme Dummy aufgerufen
             * Stylesheet u.a fehlen dann aber..
             * Nochmal auf settings.php pruefen? Dzt in theme::default_header()
             * @see theme.php
             *
             */
            $this->theme = new theme();
        }
        return false;               
    }
    
    /**
     * Der Querystring ist wegen URL-rewriting (spaeter) wichtig
     *
     * @access public
     * @param string Fuer einen bestimmten Parameter
     * @return array/string/false
     * 
     */
    final function request($key=false) {
        if($_SERVER['QUERY_STRING']) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
            if(false !== $key && !empty($parameters[$key])) {
                return $parameters[$key];
            } else {
                return $parameters;
            }
        }
        return false;
    }
    
    /**
     * @todo Prueft dzt nur, welche Ordner im Ordner ../content/themes/* enthalten sind.
     * 
     */
    function installed_themes() {
        $themes = null;        
        if ($files = opendir( ABSPATH . 'content' . DS . 'themes')) {        
            while (false !== ($file = readdir($files))) { if ($file!='.' && $file!='..'){ $themes[] = $file; } }        
            closedir($files);    
        }        
        return $themes;    
    }
    
    /**
     * Uebersetzt einen String aus einer Sprachdatei  ../system/lang/*
     * 
     * @todo
     * 
     */
    function _t($str) {
        include ABSPATH . 'system' . DS . 'lang' . DS . 'lang.php';
        if(is_file(ABSPATH . 'system' . DS . 'lang' . DS . 'lang-' . $this->settings('site_language') . '.php')) {
            include ABSPATH . 'system' . DS . 'lang' . DS . 'lang-' . $this->settings('site_language') . '.php';
        }
        return isset($lang[$str]) ? $lang[$str] : "Language file is missing or corrupt.";
    }

}

?>
