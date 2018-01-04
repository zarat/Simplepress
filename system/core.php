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
    
    final function themes() {    
        $themes = false;        
        if($files = opendir(ABSPATH . 'content' . DS . 'themes')) {        
                while (false !== ($file = readdir($files))) {        
                    if ($file!='.' && $file!='..'){ 
                        if(is_file(ABSPATH . 'content' . DS . 'themes' . DS . $file . DS . 'functions.php')) {
                            $themes[] = $file;
                        } 
                    }        
                }        
            closedir($files);    
        }        
        return $themes;
    }
    
    final function plugins() {    
        $plugins = false;        
        if($files = opendir(ABSPATH . 'content' . DS . 'plugins')) {        
                while (false !== ($file = readdir($files))) {        
                    if ($file!='.' && $file!='..'){           
                        if(is_file(ABSPATH . 'content' . DS . 'plugins' . DS . $file . DS . 'functions.php')) {
                            $plugins[] = $file;
                        } 
                    }        
                }        
            closedir($files);    
        }        
        return $plugins;
    }
    
}

?>
