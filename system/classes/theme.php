<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * 
 */

class theme extends system {

    private $includes = array('header','footer');
    private $variables = array();
    
    /**
     * Registriert Inhalte zum Einbinden an bestimmten Positionen (Hooks ->)
     * 
     * @param string $position Wo soll es eingefuegt werden
     * @param string $include Was soll eingefuegt werden
     * 
     * @return void
     * 
     */
    final function set_include($position,$include) {
        $this->include_positions[$position][] = $include;
    }
    
    /**
     * Zeigt einzubindende Inhalte zu einer Position an
     * 
     * @param string position
     * @return void
     * 
     */
    final function get_includes($position) {
        foreach($this->include_positions[$position] as $include) {
            echo $include . "\n";
        }
    }
    
    /**
     *  Bindet angeforderte Objekt Templates ein
     *  Muss aufgeteilt werden um Hooks einzubauen.
     *  
     * @return void
     */ 
    function object_path() { 
        $this->type = !empty($this->the_querystring('type')) ? $this->the_querystring('type') : "default"; // default
        $this->id = !empty($this->the_querystring('id')) ? $this->the_querystring('id') : false; // 0
        switch($this->type) {
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
        $system = new system();
        switch($this->view) {        
            case "single": 
                $item = $this->single($this->type,$this->id); 
                if(is_file(ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->type . ".php")) {
                
                    include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single-" . $this->type . ".php";
                    
                } else {
                
                    include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "single.php";
                    
                }
            break;
            case "archive":
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "archive.php";
            break;            
            case "default": 
                include ABSPATH . "content" . DS . "themes" . DS . $this->settings('site_theme') . DS . "index.php";
            break;        
        }    
        return false;      
    }
    
    /**
     * Ausgabe
     * 
     * @return string
     * 
     */
    final function display_page() {
    
        echo "<html>\n";
        echo "<head>\n";
        $this->get_includes('header');
        echo "</head>\n";
        echo "<body>\n";
        echo "<div class='main-wrapper'>\n";
        $this->object_path();
        echo "</div>";
        echo "\n</body>\n";
        $this->get_includes('footer');
        echo "</html>\n";
    
    }

}

?>
