<?php

/**
 * Simplepress Core
 *
 * @author Manuel Zarat
 * @version 0.2.0
 * @link https://github.com/zarat/simplepress   
 * @since 06/2018 
 */

$hooks = new hooks();
global $hooks;

abstract class core {               
 
    private $db = false;     
    private $last_insert_id = false; 

    /**
     * Im Konstruktor wird entweder die Konfiguration geladene und fall keine vorhanden ist
     * wird die Installation gestartet.
     *
     * @todo final weg damit er ueberschrieben werden kann?
     * 
     * @return void
     */
    final function __construct() {              
        if(is_file(ABSPATH . "config.php")) {         
            include ABSPATH . "config.php";             
        } else {         
            include ABSPATH . "install.php"; 
            exit();             
        }                        
        $this->db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);                                                         
    }

    /**
     * Beendet allenfalls offene Datenbankverbindungen
     * 
     * @return void
     */
    final function __destruct() {              
        if($this->db) {        
            $this->db->close();            
            $this->db = false;            
        }                                                         
    }
    
    /**
     * Meldet einen User an. Derzeit ist in der Datei login.php ein JS Code eingebunden
     * um die lokale Zeit des Users inkl. Offset zu erhalten und ein Cookie zu setzen - localtime-offset ist zu ungenau!
     * 
     * @param integer $uid Die BenutzerID
     * @param md5(string) $pass Das Passwort
     * 
     * @return int|bool userID|(sucess|error)
     */
    final function login($uid, $pass) {    
        $user = $this->select( array( "select" => "*", "from" => "user", "where" => "email='$uid' AND password='$pass'") );        
        return !empty( $user[0]['id'] ) ? $user[0]['id'] : false;        
    }
    
    /**
     * Meldet einen Benutzer ab indem es den Login Token aus der Datenbank entfernt. Der Cookie bleibt bestehen.
     * 
     * @param false
     * 
     * @return bool 
     */
    final function logout() {    
        $token = $_COOKIE['sp-uid'];        
        $this->update( array( "table" => "user", "set" => "token='' where token='$token'" ) );        
    }
    
    /**
     * Prueft, ob ein Benutzer angemeldet ist indem es den Cookie mit dem Login Token aus der DB abgleicht.
     * 
     * @param false
     * 
     * @return integer|bool UserID|(success|error)
     */
    final function auth() {    
        $token = @$_COOKIE['sp-uid'];        
        $user = $token ? $this->select( array( "select" => "*", "from" => "user", "where" => "token='$token'") ) : false;        
        return !empty( $user[0]['id'] ) ? $user[0] : false;       
    }  
     
    private function sql_escape_string($query) {    
        return mysqli_real_escape_string($this->db, $query);            
    }   
         
    final function query($query) {     
        $this->sql_escape_string($query);        
        return $this->db->query($query);        
    }
    
    final function fetch($sql) {    
        return $sql->fetch_array();        
    }
    
    final function fetch_assoc($sql) {    
        return $sql->fetch_assoc();       
    }
    
    final function fetch_all($sql) {
        while( $res = $this->fetch( $sql) ) {
            $result[] = $res;
        }
        return $result ? $result : false;
    }
    
    final function fetch_all_assoc($sql) {
        while( $res = $this->fetch_assoc( $sql) ) {
            $result[] = $res;
        }
        return $result ? $result : false;
    }
    
    final function last_insert_id() {    
        return $this->last_insert_id;        
    }   
    
    /**
     * Dynamisches Insert zur Bequemlichkeit. Muss weg da.
     * 
     * @param array($config) insert values
     * 
     * @return integer last_insert_ID
     */
    final function insert($config) {    
        extract($config);       
        $this->query( "INSERT INTO $insert VALUES $values" );
        $this->last_insert_id = $this->db->insert_id;
        return $this->last_insert_id;
    }
    
    /**
     * Dynamisches Update zur Bequemlichkeit. Muss weg da.
     * 
     * @param array($config) table set
     * 
     * @return bool 
     */
    final function update($config) {
        extract($config);
        return $this->query( "UPDATE $table SET $set" );
    }    
    
    /**
     * Dynamisches Delete zur Bequemlichkeit. Muss weg da.
     * 
     * @param array($config) from where
     * 
     * @return integer last_insert_ID
     */
    final function delete( $config ) {
        extract( $config );
        return $this->query( "DELETE FROM $from WHERE $where" );
    }
    
    /**
     * Dynamisches Select zur Bequemlichkeit. Muss weg da.
     * 
     * @param array($config) select from where
     * 
     * @return array()|bool result|(success|error) 
     */
    final function select($config) {
        extract($config);
        $arr = array();
        $query = $this->query( "SELECT $select FROM $from WHERE $where" );
        while( $row = $this->fetch_assoc( $query ) ) {
            $arr[] = $row;
        }
        return ( $arr ) ? $arr : false;
    }
 
    /**
     * Gibt ein assoziatives Array mit Einstellungen aus der Tabelle 'settings' zurück. 
     * Wenn der Parameter key angegeben wurde, wird nur der jeweilige Datensatz ausgegeben.
     * 
     * @param string $key optional
     * 
     * @return string|array eines|alle
     */
    final function settings($key = false) { 
        $query = $this->query( ( false !== $key ) ? "SELECT * FROM settings WHERE settings.key='$key'" : "SELECT * FROM settings" );
        $arr = false;
        while( $row = $this->fetch( $query ) ) {
            $arr[$row['key']] = $row['value'];
        }   
        return $key ? $arr[$key] : $arr;
    }
    
    /**
     * Ein assoziatives Array eines Items.
     * 
     * Kann mit oder ohne Metadaten aufgerufen werden.
     * Dazu einen Index 'metadata' im Array(config) anlegen.
     * 
     * @param array() ItemID
     * 
     * @return array()|bool item|(success|error)
     */
    final function single( $config ) { 
        extract($config);
        $item = $this->fetch_assoc( $this->query( "SELECT * FROM item WHERE id=$config[id]" ) ); 
        if( !$item["id"] ) { return false; };
        if( @$metadata && $metas = $this->single_meta($item['id'])) {
            foreach( $metas as $k => $v ) {
                $item[$k] = $v;
            }
        }
        return isset($item['id']) ? $item : false;
    }
    
    /**
     * Ein assoziatives Array aller Metadaten zu einem Item.
     * 
     * Wird der Parameter index auf true gesetzt, wird die jeweilige ID in Array mit ausgegeben. 
     * In dem Fall kann es nicht mehr an ein Item eangehängt werden! 
     * 
     * @param array() ItemID
     * @param bool index
     * 
     * @return array()|bool metadata|(success|error)
     */
    final function single_meta($item_id,$index=false) {    
        if($index) {        
            $item_meta = $this->query("SELECT meta_id, meta_key as k, meta_value as v FROM item_meta WHERE meta_item_id=$item_id");             
        } else {        
            $item_meta = $this->query("SELECT meta_key as k, meta_value as v FROM item_meta WHERE meta_item_id=$item_id");            
        }        
        $metadata = false;       
        while($metas = $this->fetch_assoc($item_meta)) {        
            if( $index ) {
                //print_r( $metas );
                $metadata[$metas['meta_id']] = array( $metas['k'], $metas['v']);            
            } else {
                $metadata[$metas['k']] = $metas['v'];  
            }         
        }              
        return ($metadata) ? $metadata : false;        
    }

    /**
     * Diese haessliche Funktion wird nur noch von der Klasse menu benutzt..
     *
     * @deprecated
     */
    final function archive($config) {    
        extract($config);        
        $archive = false;        
        if($items = $this->query("SELECT $select FROM $from WHERE $where")) {        
            while($item = $this->fetch($items)) {                                   
                $archive[] = $item;                 
            }            
        }
        return (false !== $archive) ? $archive : false;        
    }
    
    /**
     * Prueft, welche Ordner in ../content/themes/* enthalten sind.
     * 
     * @param false
     * 
     * @return array() alle Theme Ordner
     */
    final function installed_themes() {         
        if( $files = opendir( ABSPATH . 'content' . DS . 'themes') ) {             
            while ( false !== ( $file = readdir( $files ) ) ) {             
                if ( $file != '.' && $file != '..' ) {                
                    $themes[] = $file;                        
                }                             
            }                              
            closedir($files);                           
        }                   
        return $themes ? $themes : false;                  
    }
    
    /**
     * Parst den Querystring und gibt ihn als Array zurueck - sonst false
     * Wenn ein Parameter uebergeben wird, wird dieser (wenn vorhanden) zurueckgegeben - sonst false
     * 
     * @todo SQL injection fix
     * 
     * @param string $key optional Der Schluesselindex
     * 
     * @return array()|bool parameter|(success|error)
     */
    final function request($key=false) {   
        if($_SERVER['QUERY_STRING']) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);            
            if(false !== $key) {            
                if(!empty($parameters[$key])) {                       
                    if($key == 'id') {                    
                        return (int)$parameters[$key];                        
                    } else {                    
                        return $parameters[$key];                       
                    }                   
                } else {               
                    return false;                                
                }                        
            } else {              
                return ($parameters) ? $parameters : false;                    
            }                 
        }           
    }
        
}

?>
