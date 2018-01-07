<?php

/**
 * Diese Klasse sollte wenn moeglich nicht angefasst werden.
 *
 * @author Manuel Zarat
 * @date 05.01.2018
 * @license http://opensource.org/licenses/MIT
 * 
 */

abstract class core {               
 
    private $db = false;  

    /**
     * Datenbankverbindung aufbauen
     * 
     * Unterstuetzung verschiedener Datenbanktypen wie MySQL, SQLite,.. u.a.
     * 
     * @todo
     * 
     */
    final function __construct() {          
        if(is_file(ABSPATH . "config.php")) { include ABSPATH . "config.php"; } else { include ABSPATH . "install.php"; exit(); }                
        $this->db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);                                                 
    }
    
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
     * Globale Einstellungen aus der Tabelle 'settings' holen.
     * 
     * Das Feld 'key' ist mit Tabellennamen davor angegeben weil 'key' ein Anweisungswort ist.
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
     * Ein Archiv aus der Datenbank holen
     * 
     * @todo Neue Objekttypen sollten manuell erstellt werden koennen.
     * @todo aktueller Parameter ist shice
     *
     * @param array $config array('select','from','where')
     * @return array Ein Array aus allen enthaltenen Items.
     * 
     */
    final function archive($config) {
        extract($config);
        $items = $this->query("SELECT $select FROM $from WHERE $where");
        $result = false;
        while($item = $this->fetch($items)) {
            $result[] = $item;
        }
        return ($result) ? $result : false;
    }
    
    /**
     * Einzelnes Objekt aus der Datenbank holen
     * 
     * @param int $id
     * @return array
     * 
     */
    final function single($type,$id) {
        $item = $this->fetch($this->query("SELECT * FROM object WHERE type='$type' AND id=$id"));
        return ($item) ? $item : false;
    }
    
}

?>
