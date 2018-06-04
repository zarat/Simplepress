<?php

/**
 * @author Manuel Zarat
 */

if($item) { 

    echo "<div class ='sp-content-item-head'>" . $item['title'] . "</div>\n";
    echo "<div class ='sp-content-item-body'>" . $item['content'] . "</div>\n";
    
} else {

    echo "<div class ='sp-content-item-head'>" . $system->_t('no_items_to_display') . "</div>\n";
    
}

?>
