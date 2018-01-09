<?php

/** 
 * SimplePress Theme Dummy
 *
 * @author Manuel Zarat
 * 
 * THEMES sollen eine Datei "theme.php" und eine Datei "functions.php" im Root Verzeichnis haben, die dieses Dummy ueberschreibt.
 * 
 */

class theme extends system {

    /**
     * THEMES muessen VIEWS definieren, die vorgeben, welche Positionen ein Theme hat.
     * So koennen auch Themes mit individuellen VIEWS arbeiten (ShoppingCart, Listen, Profil, ...)
     *
     * @todo Eigene VIEWS erstellen und verwalten
     * 
     */
    private $views = array('default','single','archive');
    
    /**
     * Themes muessen Positionen aufweisen, in welche man Inhalte einfuegen kann.
     * 
     * @todo Themes sollten Positionen erweitern bzw definieren koennen
     *
     */
    private $positions = array('header','content','sidebar','footer');

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
     * @todo Evtl besser mit array_pop()
     * 
     * @param string $position Wo soll es eingefuegt werden
     * @param string $include Was soll eingefuegt werden
     * 
     * @return void
     * 
     * @example: set_include('header','i am the header');
     */
    function set_include($position,$include) {
        $this->positions[$position][] = $include;
    }
    
    /**
     * Zeigt einzubindende Inhalte zu einer Position an.
     * 
     * @todo 
     * 
     * @param string position
     * 
     */
    function get_includes($position) {
        if(isset($this->positions[$position])) {
            foreach($this->positions[$position] as $include) {
                $includes[] = $include;
            }
            return $includes;    
        }
        return false;
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
     * Wenn kein Theme aktiv ist, gibt es auch kein Stylesheet. Das ist Mist!
     * Deshalb zuerst pruefen, ob settings.php vorhanden ist und wenn ja - includen
     * 
     * @todo
     * 
     */
    final function theme_functions() {
        session_start();
        if(is_file($theme_settings=ABSPATH . 'content' . DS . 'themes' . DS . $this->settings('site_theme') . DS . 'functions.php')) {
            include $theme_settings;
        }
    }
    
    /**
     * Die einzelnen Schritte sind in Funktionen aufgeteilt, um Trigger aufrufen zu koennen.
     * 
     * @todo Vielleicht noch mehr aufteilen => Hooks
     * 
     * @return string
     * 
     */
    function render() { 
        //session_start();
        $this->theme_functions();   
        $this->html_header();
        $this->header();        
        $this->content();
        $this->sidebar();        
        $this->footer();
        $this->html_footer();    
    }
    
    /**
     * HTML Header
     *
     * Bindet die ganzen Includes vor </head> ein
     * 
     */
    function html_header() {
        echo "<!DOCTYPE html>\n<html>\n";
        echo "<head>\n";
        if(is_array($headers = $this->get_includes('header'))) {
            foreach($headers as $header) {
                echo "\t" . $header . "\n";
            }
        }
        echo "</head>\n";
        echo "<body>\n";
    }
    
    function header() {
        /**
	       * Der im Browser sichtbare Teil nach <body>
         * 
         */
         $nav = new menu();
         $nav->config(array('id' => 1));
         echo $nav->html();
    }
    
    /**
     * Content
     * 
     * Hier wird entschieden, welche Theme Dateien eingebunden werden.
     *
     * @todo Muss weiter aufgeteilt werden um Hooks einzubauen und das ganze dynamischer zu gestalten.
     *  
     * @return false
     * 
     */ 
    function content() { 
    
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
             
        $system = new system(); /** Muss System hier wirklich initiiert werden? - dzt ja */  
              
        switch($this->view) { 
            /**
             * Ein einzelnes Objekt
             * 
             */
            case "single":             
                $item = $this->single(array('type'=>$this->type,'id'=>$this->id)); /** Ist das noch notwendig?!? */                 
                if(is_file($single=ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->type . ".php")) {                
                    include $single;                    
                } else {                
                    include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single.php";                   
                }                
            break;
            /**
             * Liste von Objekten
             *
             * @todo Pagination
             * @todo search
             *
             */
            case "archive":
                if(is_file(ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive-" . $this->type . ".php")) {                
                    include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive-" . $this->type . ".php";                    
                } else {                
                    include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive.php";                   
                }
            break;            
            /**
             * Wenn die Seite ohne Parameter aufgerufen wird
             * 
             */
            case "default": 
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";
            break;        
        }       
    }
    
    /**
     * Sidebar
     * 
     * @todo $system muss initiiert werden
     * 
     */
    function sidebar() { 
        $system = new system();
        if(is_file($sidebar_tpl = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "sidebar-" . $this->type . ".php")) {                
            include $sidebar_tpl;                    
        } else {                
            include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "sidebar.php";                   
        }        
    }
    
    function footer() {
        $this->attribution();
    }
    
    /**
     * HTML Foot
     * 
     */
    function html_footer() {
        echo "</body>\n";
        if(is_array($footers = $this->get_includes('footer'))) {
            foreach($footers as $footer) {
                echo "\t" . $footer . "\n";
            }
        }
        echo "</html>";
    }
    
    function attribution() {
        echo "Powered by <a href='https://github.com/zarat/simplepress' target='_blank'>Simplepress</a>";
    }

}

?>
