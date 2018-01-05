<?php

class menu extends system {

private $menu_id;
private $divclass;
private $ulclass; 
private $sublevel = 0;

    function config($config) {
        $this->menu_id = $config['id'];  
    }
    
    protected function items($id=0) {
        if($parents = $this->archive($select="*", $from="menu", $where="menu_id=" . $this->menu_id . " AND parent=$id ORDER BY sort")) {
            if($this->sublevel<1) { echo "\n" . str_repeat("\t", $this->sublevel) . "<ul class='menu level-$this->sublevel'>\n"; }
            else { echo "\n" . str_repeat("\t", $this->sublevel) . "<ul class='submenu level-$this->sublevel'>\n"; } 
            $this->sublevel = $this->sublevel+1;  
            foreach($parents as $item) {    
                echo str_repeat("\t", $this->sublevel) ."<li><a href='$item[link]'>$item[label]</a>";
                if($children = $this->items($item['id'])) {
                    echo $children;
                }
                echo str_repeat("\t", $this->sublevel) . "</li>\n";
            } 
            $this->sublevel = $this->sublevel-1;       
            echo str_repeat("\t", $this->sublevel) . "</ul>\n";
        }
    }
    
    public function html() {
        $this->divclass = "nav-container";    
        echo "<div class='$this->divclass'>";
        $this->items();
        echo "</div>\n\n";
    }

}

?>
