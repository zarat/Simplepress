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
     * Diese Funktion gibt im Prinzip alles aus. 
     */
    final function render() {
           
        /**
         * Diese Funktion muss zuerst ausgefuehrt werden, da die Variablen im Header gebraucht werden. 
         * wird gebuffert - deshalb echo!!!
         */
        $content = $this->content();
        
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
         * WICHTIG: Den Buffer immer leeren!!!
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
        $item = @$this->get_testvar();
        
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
        
        
             
        $system = new system(); /** Muss System hier wirklich initiiert werden? - dzt ja */ 
        
        ob_start();       
              
        switch($this->view) { 
        
            case "single":
            
                $single_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single.php"; 
                $custom_single_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->request('type') . ".php";  
                $item = $this->single( array( 'type' => $this->request('type'), 'id' => $this->request('id'), 'metadata' => true ) );
                
                $this->set_testvar($item);
                
                /** @todo  HANDLE 404 */
                if(!$item) { $this->error404(); return; } 
                
                if( is_file( $custom_single_file ) ) {                
                    include $custom_single_file;                    
                } else {                
                    include $single_file;                   
                }
                                
            break;
            
            case "archive":
            
                $archive_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive.php";
                $custom_archive_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive-" . $this->request('type') . ".php";
                
                $archive = new archive();
                $archive->archive_init();
                /** @todo HANDLE 404 */
                
                if( $archive->count_posts() < 1) { $this->error404(); break; }
                                           
                if( is_file( $custom_archive_file ) ) {                
                    include $custom_archive_file;                    
                } else {                
                    include $archive_file;                   
                }
                
            break;
                        
            case "default": 
            
                $latest = new archive();
                $latest->archive_init();

                /** @todo HANDLE 404 */                
                if( $latest->count_posts() < 1) { $this->error404(); break; }
                
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";
                
            break;
                    
        }
        
        $content = ob_get_contents(); // zwischenspeichern
        
        ob_end_clean(); // Puffer leeren UND deaktivieren, sonst wird alles 2mal ausgegeben. ob_get_clean() leert ihn nicht!
        
        return $content;
               
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
    
    /**
     * Wenn etwas vor der Sidebar angezeigt werden soll, kommt es hierhin
     * 
     * @todo
     * 
     */
    function before_sidebar() { }
    
    /**
     * Sidebar
     * 
     * Sekundaerer Inhalt, meisstens Kategorien und letzte Artikel
     * 
     * @todo $system muss initiiert werden :S
     * 
     */
    function sidebar() { 
    
        $system = new system();
    
        if(is_file($sidebar = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "sidebar-" . $this->request('type') . ".php")) {  
                      
            include $sidebar; 
                               
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
    
        echo $this->attribution();
        
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
    
    function attribution() {
    
        ob_start();        
        echo "Powered by <a href='https://github.com/zarat/simplepress' target='_blank'>Simplepress</a> | <a href='../rss.php'>RSS</a>";
        $c = ob_get_contents(); // zwischenspeichern        
        ob_end_clean(); // Puffer leeren UND deaktivieren, sonst wird alles 2mal ausgegeben. ob_get_clean() leert ihn nicht!        
        return $c;
        
    }

}

?>
