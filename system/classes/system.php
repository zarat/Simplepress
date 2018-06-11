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
     * @todo Theme rendern auslagern
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
    final function _t($str) {        
        include ABSPATH . 'system' . DS . 'lang' . DS . 'lang.php';        
        if( is_file( $langfile = ABSPATH . 'system' . DS . 'lang' . DS . 'lang-' . $this->settings('site_language') . '.php' ) ) {        
            include $langfile;                
        }            
        return isset($lang[$str]) ? $lang[$str] : "Fehler: Sprachdatei fehlerhaft, kann '$str' nicht finden.";            
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
     * Hier wird der primaere Inhalt generiert und als Array($result) an system/theme uebergeben.
     * Diese Funktion sollte nur ausgeben was es wirklich gibt um dem Theme das filtern abzunehmen. 
     * 
     * @return array Der Inhalt
     */
    function get_the_content() { 
        
        /**
         * Views sagen aus, was gerade angezeigt wird. Ein einzelnes Item, ein Archiv oder nichts spezielles.
         */
        switch( $this->request( 'type' ) ) {   
             
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
                     
        $item = false;
        $result = false; 
                                                    
        switch( $this->view ) {  
                    
            case "single": 
                                               
                /**
                 * Entsprechendes Item holen
                 * Wenn keines vorhanden ist, setze ein Dummyitem und einen error trigger
                 */
                $item = $this->single( array( 'id' => $this->request('id'), 'metadata' => true ) );
                if( !$item ) {                 
                    $item = array( "title" => "404", "content" => $this->_t( 'no_items_to_display' ) ); 
                    $result['error'] = "error on single";                                                                               
                } 
                
                /**
                 * Entweder das Dummy Item oder das echte fuer den header und das theme setzen
                 */
                $this->set_current_item($item); 
                
                /**
                 * Den Inhalt Loop bildem
                 */
                $archive = new archive();
                $archive->archive_init(); 
                $item = $archive;
                
                /**
                 * Ergebnisse fuer theme setzen.
                 */
                $result['content'] = $item;               
                $result['view'] = "single";
                
            break;   
                      
            case "archive":
            
                /**
                 * Wenn der Parameter search gesetzt ist, dann suchen wir etwas
                 * Einfach nur das Header Item setzen..
                 */
                if( $this->request( 'type' ) == "search" ) {
                                                                                             
                    $this->set_current_item( array( "title" => "Suchergebnisse zu: " . $this->request( 'term' ), "content" => "Suchergebnisse zu: " . $this->request( 'term' ) ) );
                                                                                         
                } 
                
                /**
                 * Archive sind besonders, sie sollen naemlich andere Items nach bestimmten Eigenschaften gruppieren koennen (siehe Taxonomy)
                 * Derzeit nur Kategorien..
                 * 
                 * @todo
                 */
                if( $this->request( 'type' ) == "category" ) {
                                                                
                    $item = $this->single( array( 'id' => $this->request( 'id' ) ) ); 
                    
                    if( !$item ) { 
                    
                        /**
                         * Wenn es die Kategorie nicht gibt, ein Dumy fuer den header bilden
                         */
                        $this->set_current_item( array( "title" => "404", "description" => $this->_t( 'no_items_to_display' ), "content" => $this->_t( 'no_items_to_display' ) ) ); 
                        $result['error'] = "error on archive"; 
                                              
                    } else {
                    
                        /**
                         * sonst einfach die Kategorie selbst als Header Item setzen. (Meta)
                         */
                        $this->set_current_item( $item );
                        
                    }   
                                                                      
                }
                
                /**
                 * Jetzt das Archiv bilden
                 */
                $archive = new archive();                
                $archive->archive_init();

                if( $archive->items ) {  
                             
                    /**
                     * Wenn Ergebnisse vorhanden sind setze result[content] fuer theme und current_item fuer header
                     */                                                          
                    $result['content'] = $archive;
                                                                              
                } else { 
                                  
                    /**
                     * Sind keine Ergebnisse vorhanden setze nur! den error trigger
                     */                            
                    $result['error'] = "error on archive"; 

                }  
                                                                                            
                $result['view'] = "archive"; 
                                                                                                         
            break;   
                                                         
            default:
                                                   
                $latest = new archive();                
                $latest->archive_init();  
                                                                                                                                                                           
                if( !$latest->items ) {  
                    
                    $item = array( "title" => "404", "content" => $this->_t( 'no_items_to_display' ) );
                    $result['content'] = $item;
                    $result['error'] = "error on default";               
                    
                } else {
                    $result['content'] = $latest;
                } 
                                 
                $result['view'] = "default"; 
                //$result['content'] = $latest; 
                                
            break;             
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
