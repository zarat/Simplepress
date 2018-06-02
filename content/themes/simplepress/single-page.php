<?php

/**
 * @author Manuel Zarat
 * 
 */

$item = $system->single(array('type'=>$system->request('type'),'id'=>$system->request('id'))); 

echo "<div class='sp-content'>\n";

if($item) { 

    echo "<div class ='sp-content-item-head'>" . $item['title'] . "</div>\n";
    echo "<div class ='sp-content-item-body'>" . $item['content'] . "</div>\n";
    
} else {

    echo "<div class ='sp-content-item-head'>" . $system->_t('no_items_to_display') . "</div>\n";
    
}

echo "</div>\n";

?>
