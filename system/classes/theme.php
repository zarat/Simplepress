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
     * Diese Funktion gibt im Prinzip alles aus
     * 
     * Die einzelnen Schritte sind in Funktionen aufgeteilt, um Hooks aus System aufrufen zu koennen.
     */
    final function render() {
           
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
       
        ob_start();
        
        echo "<!DOCTYPE html>\n<html>\n";
        echo "<head>\n";
        
        /**
         * @todo Wenn ein Objekt angezeigt wird, werden auch dessen Metainformationen benoetigt
         */
        echo "<title>" . $this->settings( 'site_title' ) . "</title>\n";        
        
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />\n";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0' />\n";        
        echo "<meta name='generator' content='SimplePress - https://github.com/zarat/simplepress' />\n";
        echo "<meta name='keywords' content='" . $this->settings( 'site_keywords' ) . "'>\n";
        echo "<meta name='description' content='" . $this->settings( 'site_description' ) . "'>\n";
        echo "<link rel='stylesheet' href='../content/themes/" . $this->settings( 'site_theme' ) . "/css/style.css'>\n";
        echo "<link rel='stylesheet' href='../content/themes/" . $this->settings( 'site_theme' ) . "/css/menu.css'>\n";      
        echo "</head>\n";
        echo "<body>\n";
        
        $header = ob_get_clean();
        
        echo $header;
        
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
        
        ob_start();
             
        $system = new system(); /** Muss System hier wirklich initiiert werden? - dzt ja */        
              
        switch($this->view) { 
        
            case "single":
            
                $single_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single.php"; 
                $custom_single_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->request('type') . ".php";  
                $item = $this->single( array( 'type' => $this->request('type'), 'id' => $this->request('id') ) );
                
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
                                           
                if( is_file( $custom_archive_file ) ) {                
                    include $custom_archive_file;                    
                } else {                
                    include $archive_file;                   
                }
                
            break;
                        
            case "default": 
            
                $latest = $system->archive(array('select' => '*','from' => 'object', 'where' => 'type="post" AND status=1 ORDER BY id DESC'));
                
                /** @todo HANDLE 404 */ 
                if(!$latest) { $this->error404(); return; }
                
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";
                
            break;
                    
        }
        
        $content = ob_get_contents(); // zwischenspeichern
        
        ob_end_clean(); // Puffer leeren UND deaktivieren, sonst wird alles 2mal ausgegeben. ob_get_clean() leert ihn nicht!
        
        echo $content;
               
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
    
        $this->attribution();
        
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
    
        echo "Powered by <a href='https://github.com/zarat/simplepress' target='_blank'>Simplepress</a>";
        
    }

}

?>
