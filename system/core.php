<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * 
 */

abstract class core {               
 
    private $db = false;  

    /**
     * Datenbankverbindung aufbauen
     * 
     */
    final function __construct() {          
        if(is_file(ABSPATH . "config.php")) { include ABSPATH . "config.php"; } else { include ABSPATH . "install.php"; exit(); }                
        $this->db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);                                                 
    }
    
    /**
     * Datenbankinteraktionen
     * 
     */
    private function sql_escape_string($query) {
        return mysqli_real_escape_string($this->db, $query);    
    }        
    private function query($query) { 
        $this->sql_escape_string($query);
        return $this->db->query($query);
    }
    private function fetch($sql) {
        return $sql->fetch_array();
    }
    private function fetch_assoc($sql) {
        return $sql->fetch_assoc();
    }
    
    /**
     * Einstellungen aus der Tabelle settings
     * 
     * @param string $key
     * @return string/array
     * 
     */
    final function settings($key=null) {
        $query = (null!==$key) ? "SELECT * FROM settings WHERE settings.key='$key'" : "SELECT * FROM settings";
        $result = $this->query($query);
        while($r = $this->fetch($result)) {
            $ret[$r['key']] = $r['value'];
        }   
        return (null !== $key) ? $ret[$key] : $ret;
    }
    
    /**
     * Archiv von Objekten
     * 
     * @param array $config
     * @return array
     * 
     */
    function archive($config) {
        extract($config);
        $items = $this->query("SELECT $select FROM $from WHERE $where");
        $result = false;
        while($item = $this->fetch($items)) {
            $result[] = $item;
        }
        return $result;
    }
    
    /**
     * Einzelnes Objekt
     * 
     * @param int $id
     * @return array
     * 
     */
    function single($type,$id) {
        $item = $this->fetch($this->query("SELECT * FROM object WHERE type='$type' AND id=$id"));
        return ($item) ? $item : array('error' => 'no such object');
    }
    
}

?>
