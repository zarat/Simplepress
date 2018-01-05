<?php

/*
Abstrakte Klasse kann nicht initialisiert, nur abgeleitet werden.
*/
abstract class core {

    //abstract function required_function();               
 
    private $db = false;  
     
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
    
    /*
    Gemeinsame Methoden aller ableitenden Klassen (können teilweise überschrieben werden)
    */ 
    final function settings($key=null) {
        $query = (null!==$key) ? "SELECT * FROM settings WHERE settings.key='$key'" : "SELECT * FROM settings";
        $result = $this->query($query);
        while($r = $this->fetch($result)) {
            $ret[$r['key']] = $r['value'];
        }   
        return (null !== $key) ? $ret[$key] : $ret;
    }
    
    function archive($select,$from,$where) {
        $query = "SELECT $select FROM $from WHERE $where";
        $items = $this->query($query);
        $result = false;
        while($item = $this->fetch($items)) {
            $result[] = $item;
        }
        return $result;
    }
    
    function single($type,$id) {
        $query = "SELECT * FROM object WHERE type='$type' AND id=$id";
        $item = $this->query($query);
        return $this->fetch($item);
    }
    
}

?>
