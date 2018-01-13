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
     * Themes muessen Positionen aufweisen, in welche man Inhalte einfuegen kann.
     * 
     * @todo Themes sollten Positionen erweitern bzw definieren koennen
     *
     */
    private $positions = array('header','content','sidebar','footer');
    
    /**
     * Registriert Inhalte zum Einbinden an bestimmten Positionen (Hooks ->)
     * 
     * @todo Evtl besser mit array_pop()
     * 
     * @param string $position Wo soll es eingefuegt werden
     * @param string $include Was soll eingefuegt werden
     * 
     * @return void
     */
    final function set_include($position,$include) {
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
    final function get_includes($position) {
        if(isset($this->positions[$position])) {
            foreach($this->positions[$position] as $include) {
                $includes[] = $include;
            }
            return $includes;    
        }
        return false;
    }
    
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
    final function render() { 
        $this->theme_functions();
           
        $this->head();
        
        $this->before_header();
            $this->header();       
        
        $this->before_content(); 
            $this->content();
        
        $this->before_sidebar();
            $this->sidebar();       
        
        $this->before_footer(); 
            $this->footer();
        
        $this->foot();    
    }
  
    /**
     * HTML Header
     *
     * Bindet die ganzen Metatags und Includes vor </head> ein
     * 
     * @todo Dokumenttitel bei Items!!!
     * 
     */
    final function head() {
        echo "<!DOCTYPE html>\n<html>\n";
        echo "<head>\n";
        echo "<title>" . $this->settings('site_title') . "</title>\n";        
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />\n";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0' />\n";        
        echo "<meta name='generator' content='SimplePress - https://github.com/zarat/simplepress' />\n";
        echo "<meta name='keywords' content='" . $this->settings('site_keywords') . "'>\n";
        echo "<meta name='description' content='" . $this->settings('site_description') . "'>\n";
        echo "<link rel='stylesheet' href='../content/themes/" . $this->settings('site_theme') . "/css/style.css'>\n";
        
        if(is_array($headers = $this->get_includes('header'))) {
            foreach($headers as $header) {
                echo $header . "\n";
            }
        }
        echo "</head>\n";
        echo "<body>\n";
    }
    
    /**
     * Wenn etwas ganz am Anfang des HTML Markup stehen soll kommt es hierhin.
     * 
     * @todo
     */
    function before_header() { }
    
    /**
     * Header 
     * 
     * Damit ist der im Browser sichtbare Teil nach <body> gemeint
     * 
     * @todo Was tun, wenn kein Menue erstellt wurde?!!!
     * 
     */    
    function header() {
         $nav = new menu();
         $nav->config(array('id' => 1));
         echo $nav->html();
    }
    
    /**
     * Wenn etwas vor dem Hauptinhalt angezeigt werden soll, kommt es hierhin
     * 
     * @todo
     * 
     */
    function before_content() { }
    
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
    final function content() { 
    
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
     * Wenn etwas vor der Sidebar angezeigt werden soll, kommt es hierhin
     * 
     * @todo
     * 
     */
    function before_sidebar() { }
    
    /**
     * Sidebar
     * Sekundaerer Inhalt, meisstens Kategorien und letzte Artikel
     * 
     * @todo $system muss initiiert werden :S
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
    
    /**
     * Wenn etwas unter dem ganzen Inhalt angezeigt werden soll, kommt es hierhin
     * 
     * @todo
     * 
     */
    function before_footer() { }
    
    /**
     * Footer
     * Mit footer ist hier der sichtbare Footer gemeint, also der untere Teil der Website.
     * 
     */
    function footer() {
        $this->attribution();
    }
    
    /**
     * HTML Footer, also der Teil nach </html>
     * Eigentlich nur fuer Style- und Scriptincludes
     * 
     */
    final function foot() {
        echo "</body>\n";
        if(is_array($footers = $this->get_includes('footer'))) {
            foreach($footers as $footer) {
                echo $footer . "\n";
            }
        }
        echo "</html>";
    }
    
    function attribution() {
        echo "Powered by <a href='https://github.com/zarat/simplepress' target='_blank'>Simplepress</a>";
    }

}

?>
