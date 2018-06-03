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
         echo "<!--BeginNoIndex-->\n";
         echo $nav->html(); 
         echo "<!--EndNoIndex-->\n";   
    }
    
    /**
     * @todo Logik nach system() auslagern
     */
    function content() {            
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
        switch($this->view) {         
            case "single":            
                $include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single.php"; 
                $custom_include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->request('type') . ".php";                                                
                if( ($item = $this->single( array('type' => $this->request('type'), 'id' => $this->request('id'), 'metadata' => true) ) ) === false ) {
                    $this->error404(); 
                    break; 
                }                
                $this->set_current_item($item);                 
                if( is_file( $custom_include_file ) ) {                
                    include $custom_include_file;                    
                } else if( is_file( $include_file) ){                
                    include $include_file;                  
                } else {
                    /**
                     * @todo Kein Single Template vorhanden!
                     */
                    echo "<div class=\"sp-content\">\n";
                    echo "<div class='sp-content-item'>\n";
                    echo "<div class=\"sp-content-item-head\">" . $item['title']. "</div>\n";
                    echo "<div class=\"sp-content-item-body\">" . $item['content']. "</div>\n";
                    echo "</div>\n";
                    echo "</div>\n\n";                              
                }                                
            break;            
            case "archive":            
                $include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive.php";
                $custom_include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive-" . $this->request('type') . ".php";
                $archive = new archive();
                $archive->archive_init();
                if( $this->request( 'id' ) ) { 
                    $item = $this->single( array( 'id' => $this->request( 'id' ) ) );
                    $this->set_current_item($item);
                }           
                if( $archive->count_posts() < 1) { 
                    $this->error404(); 
                    break; 
                }                                           
                if( is_file( $custom_include_file ) ) {                
                    include $custom_include_file;                    
                } else if( is_file( $include_file ) ) {                
                    include $include_file;                  
                } else {
                    /**
                     * @todo Kein Single Template vorhanden!
                     */
                    echo "<div class='sp-content'>\n";
                    while( $archive-> have_posts() ) {
                        $item = $archive->the_post();
                        echo "<div class='sp-content-item'>\n";
                        echo "<div class='sp-content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title']. "</a></div>\n";
                        echo "<div class='sp-content-item-body'>" . $item['content']. "</div>\n";
                        echo "</div>\n";
                    }
                    echo "</div>\n\n";
                }               
            break;                        
            default:             
                /**
                 * Default wird ein Archiv der letzten Items (post) gezeigt.
                 */
                $latest = new archive();
                $latest->archive_init();                
                if( $latest->count_posts() < 1) { 
                    $this->error404(); 
                    break; 
                }  
                $include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";
                $custom_include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "homepage.php";
                if( is_file( $custom_include_file ) ) {                
                    include $custom_include_file;                    
                } else if( is_file( $include_file ) ) {                
                    include $include_file;                   
                } else {
                    /**
                     * @todo Kein Single Template vorhanden!
                     */
                    echo "<div class=\"sp-content\">\n";
                    while( $latest-> have_posts() ) {
                        $item = $latest->the_post();
                        echo "<div class='sp-content-item'>\n";
                        echo "<div class='sp-content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title']. "</a></div>\n";
                        echo "<div class='sp-content-item-body'>" . $item['content']. "</div>\n";
                        echo "</div>\n";
                    }
                    echo "</div>\n\n";
                }                
            break;                    
        }        
        $content = ob_get_contents();         
        ob_end_clean();         
        return $content;               
    }
    
    /**
     * @todo Logik nach system() auslagern
     */
    function sidebar() {         
        $include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "sidebar.php"; 
        $custom_include_file = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "sidebar-" . $this->request('type') . ".php";            
        if( is_file( $custom_include_file ) ) {                
            include $custom_include_file;                    
        } else if( is_file( $include_file ) ) {                
            include $include_file;                   
        } else {                                
            echo "<div class='sp-sidebar-item'>";
                echo "<div class='sp-sidebar-item-head'>Suche</div>";
                echo "<div class='sp-sidebar-item-box'>\n";
                    echo "<div class='sp-sidebar-item-box-body'><div class='container'><form><input type='hidden' name='type' value='search'><input type='text' name='term'></form></div></div>\n";
                echo "</div>\n";
            echo "</div>";                 
            echo "<div class='sp-sidebar-item'>";
                echo "<div class='sp-sidebar-item-head'>Kategorien</div>";
                foreach($this->archive( array('select' => 'id,title','from' => 'item','where' => 'status=1 AND type="category"') ) as $cat) {
                    echo "<div class='sp-sidebar-item-box'>\n";
                        echo "<div class='sp-sidebar-item-box-head'><a href='../?type=category&id=$cat[id]'>$cat[title]</a></div>\n";
                    echo "</div>\n";
                }
            echo "</div>";                       
        }                              
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
     * @todo Nach system() auslagern
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

}

?>
