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
 
    public $db = false;     
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
        $stmt = $this->db->prepare( "select id,email,password from user where email=? AND password=?" );           
        $stmt->bind_param( "ss" , $uid, $pass );
        $stmt->bind_result( $id, $email, $password);
        $stmt->execute();    
        $stmt->fetch();        
        return !empty( $id ) ? $id : false;        
    }
    
    /**
     * Meldet einen Benutzer ab indem es den Login Token aus der Datenbank entfernt. Der Cookie bleibt bestehen.
     * 
     * @param false
     * 
     * @return bool 
     */
    final function logout() {    
        if( !isset($_COOKIE["sp-uid"]) || empty($_COOKIE["sp-uid"]) )
            return false;
        $token = $_COOKIE['sp-uid'];        
        $stmt = $this->db->prepare( "update user set token='' where token=?" );           
        $stmt->bind_param( "s" , $token );
        $stmt->execute();          
    }
    
    /**
     * Prueft, ob ein Benutzer angemeldet ist indem es den Cookie mit dem Login Token aus der DB abgleicht.
     * 
     * @param false
     * 
     * @return integer|bool UserID|(success|error)
     */
    final function auth() {    
        if( !isset($_COOKIE["sp-uid"]) || empty($_COOKIE["sp-uid"]) )
            return false;
        $token = $_COOKIE['sp-uid'];
        $stmt = $this->db->prepare( "select id,email,password from user where token=?" );           
        $stmt->bind_param( "s" , $token );
        $stmt->bind_result( $id, $email, $password);
        $stmt->execute();    
        $stmt->fetch();        
        return !empty( $id ) ? $id : false;      
    }  
     
    private function sql_escape_string($query) {    
        return mysqli_real_escape_string($this->db, $query);            
    }   
         
    final function query($query) {     
        $this->sql_escape_string($query);        
        $result = $this->db->query($query);
        $this->last_insert_id = $this->db->insert_id;
        return $result;        
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
        return isset($result) ? $result : false;
    }
    
    final function fetch_all_assoc($sql) {
        while( $res = $this->fetch_assoc( $sql) ) {
            $result[] = $res;
        }
        return isset($result) ? $result : false;
    }
    
    final function last_insert_id() {    
        return $this->last_insert_id;        
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
        $query = $this->query( $key ? "SELECT * FROM settings WHERE settings.key='$key'" : "SELECT * FROM settings" );
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
        if( !$item["id"] ) 
            return false;       
        /**
         * get all taxonomies of this item
         */
        $tax = new taxonomy();
        $taxonomies = $tax->taxonomies_by_item_id( $item['id'] ); 
        if($taxonomies) {        
            foreach( $taxonomies as $taxonomy ) {            
                $item[ $taxonomy['taxonomy'] ] = array();                
                $all_terms = $tax->terms_by_taxonomy_id( $taxonomy['id']);                
                if( $all_terms ) {
                    foreach( $all_terms as $term ) {                
                        $item[ $taxonomy['taxonomy'] ] [$term['id']] = $term['name'];                    
                    }
                }                
            }           
        }        
        if( @$metadata && $metas = $this->single_meta($item['id'])) {
            foreach( $metas as $k => $v ) {
                $item[$k] = $v;
            }
        }
        return $item; 
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
        $metadata = false; // if no metadata by default return false       
        while($metas = $this->fetch_assoc($item_meta)) {        
            if( $index ) {
                //print_r( $metas );
                $metadata[$metas['meta_id']] = array( $metas['k'], $metas['v']);            
            } else {
                $metadata[$metas['k']] = $metas['v'];  
            }         
        }              
        return $metadata;      
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
        $secure_pattern = "/[a-zA-ZäöüÄÖÜ0-9- ]+$/";
        if($_SERVER['QUERY_STRING']) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);            
            if(false !== $key) {            
                if(!empty($parameters[$key])) {                       
                    preg_match($secure_pattern, $parameters[$key], $clear); // preg_match('/\w+/', $parameters[$key], $clear);
                    return $clear[0];                                         
                } else {               
                    return false;                                
                }                        
            } else {
                $i = 0;
                foreach($parameters as $parameter) {
                    preg_match($secure_pattern, $parameter, $clear);
                    $parameters[$i] = $clear;
                    $i++;    
                }              
                return ($parameters) ? $parameters : false;                    
            }                 
        }           
    }
    
    function taxonomies() {
        $query = "select id, taxonomy from term_taxonomy";
        $result = $this->fetch_all_assoc( $this->query( $query ) );
        return $result;  
    }    
    
    function terms( $taxonomy = false, $item_id = false ) {
        $query = "select term.id, term.name from term ";
        if( $taxonomy ) {
            $query .= "
                join term_relation tr on tr.term_id=term.id 
                where tr.taxonomy_id = ( select id from term_taxonomy where taxonomy='$taxonomy' ) 
                and tr.term_id = term.id
                ";
            if( $item_id ) {
                $query .= "
                and tr.object_id=$item_id
                ";            
            }    
            $query .= "
                group by term.id
                ";
        } 
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;    
    }

    function item_terms_by_taxonomy_id( $item_id, $parent_taxonomy ) {
        $query = "select * from term where id in ( select term_id from term_relation where object_id=$item_id and taxonomy_id=$parent_taxonomy )";
        $result = $this->fetch_all_assoc( $this->query( $query ) ); 
        return $result;
    }
 
    function relation( $patterns, $item ) {
     
        /* php7.4 fix if there is no result */
        if(null == $item || false == $item)
            return false;
     
        if( preg_match_all( '/'.$patterns.'/', $item['type_str'], $matches) || preg_match_all( '/'.$patterns.'/', $item['type_int'], $matches) ) {
            return $matches;
        }
        return false;    
    }
 
    // todo intval is ugly
    final function getUserById($id) {         
        $sid = intval($id);
        $user = $this->fetch_assoc( $this->query( "select * from user where id=$sid" ) );        
        return isset($user) ? $user : false;        
    }
 
    // todo pdo
    final function currentUser() {    
        $token = @$_COOKIE['sp-uid'];         
        $result = $this->fetch_assoc( $this->query( "select id, email, fname, lname from user where token='$token'" ) );             
        return $result;     
    }
        
}

?>
