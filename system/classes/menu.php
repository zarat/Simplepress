<?php

/**
 * @author Manuel Zarat
 */

class menu extends system {

private $menu_id;
private $div;
private $ul; 
private $sublevel = 0;

    /** 
     * Laedt das Menue
     *
     * @param array $config
     * @return void
     */
    function config($config) {
        $this->menu_id = $config['id'];
        $this->div = "nav-container";
        $this->ul = "submenu";
    }
    
    /**
     * Rekursive Funktion, die sich selbst immer und immer wieder aufruft
     * 
     * @param id int
     * @return string
     */
    protected function items($id=0) {
        $query = array('select' => '*','from' => 'menu','where' => "menu_id=" . $this->menu_id . " AND parent=$id ORDER BY sort");
        if($parents = $this->archive($query)) {
        
            if($this->sublevel<1) { echo "\n" . str_repeat("\t", $this->sublevel) . "<ul class='menu level-$this->sublevel'>\n"; } /** Noch kin Submenu!!! */
            else { echo "\n" . str_repeat("\t", $this->sublevel) . "<ul class='$this->ul level-$this->sublevel'>\n"; } 
            $this->sublevel = $this->sublevel+1;  
            foreach($parents as $item) {    
                echo str_repeat("\t", $this->sublevel) ."<li><a href='$item[link]'>$item[label]</a>";
                if($children = $this->items($item['id'])) {
                    echo $children;
                }
                echo str_repeat("\t", $this->sublevel) . "</li>\n";
            } 
            $this->sublevel = $this->sublevel-1;
            /**
             * Adminlink bei letzter Iteration
             * 
             */
            if($this->sublevel<1 && $this->auth() ) { 
            
                echo str_repeat("\t", $this->sublevel) . "<li><a href='../admin'>Admin</a>\n";
                echo "<ul>";
                echo str_repeat("\t", $this->sublevel) . "<li><a href='../logout.php'>logout</a></li>\n";
                echo "</ul></li>";
                
            }       
            echo str_repeat("\t", $this->sublevel) . "</ul>\n";
            
        }
    }
    
    /**
     * Anzeigen
     * 
     * @return string
     */
    public function html() {    
        echo "<div class='$this->div'>";
        echo "<label class='responsive_menu' for='responsive_menu'>";
        echo "<span>Menu</span>";
        echo "</label>";
        echo "<input id='responsive_menu' type='checkbox'>"; 
        $this->items();                  
        echo "</div>\n\n";
    }

}

?>
