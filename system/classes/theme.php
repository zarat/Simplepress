<?php

/**
 * Simplepress Theme
 *
 * Grundlegendes Theme zur Ausgabe
 *
 * @author Manuel Zarat
 * @version 0.2.0
 * @link https://github.com/zarat/simplepress   
 * @since 06/2018 
 */

class theme extends system {
  
    /**
     * Der HTML-Header ist der im Browser unsichtbare Bereich der die ganzen Metatags & Co einbindet.
     * Er braucht die Informationen zum aktuell angezeigten Item schon vor der eigentlichen Ausgabe des Inhaltes.
     * 
     * @return html
     */
    final function html_header() {             
        echo "<!DOCTYPE html>\n";
        echo "<html lang=de>\n";
            echo "<head>\n";                
                $item = $this->get_current_item();                
                $title = @$item['title'] ? $this->settings( 'site_title' ) . " - " . $item['title'] :$this->settings( 'site_title' );
                $keywords = @$item['keywords'] ? $item['keywords'] : $this->settings( 'site_keywords' );
                $description = @$item['description'] ? $item['description'] : $this->settings( 'site_description' );                        
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
     * 
     * @return html
     */
    function header() {
        echo "<div class='main-header'>\n";
            echo "<div class='main-header-logo'>\n";
                echo "<h1>".$this->settings('site_title')."</h1>\n";
                echo "<h4>".$this->settings('site_subtitle')."</h4>\n";
            echo "</div>\n";
        echo "</div>";         
    }
    
    /**
     * Die Hauptnavigation ist in spezielle BeginNoIndex-Tags gewrappt. 
     * Diese sollen SuMa sagen, das der darin stehende Content keinen direkten Bezug zum gerade angezeigten Context hat.
     * Z.b eine Liste mit Kategorien oder den neuesten Items.
     * Diese haben ja nicht unbedingt mit dem aktuellen Context zu tun.
     * 
     * @param integer $id Die ID des Menues in der Datenbank - default 1
     * 
     * @return html
     */
    function navigation() {
         $nav = new menu();
         $nav->config( array( 'id' => 1 ) );
         echo $nav->html();   
    }
    
    /**
     * Der eigentliche "Primaere Inhalt", der seinen Inhalt aber von $system->get_the_content() bekommt um die Infos im html_header ui haben.
     * 
     * @see $this->html_header()
     * @see $system->get_the_content()
     * 
     * @return html
     */
    function content() {     
                               
        $data = $this->get_the_content(); 
         
        if( !empty( $data['error'] ) ) {   
                         
            echo "<div class='sp-content-item'>\n";
                    echo "<div class='sp-content-item-head'>" . $this->_t('no_items_to_display') . "</div>\n";
            echo "</div>\n"; 
                                                          
        } else {   
                                                                            
            if( $data['view'] == "archive" ) { 
                       
                $template = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive.php";
                $custom_template = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive-" . $this->request('type') . ".php"; 
                               
                if( is_file( $custom_template ) ) { 
                           
                    $archive = $data['content'];
                    include $custom_template;   
                                 
                } else if( is_file( $template ) ) {
                            
                    $archive = $data['content'];
                    include $template;   
                                 
                } else {       
                     
                    while( $data['content']->have_items() ) {
                    
                        $item = $data['content']->the_item(); 
                        $item['content'] = strip_tags( preg_replace("/[^ ]*$/", '', substr( $item['content'], 0, 150 ) ) );  
                        echo "<div class='sp-content-item'>\n";
                            echo "<div class='sp-content-item-head'><a href=\"../?type=$item[type]&id=$item[id]\">$item[title]</a></div>\n";
                            echo "<div class='sp-content-item-body'>$item[content]</div>\n";
                        echo "</div>\n";  
                                                                      
                    }  
                                  
                    $data['content']->pagination();   
                                  
                }   
                                                                      
            } else if( $data['view'] == "single" ) { 
                         
                $template = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single.php";
                $custom_template = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->request('type') . ".php";                
                
                if( is_file( $custom_template ) ) {
                            
                    $item = $data['content'];
                    include $custom_template; 
                                   
                } else if( is_file( $template ) ) { 
                           
                    $item = $data['content'];
                    include $template;    
                                
                } else {              
                                                
                    $item = $data['content'];
                    $item['content'] = html_entity_decode( $item['content'] );                    
                    echo "<div class='sp-content-item'>\n";
                        echo "<div class='sp-content-item-head'>" . $item['title'] . "</div>\n";
                        echo "<div class='sp-content-item-body'>" . $item['content'] . "</div>\n";
                    echo "</div>\n"; 
                                
                }  
                                                                                   
            } else if( $data['view'] == "default" ) {  
                  
                $template = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";
                                
                if( is_file( $template ) ) {    
                        
                    $latest = $data['content'];
                    include $template;   
                                 
                } else {           
                                                   
                    while( $data['content']->have_items() ) {
                    
                        $post = $data['content']->the_item();
                        html_entity_decode( $post['content'] );
                        $post['content'] = strip_tags( preg_replace("/[^ ]*$/", '', substr( $post['content'], 0, 150 ) ) );                    
                        echo "<div class='sp-content-item'>\n";
                            echo "<div class='sp-content-item-head'><a href=\"../?type=$post[type]&id=$post[id]\">$post[title]</a></div>\n";
                            echo "<div class='sp-content-item-body'>$post[content]</div>\n";
                        echo "</div>\n"; 
                        
                    }           
                }        
            } 
        }                                                                        
    }
    
    /**
     * Eine Default Sidebar (sekundaerer Inhalt)
     * 
     * @return html
     */
    function sidebar() {     
        $template = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "sidebar.php";
        $custom_template = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "sidebar-" . $this->request('type') . ".php";                
        if( is_file( $custom_template ) ) {            
            include $custom_template;                
        } else if( is_file( $template ) ) {            
            include $template;                
        } else {
            echo "<div class='sidebar-item'>\n";
                echo "<div class='sidebar-item-head'>Suche</div>\n";
                echo "<div class='sidebar-item-box'>\n";
                    echo "<div class='sidebar-item-box-body'><div class='container'><form><input type='hidden' name='type' value='search'><input type='text' name='term'></form></div></div>\n";
                echo "</div>\n";
            echo "</div>\n";                        
        }
    }
    
    /**
     * Der Footer ist der sichtbare, unterste Bereich der Seite.
     * 
     * @return html
     */
    function footer() {    
        echo "Powered by <a href='https://github.com/zarat/simplepress' target='_blank'>Simplepress</a> | <a href='../rss.php'>RSS</a>";        
    }
    
    /**
     * Der HTML Footer steht im HTML ganz unten, ist "unsichtbar" und schliesst es auch.
     * 
     * @return html
     */
    function html_footer() {    
            echo "\n</body>\n";
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
        $this->content(); //
        $this->sidebar();       
        $this->footer();
        $this->html_footer();            
    }
    
    /**
     * Wenn nichts gefunden wurde muss ein Fehler ausgegeben werden. 
     * Wird derzeit in system->get_the_content() erledigt.
     * 
     * @see system->get_the_content()
     *
     * @todo 404 nicht gefunden Fehler
     */
    final function error404() {            
        ob_start();
        if( is_file( $errorfile = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "404-" . $this->request('type') . ".php") ) {        
            include $errorfile;            
        } else if( is_file( $errorfile = ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "404.php") ) {        
            include $errorfile;            
        } else {
            echo "<div class='content'><div class='content-item-head'>" . $this->_t('no_items_to_display') . "</div></div>";            
        } 
        $result = ob_get_contents();
        ob_end_clean();
        return $result;                            
    }

}

?>
