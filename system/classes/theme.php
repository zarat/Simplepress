<?php

/** 
 * @author Manuel Zarat
 */

class theme extends system {
  
    /**
     * Der HTML Header ist der im Browser unsichtbare Bereich der die ganzen Metatags & Co einbindet
     */
    final function html_header() {             
        echo "<!DOCTYPE html>\n"; // <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">       
        echo "<html>\n"; // <html xmlns="http://www.w3.org/1999/xhtml">        
        echo "<head>\n";
        /**
         * Wenn ein Item abgefragt wurde, werden hier bereits die Metatags geaendert und muessen davor beschafft werden. 
         */
        $item = $this->get_current_item();                
        $title = @$item['title'] ? $this->settings( 'site_title' ) . " - " . $item['title'] : $this->settings( 'site_title' );        
        $keywords = @$item['keywords'] ? $item['keywords'] : $this->settings( 'site_keywords' );        
        $description = @$item['description'] ? $item['description'] : $this->settings( 'site_description' );        
        echo "<title>" . $title . "</title>\n";                        
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";       
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />\n";                
        echo "<meta name=\"keywords\" content=\"$keywords\">\n";        
        echo "<meta name=\"description\" content=\"$description\">\n";
        echo "<meta name=\"generator\" content=\"SimplePress - https://github.com/zarat/simplepress\" />\n";            
        echo "<link rel=\"stylesheet\" href=\"../content/themes/" . $this->settings( 'site_theme' ) . "/css/style.css\">\n";        
        echo "<link rel=\"stylesheet\" href=\"../content/themes/" . $this->settings( 'site_theme' ) . "/css/menu.css\">\n";                          
        echo "</head>\n";        
        echo "<body>\n";        
        echo "<div class=\"sp-main-wrapper\">\n\n";        
    }

    /**
     * Der Header ist der sichtbare, oberste Bereich der Seite.
     */
    function header() {            
        echo "<div class=\"sp-main-header\">\n";        
            echo "<div class=\"sp-main-header-logo\">\n";            
                echo "<h1>".$this->settings('site_title')."</h1>\n<h4>".$this->settings('site_subtitle')."</h4>\n";                
            echo "</div>\n";            
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
         echo "<!--BeginNoIndex-->\n";         
         echo $nav->html();          
         echo "<!--EndNoIndex-->\n";            
    }
    
    /**
     * @todo Logik nach system() auslagern
     */
    function content() {                                
        $content = $this->get_the_content();        
        /**
         * @todo  Wenn ein Fehler beim generieren der Inhalte passiert ist, muss das angezeigt werden
         */
        if( isset( $content['error'] ) ) {                 
            echo "\n<!--BeginNoIndex-->\n";
            echo "<div class=\"sp-content\">";               
                echo "<div class=\"sp-content-item\">\n";               
                        echo "<div class=\"sp-content-item-head\">" . $this->_t('no_items_to_display') . "</div>\n";
                echo "</div>\n";
            echo "</div>";
            echo "\n<!--EndNoIndex-->\n";
        } else { 
            if( $content['view'] == "archive" ) {  
                if( !$content['content'] ) { 
                    $this->error404(); 
                }
                foreach( $content['content'] as $item ) {  
                    if( preg_match( "/^.{1,150}\b/s", $item['content'], $match ) ) {
                        $item['content'] = $match[0];
                    } 
                    echo "<div class=\"sp-content-item\">\n";
                        echo "<div class=\"sp-content-item-head\"><a href=\"../?type=$item[type]&id=$item[id]\">$item[title]</a></div>\n";
                        echo "<div class=\"sp-content-item-body\">$item[content]</div>\n";
                    echo "</div>\n";  
                }               
            } else if( $content['view'] == "single" ) { 
                $item = $content['content'];   
                    echo "<div class=\"sp-content-item\">\n";
                        echo "<div class=\"sp-content-item-head\">$item[title]</div>\n";
                        echo "<div class=\"sp-content-item-body\">$item[content]</div>\n";
                    echo "</div>\n";
            } else {
                foreach( $content['content'] as $item ) {  
                    if (preg_match('/^.{1,150}\b/s', $item['content'], $match)) {
                        $item['content'] = $match[0];
                    }                   
                    echo "<div class=\"sp-content-item\">\n";
                        echo "<div class=\"sp-content-item-head\"><a href=\"../?type=$item[type]&id=$item[id]\">$item[title]</a></div>\n";
                        echo "<div class=\"sp-content-item-body\">$item[content]</div>\n";
                    echo "</div>\n";
                }  
            } 
        }                                                         
    }
    
    /**
     * default sidebar
     */
    function sidebar() {                                                           
        echo "<div class=\"sp-sidebar-item\">\n";            
            echo "<div class=\"sp-sidebar-item-head\">Suche</div>\n";                
            echo "<div class=\"sp-sidebar-item-box\">\n";                
                echo "<div class=\"sp-sidebar-item-box-body\"><div class=\"container\"><form><input type=\"hidden\" name=\"type\" value=\"search\"><input type=\"text\" name=\"term\"></form></div></div>\n";                
            echo "</div>\n";               
        echo "</div>\n";  
        echo "<div class=\"sp-sidebar-item\">\n";
            echo "<div class=\"sp-sidebar-item-head\">Kategorien</div>\n";
            foreach($this->archive( array('select' => 'id,title','from' => 'item','where' => 'status=1 AND type="category"') ) as $cat) {
                echo "<div class=\"sp-sidebar-item-box\">\n";
                    echo "<div class=\"sp-sidebar-item-box-head\"><a href=\"../?type=category&id=$cat[id]\">$cat[title]</a></div>\n";
                echo "</div>\n";
            }
        echo "</div>\n";
    }
    
    /**
     * Der Footer ist der sichtbare, unterste Bereich der Seite.
     */
    function footer() {    
        echo "\n<div class=\"sp-footer\" style=\"padding:10px;\">\n";
            echo "Powered by <a href=\"https://github.com/zarat/simplepress\" target=\"_blank\">Simplepress</a> | <a href=\"../rss.php\">RSS</a>\n";
        echo "</div>\n";  
    }
    
    /**
     * Der HTML Footer steht im HTML ganz unten und schliesst es auch.
     */
    final function html_footer() { 
        echo "</div>\n";  
        echo "</body>\n";
        echo "</html>";
    }
    
    /**
     * Diese Funktion setzt im Prinzip alles zusammen und gibt es aus. 
     * Sie kann im Custom Theme ueberschrieben werden.     
     */
    function render() {
        $this->get_the_content();                
        $this->html_header();        
        $this->header();        
        $this->navigation();        
        $this->content();        
        $this->sidebar();               
        $this->footer();        
        $this->html_footer();                    
    }
    
    /**
     * @todo Nach system() auslagern
     */
    final function error404() {                
        ob_start();        
        if( is_file( $errorfile = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "404-" . $this->request('type') . ".php") ) {                
            include $errorfile;                        
        } else if( is_file( $errorfile = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "404.php") ) {                
            include $errorfile;                        
        } else {        
            echo "<div class='sp-content'><div class='sp-content-item-head'>" . $this->_t('no_items_to_display') . "</div></div>";                        
        }         
        $result = ob_get_contents();        
        ob_end_clean();        
        return $result;                                    
    }

}

?>
