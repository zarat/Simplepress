<?php

/**
 * @author Manuel Zarat
 */

abstract class core {               
 
    private $db = false; 
    private $last_insert_id = false; 

    /**
     * @todo Unterstuetzung verschiedener Datenbanktypen wie MySQL, SQLite,.. u.a.
     */
    final function __construct() {          
        if(is_file(ABSPATH . "config.php")) { include ABSPATH . "config.php"; } else { include ABSPATH . "install.php"; exit(); }                
        $this->db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);                                                 
    }

    final function __destruct() {          
        if($this->db) {
            $this->db->close();
            $this->db = false;
        }                                                 
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
    final function select($config) {
        extract($config);
        $query = "SELECT $select FROM $from WHERE $where";
        $ret = [];
        $res = $this->query($query) or 'error';
        while($r = $this->fetch($res)) {
            $ret[] = $r;
        }
        return ($ret) ? $ret : false;
    }
 
    /**
     * Gibt ein assoziatives Array mit Einstellungen aus der Tabelle 'settings' zurück.
     * 
     * Wenn der Parameter key angegeben wurde, wird nur der jeweilige Datensatz ausgegeben.
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
     * Ein assoziatives Array eines Items.
     * 
     * Kann mit oder ohne Metadaten aufgerufen werden.
     * Dazu einen Index 'metadata' im Array(config) anlegen.
     */
    final function single($cfg) {
        extract($cfg);       
        $item = false;
        $item = @$this->fetch_assoc($this->query("SELECT * FROM object WHERE id=$id"));
        if(!$item) { return false; }
        $result = $item;
        if(isset($metadata) && $metas = $this->single_meta($item['id'])) {
            $result = array_merge($item, array_column($metas, 'v', 'k'));
        }
        return ($result) ? $result : false;
    }
    
    /**
     * Ein assoziatives Array aller Metadaten zu einem Item.
     * 
     * Wird der Parameter index auf true gesetzt, wird die jeweilige ID des Metatags mit ausgegeben. 
     * In dem Fall kann es allerdngs nicht mehr an ein Item eangehängt werden.
     */
    final function single_meta($item_id,$index=false) {
        if($index==true) {
            $item_meta = $this->query("SELECT meta_id as id, meta_key as k, meta_value as v FROM object_meta WHERE meta_item_id=$item_id"); 
        } else {
            $item_meta = $this->query("SELECT meta_key as k, meta_value as v FROM object_meta WHERE meta_item_id=$item_id");
        }
        $metadata = false;
        while($metas = $this->fetch($item_meta)) {
            $metadata[] = $metas;
        }      
        return ($metadata) ? $metadata : false;
    }
    
    /**
     * Gibt ein Array mit Ergebnissen aus einer Tabelle aus.
     * 
     * Wird fuer das Menue verwendet.
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
     * @todo Prueft nur, welche Ordner in ../content/themes/* enthalten sind.
     */
    final function installed_themes() {
        $themes = null;     
        if ($files = opendir( ABSPATH . 'content' . DS . 'themes')) {     
            while (false !== ($file = readdir($files))) { 
                if ($file!='.' && $file!='..') { 
                    $themes[] = $file;     
                }              
            }                  
            closedir($files);               
        }           
        return $themes;          
    }
    
    /**
     * Parst den Querystring und gibt ihn als Array zurueck - sonst false
     * Wenn ein Parameter uebergeben wird, wird dieser (wenn vorhanden) zurueckgegeben - sonst false
     */
    final function request($key=false) {
        if($_SERVER['QUERY_STRING']) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
            if(false !== $key) {
                if(!empty($parameters[$key])) {       
                    /** 
                     * SQL Injection 
                     * @todo HOTFIX 
                     */
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
