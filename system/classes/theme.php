<?php

/** 
 * @author Manuel Zarat
 */

class theme extends system {
    
    /**
     * Diese Funktion gibt im Prinzip alles aus. 
     */
    final function render() {
           
        /**
         * Diese Funktion muss zuerst ausgefuehrt werden, da die Variablen im Header gebraucht werden. 
         * Inhalt wird in system gebuffert!
         */
        $content = $this->path();
        
        /**
         * Der unsichtbare Header holt sich jetzt die Daten, die davor gebuffert wurden.
         * zwecks SEO warats
         */
        $this->head();
        
        /**
         * Custom scripts & Co bevor der sichtbare Header ausgegeben wird.
         */
        $this->before_header();
        
        /**
         * Der sichtbare Header.
         * Darin ist z.B die Navigation.
         */
        $this->header();
        
        /**
         * Custom scripts & Co bevor der sichtbare MAIN CONTENT ausgegeben wird.
         * Also nach der Navigation aber noch vor dem Inhalt.
         */
        $this->before_content(); 
        
        /**
         * Jetzt wird der Inhalt ausgegeben, der zu Beginn gebuffert wurde.
         */
        echo $content;
        
        /**
         * Custom scripts & Co die vor der Sidebar ausgegeben werden.
         */
        $this->before_sidebar();        
        
        /**
         * Die Sidebar
         */
        $this->sidebar();       
        
        /**
         * Custom scripts & Co, die vor dem sichbaren Footer ausgegeben werden.
         */
        $this->before_footer();
        
        /**
         * Der sichtbare Footer.
         */
        $this->footer();
        
        /**
         * Der unsichtbare Footer
         */
        $this->foot(); 
           
    }
  
    /**
     * Der unsichtbare Header
     * Bindet die ganzen Metatags & Co vor </head> ein
     */
    final function head() { 
        
        echo "<!DOCTYPE html>\n<html>\n";
        echo "<head>\n";

        $item = false;
        $item = @$this->get_current_item();
        
        $title = $item['title'] ? $this->settings( 'site_title' ) . " - " . $item['title'] :$this->settings( 'site_title' );
        $keywords = $item['keywords'] ? $item['keywords'] : $this->settings( 'site_keywords' );
        $description = $item['description'] ? $item['description'] : $this->settings( 'site_description' );
        
        echo "<title>" . $title . "</title>\n";        
        
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />\n";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0' />\n";        
        echo "<meta name='generator' content='SimplePress - https://github.com/zarat/simplepress' />\n";
        echo "<meta name='keywords' content='$keywords'>\n";
        echo "<meta name='description' content='$description'>\n";
        
        echo "<link rel='stylesheet' href='../content/themes/" . $this->settings( 'site_theme' ) . "/css/style.css'>\n";
        echo "<link rel='stylesheet' href='../content/themes/" . $this->settings( 'site_theme' ) . "/css/menu.css'>\n"; 
             
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
     * Wenn etwas vor der Sidebar angezeigt werden soll, kommt es hierhin
     * 
     * @todo
     * 
     */
    function before_sidebar() { }
    
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
        echo "Powered by <a href='https://github.com/zarat/simplepress' target='_blank'>Simplepress</a> | <a href='../rss.php'>RSS</a>";        
    }
    
    /**
     * HTML Footer, also der Teil nach </html>
     * Eigentlich nur fuer Style- und Scriptincludes
     * 
     */
    final function foot() {    
        echo "</body>\n";
        echo "</html>";
    }
    
    /**
     * @todo Einstweiliges 404 Handle
     */
    final function error404() {            
        if( is_file( $errorfile = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "404-" . $this->request('type') . ".php") ) {        
            include $errorfile;            
        } else if( is_file( $errorfile = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "404.php") ) {        
            include $errorfile;            
        } else {
            echo "<div class='sp-content'><div class='sp-content-item-head'>" . $this->_t('no_items_to_display') . "</div></div>";            
        }                             
    }

}

?>
