<?php

/** 
 * SimplePress Theme Dummy
 *
 * @author Manuel Zarat
 * @date 05.01.2018
 * @license http://opensource.org/licenses/MIT
 * 
 * @todo THEMES sollten eine Datei "theme.php" in Root Verzeichnis beinhalten, die im Fall dieses Dummy Theme ueberschreibt.
 * @todo THEMES sollten einen Unterordner "<THEME-DIR>/views" beinhalten, in dem eigene VIEWS definiert werden koennen.
 * 
 */

class theme extends system {

    /**
     * THEMES muessen VIEWS definieren, die vorgeben, welche Positionen ein Theme hat.
     * So koennen auch Themes mit individuellen VIEWS arbeiten (ShoppingCart, Listen, Profil, ...)
     *
     * @todo Eigene VIEWS sollen manuell erstellbar sein
     * 
     */
    private $views = array('single','archive','index');
    
    /**
     * Themes muessen Positionen aufweisen, in welche man Inhalte einfuegen kann.
     * 
     * @see self::set_include() 
     *
     */
    private $positions = array('header','footer');

    /**
     * Zu bestimmten Zeiten waehrend dem Seitenaufbau werden SYSTEM Events geworfen.
     * 
     * @todo Events rufen Trigger auf, die den Aufbau zur Laufzeit erweitern sollen.
     * 
     */
    private $events = array();
    
    /**
     * Zu jedem registrierten Event werden zugehoerige Trigger aufgerufen.
     * 
     * @todo Eigene Trigger sollen registriert werden koennen.
     * 
     */
    private $triggers = array();
    
    /**
     * Registriert Inhalte zum Einbinden an bestimmten Positionen (Hooks ->)
     * 
     * @todo
     * 
     * @param string $position Wo soll es eingefuegt werden
     * @param string $include Was soll eingefuegt werden
     * 
     * @return void
     * 
     * Example: set_include('header','i am the header');
     */
    function set_include($position,$include) {
        //$this->include_positions[$position][] = $include;
        $this->views[$position][] = $include;
    }
    
    /**
     * Zeigt einzubindende Inhalte zu einem Event an.
     * 
     * @todo 
     * @testing
     * 
     * @param string position
     * 
     */
    function get_includes($position) {
        foreach($this->views[$position] as $include) {
            echo $include . "\n";
        }
    }
    
    /**
     * Setzt einen Trigger zu einem bestimmten Event
     * 
     * @todo Soll das Event ueberschreiben oder ueberladen koennen.
     *
     */
     function set_trigger($event, $trigger) { }
     
     /**
      * Ruft Trigger zu einem bestimmten Event auf.
      * 
      * @todo
      *
      */
    function execute_triggers($event) { }
    
    /**
     *  Bindet das angeforderte View Template ein (archiv,single,custom..)
     *  Muss aufgeteilt werden um Hooks einzubauen.
     *  
     * @todo
     * @deprecated
     *  
     * @return false
     * 
     */ 
    function object_path() { 
        $this->type = !empty($this->request('type')) ? $this->request('type') : "default"; // default
        $this->id = !empty($this->request('id')) ? $this->request('id') : false; // 0
        switch($this->type) {
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
        
        $system = new system();
        
        switch($this->view) {        
            case "single": 
                $item = $this->single($this->type,$this->id); 
                if(is_file(ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->type . ".php")) {                
                    include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->type . ".php";                    
                } else {                
                    include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single.php";                   
                }
            break;
            case "archive":
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive.php";
            break;            
            case "default": 
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";
            break;        
        }    
        return false;      
    }

    /**
     * Ausgabe aller Komponenten
     * Die einzelnen Schritte sind in Funktionen aufgeteilt, um Trigger aufrufen zu koennen.
     * 
     * @todo
     * 
     * @return string
     * 
     */
    function render() {
    
        /** Theme Settings importieren */
        include ABSPATH . 'content' . DS . 'themes' . DS . $this->settings('site_theme') . DS . 'functions.php';
    
        $this->get_header();

        echo "<div class='main-wrapper'>\n";
        
        $this->object_path();
        
        echo "</div>";
        
        $this->get_footer();
    
    }
    
    /**
     * Gibt den HTML Header aus
     * 
     */
    function get_header() {
        echo "<html>\n";
        echo "<head>\n";
        $this->get_includes('header');
        echo "</head>\n";
        echo "<body>";
    }
    
    /**
     * Gibt den HTML Footer aus.
     * 
     */
    function get_footer() {
        $this->get_includes('footer');
        echo "</body>\n";
        echo "</html>";
    }

}

?>
