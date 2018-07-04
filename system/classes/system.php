<?php

/**
 * Simplepress System
 *
 * Grundlegende Aktionen im System
 *
 * @author Manuel Zarat
 * @version 0.2.0
 * @link https://github.com/zarat/simplepress   
 * @since 06/2018 
 */

class system extends core {

    /**
     * Diese Variable ist privat, damit nur ueber die vorgegebenen Methoden set_current_item() und get_current_item() darauf zugegriffen werden kann. 
     */
    private $current_item = false;        

    /**
     * Diese Funktion wird als erstes nach dem Start aufgerufen. 
     * Sie setzt das Theme auf und triggert den ersten Hook. 
     * Am Ende wird dann das Theme gerendert.
     * 
     * @param false
     * 
     * @return void
     */
    final function init() {     
        $this->setup_theme();                
        $this->theme->render();             
    }
    
    /**
     * Startet das installierte Theme. 
     * Falls kein oder ein kaputtes Theme installiert ist, wird das integrierte Default Theme genutzt. 
     * Danach wird ein Hook aufgerufen.
     * 
     * @param false
     * 
     * @return void
     */
    final function setup_theme() {   
        if(is_file($custom_theme_file = ABSPATH . 'content' . DS . 'themes' . DS . $this->settings('site_theme') . DS . 'theme.php')) {        
            include $custom_theme_file;                  
            $custom_theme = $this->settings('site_theme');                                   
            $this->theme = new $custom_theme;                    
        } else {                  
            $this->theme = new theme();                                 
        }                            
    }
    
    /**
     * Uebersetzt einen String aus der Sprachdatei system/lang/* 
     * Ist eine eigene Sprachdatei vorhanden, wird diese genutzt - ansonsten die Default Sprachdatei. 
     * 
     * @param string $str Der zu uebersetzende String
     * 
     * @return string uebersetzer_string|fehlerstring
     */
    final function _t( $str, $arr = false ) { 
        
        /**
         * Erst di Default Sprachdatei, das wir alles haben..
         *
         * Danach, falls es eine gibt, diese einbinden und die Default ueberschreiben
         */
        include ABSPATH . 'system' . DS . 'lang' . DS . 'lang.php'; 
               
        if( is_file( $langfile = ABSPATH . 'system' . DS . 'lang' . DS . 'lang-' . $this->settings('site_language') . '.php' ) ) { 
               
            include $langfile; 
                           
        }  
                  
        return isset($lang[$str]) ? vsprintf( $lang[$str], $arr ) : "Fehler: Sprachdatei fehlerhaft, kann '$str' nicht finden.";  
                  
    }
 
    /**
     * Wenn aktuell ein Item oder ein Archiv ausgegeben wird, werden die Informationen im Header gebraucht. 
     * Dazu werden sie vorher hier abgelegt.
     * 
     * Indexseiten und Suchergebnisse sind KEINE ITEMS - dazu muessen eigene erstellt werden!
     * 
     * @param array $item Das aktuelle Item
     * 
     * @return void
     */
    function set_current_item( $item ) { 
       
        $this->current_item = $item;  
              
    }
    
    /**
     * Mit dieser Funktion holt sich der HTML Header die benoetigten Informationen zum aktuell angezeigten Inhalt 
     * um die entsprechenden Metadaten auzuliefern. 
     * 
     * Indexseiten und Suchergebnisse sind KEINE ITEMS - dazu muessen eigene erstellt werden!
     * 
     * @return array Das aktuelle Item
     */
    function get_current_item() { 
    
        global $hooks;
        $item = $hooks->apply_filters( 'get_current_item', $this->current_item );   
        return $item; 
               
    }
    
    /**
     * Hier wird der primaere Inhalt generiert. 
     */
    function get_the_content() { 
        
        /**
         * das Archiv bilden
         */
        $archive = new archive();                
        $archive->archive_init();

        if( $archive->have_items() ) {                                                        
            $result['content'] = $archive;                                                                              
        } else {                          
            $result['error'] = "error"; 
        }                                                                                              
                      
        return $result;  
    }
    
    /**
     * Hier wird der sekundaere Inhalt generiert. 
     * 
     * @todo Sekundaerer Inhalt/Sidebar
     */
    function get_the_sidebar() { }

}

?>
