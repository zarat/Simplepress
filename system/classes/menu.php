<?php

/**
 * Simplepress Menu
 *
 * Primaeres Menue aus der Datenbank
 *
 * @author Manuel Zarat
 * @version 0.2.0
 * @link https://github.com/zarat/simplepress   
 * @since 06/2018 
 */
 
class menu extends system {

private $menu_id;
private $div;
private $ul; 
private $sublevel = 0;

    /**
     * Konfiguration fuer das Menue um den umrahmenden Container, UL und LI Elemente zu stylen.
     * 
     * @param array() $config div, ul und li
     * 
     * @return void
     */
    function config($config) {
        $this->menu_id = $config['id'];
        $this->ul = isset($config['ul']) ? $config['ul'] : "submenu";
        $this->li = isset($config['li']) ? $config['li'] : "li";
    }
    
    /**
     * Rekursive Funktion, die alle Items zum Menue hierarchisch durchlaeuft.
     * 
     * @param integer TopLevelID
     * 
     * @return html
     */
    protected function items($id=0) {
        if($parents = $this->query( "select * from menu where menu_id=" . $this->menu_id . " AND parent=$id ORDER BY sort" ) ) {  
            if($this->sublevel<1) { 
                echo "<ul class=\"menu level-$this->sublevel\">\n"; 
            } else { 
                echo "\n" . str_repeat("\t", $this->sublevel) . "<ul class=\"$this->ul level-$this->sublevel\">\n"; 
            }             
            $this->sublevel++; 
            foreach($parents as $item) {  
                echo str_repeat("\t", $this->sublevel) ."<li class=\"$this->li level-$this->sublevel\"><a href=\"$item[link]\">$item[label]</a>"; 
                if($children = $this->items($item['id'])) {    
                    echo $children;                 
                }                 
                echo str_repeat("\t", $this->sublevel) . "</li>\n";  
            }                         
            $this->sublevel--;                        
            if($this->sublevel < 1 && $this->auth() ) {                         
                echo "<li class=\"$this->li level-$this->sublevel\"><a href=\"../admin\">Admin</a>\n";
                echo "<ul class=\"$this->li level-$this->sublevel\">\n";
                echo "<li class=\"$this->li level-$this->sublevel\"><a href=\"../logout.php\">logout</a></li>\n";                
                echo "</ul>\n";                
                echo "</li>\n";                                
            }                               
            echo str_repeat("\t", $this->sublevel) . "</ul>\n";                                    
        }       
    }
    
    /**
     * Adminlinks und Logout
     * 
     * @return html
     */
    public function html( $config = false ) {        
        if( $config ) {
            extract( $config );                   
            echo $before ? $before : "";            
        }
        $this->items();
        if( $config ) {                   
            echo $after ? $after : "";            
        }                                        
    }
    
}

?>
