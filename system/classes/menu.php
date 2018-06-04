<?php
/**
 * @author Manuel Zarat
 */
class menu extends system {
private $menu_id;
private $div;
private $ul; 
private $sublevel = 0;
    function config($config) {
        $this->menu_id = $config['id'];
        $this->div = "nav-container";
        $this->ul = "submenu";
    }
    protected function items($id=0) {
        $query = array('select' => '*','from' => 'menu','where' => "menu_id=" . $this->menu_id . " AND parent=$id ORDER BY sort");
        if($parents = $this->archive($query)) {                
            if($this->sublevel<1) { 
                echo "<ul class='menu level-$this->sublevel'>\n";  
            } else { 
                echo "\n" . str_repeat("\t", $this->sublevel) . "<ul class='$this->ul level-$this->sublevel'>\n"; 
            }             
            $this->sublevel++;              
            foreach($parents as $item) {                
                echo str_repeat("\t", $this->sublevel) ."<li><a href='$item[link]'>$item[label]</a>";                   
                if($children = $this->items($item['id'])) {                
                    echo $children;                    
                }                 
                echo str_repeat("\t", $this->sublevel) . "</li>\n";                  
            }             
            $this->sublevel--;            
            if($this->sublevel < 1 && $this->auth() ) {             
                echo "<li><a href='../admin'>Admin</a>\n";
                echo "<ul>\n";
                echo "<li><a href='../logout.php'>logout</a></li>\n";
                echo "</ul>\n";
                echo "</li>\n";                
            }                   
            echo str_repeat("\t", $this->sublevel) . "</ul>\n";                        
        }
    }
    public function html() {    
        echo "<div class='$this->div'>\n";
        echo "<label class='responsive_menu' for='responsive_menu'>";
        echo "<span>Menu</span>";
        echo "</label>\n";
        echo "<input id='responsive_menu' type='checkbox'>\n"; 
        $this->items();                  
        echo "</div>\n";
    }
}
?>
