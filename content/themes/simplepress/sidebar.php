<?php

/**
 * @author Manuel Zarat 
 */
 
echo "<div class='sp-sidebar-item'>";
    echo "<div class='sp-sidebar-item-head'>Suche</div>";
    echo "<div class='sp-sidebar-item-box'>\n";
        echo "<div class='sp-sidebar-item-box-body'><div class='container'><form><input type='hidden' name='type' value='search'><input type='text' name='term'></form></div></div>\n";
    echo "</div>\n";
echo "</div>";

echo "<!--BeginNoIndex-->";

$conf = array('select' => 'id,title','from' => 'item','where' => 'status=1 AND type="category"');
echo "<div class='sp-sidebar-item'>";
    echo "<div class='sp-sidebar-item-head'>Kategorien</div>";
    foreach($this->archive($conf) as $cat) {
        echo "<div class='sp-sidebar-item-box'>\n";
            echo "<div class='sp-sidebar-item-box-head'><a href='../?type=category&id=$cat[id]'>$cat[title]</a></div>\n";
        echo "</div>\n";
    }
echo "</div>";

$conf = array('select' => '*','from' => 'item','where' => 'status=1 AND type="post" order by rand() limit 5');
echo "<div class='sp-sidebar-item'>";
    echo "<div class='sp-sidebar-item-head'>Weiterlesen</div>";
    foreach($this->archive($conf) as $post) {
        echo "<div class='sp-sidebar-item-box'>\n";
            echo "<div class='sp-sidebar-item-box-head'><a href='../?type=post&id=$post[id]'>$post[title]</a></div>\n";
        echo "</div>\n";
    }
echo "</div>";

echo "<!--EndNoIndex-->";

?>
