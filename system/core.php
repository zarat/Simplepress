<?php

/**
 * Die Klasse Core sollte wenn moeglich nicht angefasst werden.
 * Sie ist das Kernstueck - was hier nicht stabil laeuft ist BETA
 * Sollte wenn moeglich auch abwaerts kompatibel bleiben :S
 * 
 * Sie baut die Datenbankverbindung auf und wickelt Querys ab. 
 * 
 * @author Manuel Zarat
 * @date 05.01.2018
 * 
 */

abstract class core {               
 
    private $db = false; 
    private $last_insert_id = false; 

    /**
     * Datenbankverbindung aufbauen
     * 
     * @todo Unterstuetzung verschiedener Datenbanktypen wie MySQL, SQLite,.. u.a.
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
    final function last_insert_id() {
        return $this->last_insert_id;
    }
    
    /**
     * Globale Einstellungen aus der Tabelle 'settings' holen.
     * 
     */
    final function settings($key=null) {
        $query = (null!==$key) ? "SELECT * FROM settings WHERE settings.key='$key'" : "SELECT * FROM settings";
        $result = $this->query($query);
        $ret = false;
        while($r = $this->fetch($result)) {
            $ret[$r['key']] = $r['value'];
        }   
        return isset($ret[$key]) ? $ret[$key] : $ret;
    }
        
    /**
     * Fuegt ein Item in die DB ein
     * 
     * @todo Custom Fields
     * 
     * @return int DB::last_insert_id
     */     
    final function insert($config) {
        extract($config);
        $query = "INSERT INTO $insert VALUES $values";
        $res = $this->query($query) or 'error';
        $this->last_insert_id = $this->db->insert_id;
        return $this->last_insert_id;
    }
    final function update($config) {
        extract($config);
        $query = "UPDATE $table SET $set";
        $res = $this->query($query) or 'error';
        return $res;
    }    
    final function delete($config) {
        extract($config);
        $query = "DELETE FROM $from WHERE $where";
        $res = $this->query($query) or 'error';
        return $res;
    }
    
    /**
     * Einzelnes Item
     * 
     * Wenn im $cfg Array ein Index "metadata" => true enthalten ist
     * werden die Metadaten mit ausgegeben!
     * 
     * @param array id,..
     * @return array Item
     * 
     */
    final function single($cfg) {
        extract($cfg);
        $item = $this->fetch_assoc($this->query("SELECT * FROM object WHERE id=$id"));
        $result = $item;
        if(isset($metadata) && $metas = $this->single_meta($item['id'])) {
            $result = array_merge($item, array_column($metas, 'v', 'k'));
        }
        return ($result) ? $result : false;
    }
    
    /**
     * Metadaten zu einem Item
     *
     * @var int ItemID
     * @return array Metadata
     * 
     */
    final function single_meta($item_id) {
        $item_meta = $this->query("SELECT meta_key as k, meta_value as v FROM object_meta WHERE meta_item_id=$item_id"); 
        $metadata = false;
        while($metas = $this->fetch($item_meta)) {
            $metadata[] = $metas;
        }      
        return ($metadata) ? $metadata : false;
    }
    
    /**
     * Ein Archiv   
     *
     * @todo Sollten die Archive standardmaessig mit oder ohne Metadaten ausgegeben werden? 
     * @todo Pagination
     *
     * @param array
     * @return array
     * 
     */
    final function archive($config) {
        extract($config);
        $archive = false;
        if($items = $this->query("SELECT $select FROM $from WHERE $where")) {
            while($item = $this->fetch($items)) {            
                if($metadata && $metas = $this->single_meta($item['id'])) {
                    $new_item = array_merge($item, array_column($metas, 'v', 'k'));
                    $item = $new_item;
                }            
                $archive[] = $item; 
            }
        }
        return (false !== $archive) ? $archive : false;
    }
        
}

?>
