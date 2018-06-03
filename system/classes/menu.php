<?php

/**
 * @author Manuel Zarat
 */

class menu extends system {

private $menu_id;
private $div;
private $ul; 
private $sublevel = -1;

    function config($config) {
        $this->menu_id = $config['id'];
        $this->div = "nav-container";
        $this->ul = "submenu";
    }

    protected function items($id=0) {
        $query = array('select' => '*','from' => 'menu','where' => "menu_id=" . $this->menu_id . " AND parent=$id ORDER BY sort");
        if($parents = $this->archive($query)) {                
            $this->sublevel++;           
            echo str_repeat("\t", $this->sublevel) . "<ul class='menu level-$this->sublevel'>\n"; 
                             
                foreach($parents as $item) { 
 
                    if($children = $this->items($item['id'])) {
                        echo str_repeat("\t", $this->sublevel) ."<li><a href='$item[link]'>$item[label]</a>";
                        echo $children;
                        echo str_repeat("\t", $this->sublevel) . "</li>\n";
                    } else {
                        echo str_repeat("\t", $this->sublevel+1) ."<li><a href='$item[link]'>$item[label]</a></li>\n";
                    }
                 
                }             
                   
            echo str_repeat("\t", $this->sublevel) . "</ul>\n"; 
            $this->sublevel--;           
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
