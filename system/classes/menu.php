<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * 
 */

class menu extends system {

private $menu_id;
private $divclass;
private $ulclass; 
private $sublevel = 0;

    /** 
     * Laedt das Menue
     *
     * @param array $config
     * @return void
     */
    function config($config) {
        $this->menu_id = $config['id'];  
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
    
    /**
     * Anzeigen
     * 
     * @return string
     */
    public function html() {
        $this->divclass = "nav-container";    
        echo "<div class='$this->divclass'>";
        $this->items();
        echo "</div>\n\n";
    }

}

?>
