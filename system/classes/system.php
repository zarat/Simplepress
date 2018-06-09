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
     * Diese Variablen sind privat, damit nur ueber vorgegebene Methoden darauf zugegriffen werden kann. 
     */
    private $current_item = false;        
    private $hooks = false;

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
        if($this->has_action(__FUNCTION__)) {         
            $this->do_action(__FUNCTION__);             
        }           
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
        if($this->has_action(__FUNCTION__)) {             
            $this->do_action(__FUNCTION__);                   
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
     * Regtistriert eine Custom Funktion, die bei Aufruf eines Hooks getriggert wird.
     * Wenn keine $action angegeben wird, werden alle Actions auf dem Hook entfernt.
     * 
     * @param string $hook Der Hook bei dem die Custom Funktion aufgerufen werden soll
     * @param string $action Die Custom Funktion, die aufgerufen werden soll
     * @param string|array params optional Die Parameter, mit der die Custom Funktion aufgerufen werden soll
     * 
     * @return bool success|error
     */
    public final function add_action( $hook, $action = false, $params = false ) {          
        if( !$action ) {        
            $this->hooks[$hook] = array();
            return false; 
        }
        if( $params ) { 
            $this->hooks[$hook][] = array( $action, $params ); 
        } else {
            $this->hooks[$hook][] = $action;
        } 
        return true; 
    }
    
    /**
     * Prueft, ob Cunstom Funktionen zu einem bestimmten Hook registriert wurden. 
     * 
     * @param string $hook Der zu pruefende Hook
     * 
     * @return bool success|error
     */
    final function has_action( $hook ) {    
        return isset( $this->hooks[$hook] );        
    }
    
    /**
     * Ruft alle registrierten Custom Funktionen zu einem bestimmten Hooks auf. 
     * Wurde eine Custom Funktion ohne Parameter registriert, wird ihr eine Referenz auf $this uebergeben. 
     * 
     * @param string $hook Der zu pruefende Hook
     * 
     * @return void
     */
    final function do_action( $hook ) {            
        if( isset( $this->hooks[$hook] ) ) {                 
            if( is_array( $this->hooks[$hook][0] ) ) {                         
                call_user_func_array( $this->hooks[$hook][0][0], array( $this->hooks[$hook][0][1] ) );                                  
            } else {                         
                call_user_func( $this->hooks[$hook][0], $this );                                
            }                                            
        }                   
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
        return $this->current_item;        
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
                    $item = array( "title" => "404", "description" => $this->_t( 'no_items_to_display' ), "content" => $this->_t( 'no_items_to_display' ) ); 
                    $result['error'] = "error on single";                                                                               
                } 
                
                /**
                 * Entweder das Dummy Item oder das echte fuer den header und das theme setzen
                 */
                $this->set_current_item($item); 
                
                /**
                 * Fucking encoding
                 * 
                 * @todo
                 */
                $item['content'] = html_entity_decode( $item['content'] );
                
                /**
                 * Ergebnisse fuer theme setzen.
                 */
                $result['content'] = $item;               
                $result['view'] = "single";
                
            break;   
                      
            case "archive":
            
                /**
                 * Wenn der Parameter search gesetzt ist, dann suchen wir etwas
                 */
                if( $this->request( 'type' ) && $this->request( 'type' ) == "search" ) {
                                                            
                    $item = array( "title" => "Suchergebnisse zu: " . $this->request( 'term' ), "description" => "Suchergebnisse zu: " . $this->request( 'term' ) ); 
                    
                    $this->set_current_item( $item );
                                                                                         
                } 
                
                /**
                 * Archive sind besonders, sie sollen naemlich andere Items nach bestimmten Eigenschaften gruppieren koennen (siehe Taxonomy)
                 * Derzeit nur Kategorien und evtl Tags.
                 * 
                 * @todo
                 */
                else if( $this->request( 'type' ) && $this->request( 'type' ) == "category" ) {
                                                                
                    $item = $this->single( array( 'id' => $this->request( 'id' ) ) ); 
                    
                    if( !$item ) { 
                    
                        $this->set_current_item( array( "title" => "404", "description" => $this->_t( 'no_items_to_display' ), "content" => $this->_t( 'no_items_to_display' ) ) ); 
                        $result['error'] = "error on archive"; 
                                              
                    } else {
                    
                        $this->set_current_item( $item );
                        
                    }   
                                                                      
                } else { }
                
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
                     * Sind keine Ergebnisse vorhanden setze ein Dummyitem auf result[content] und den error trigger
                     */
                    $item = array("id" => 0, "title" => "404", "description" => $this->_t( 'no_items_to_display' ), "content" => $this->_t( 'no_items_to_display' ) , "keywords" => "" ); 
                           
                    $result['error'] = "error on archive"; 
                    
                    $this->set_current_item( $item );
                                                         
                }  
                                                                                            
                $result['view'] = "archive"; 
                                                                                                         
            break;   
                                                         
            default:
                                                   
                $latest = new archive();                
                $latest->archive_init();  
                                                                                                                                                                           
                if( !$latest->items ) {  
                    
                    $item = array("id" => 0, "title" => "404", "description" => $this->_t( 'no_items_to_display' ), "content" => $this->_t( 'no_items_to_display' ) , "keywords" => "" );
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
