<?php

/** 
 * @author Manuel Zarat
 */

class theme extends system {
  
    /**
     * Der HTML Header ist der im Browser unsichtbare Bereich der die ganzen Metatags & Co einbindet
     */
    final function html_header() { 
            
        echo "<!DOCTYPE html>\n<html>\n";
        echo "<head>\n";

        /**
         * Wenn ein Item abgefragt wurde, werden hier bereits die Metatags geaendert und muessen davor beschafft werden. 
         */
        $item = $this->get_current_item();        
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
     * Der Header ist der sichtbare, oberste Bereich der Seite.
     */
    function header() {
        echo "<div class=\"sp-main-wrapper\">";
        echo "<div class='sp-main-header'>\n";
        echo "<div class='sp-main-header-logo'><h1>".$this->settings('site_title')."</h1><h4>".$this->settings('site_subtitle')."</h4></div>\n";
        echo "</div>\n";         
    }
    
    /**
     * Die Hauptnavigation ist in spezielle BeginNoIndex-Tags gewrappt. 
     * Diese sollen SuMa sagen, das der darin stehende Content keinen direkten Bezug zum gerade angezeigten Context hat.
     * Z.b eine Liste mit Kategorien oder den neuesten Items.
     * Diese haben ja nicht unbedingt mit dem aktuellen Context zu tun.
     * 
     * @todo multiple menues - Wenn man eine ID uebergibt kann man eine andere laden
     */
    function navigation( $id = 1 ) {
         $nav = new menu();
         $nav->config( array( 'id' => $id ) );
         echo "<!--BeginNoIndex-->";
         echo $nav->html(); 
         echo "<!--EndNoIndex-->";   
    }
    
    /**
     * Der Footer ist der sichtbare, unterste Bereich der Seite.
     */
    function footer() {    
        echo "Powered by <a href='https://github.com/zarat/simplepress' target='_blank'>Simplepress</a> | <a href='../rss.php'>RSS</a>";
        echo "<div/>";        
    }
    
    /**
     * Der HTML Footer steht im HTML ganz unten und schliesst es auch.
     */
    final function html_footer() {    
        echo "</body>\n";
        echo "</html>";
    }
    
    /**
     * Diese Funktion setzt im Prinzip alles zusammen und gibt es aus. 
     * Sie kann im Custom Theme ueberschrieben werden.     
     */
    function render() {
        $content = $this->content();        
        $this->html_header();
        $this->header();
        $this->navigation();
        echo $content;
        $this->sidebar();       
        $this->footer();
        $this->html_footer();            
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
