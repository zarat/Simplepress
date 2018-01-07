<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * @license http://opensource.org/licenses/MIT
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
     * Das Custom Theme sollte dann natuerlich von THEME ableiten.
     * 
     * Ansonsten wird ein Dummy Theme initiiert.
     * 
     * @todo
     *
     * @return false
     *
     */
    private function setup_theme() {
        if(is_file($custom_theme_file=ABSPATH . 'content' . DS . 'themes' . DS . $this->settings('site_theme') . DS . 'theme.php')) {
            include $custom_theme_file; 
            $custom_theme = $this->settings('site_theme');
            $this->theme = new $custom_theme;
        } else {
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

}

?>
