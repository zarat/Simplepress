<?php

/**
 * Custom Theme
 *
 * @author Manuel Zarat
 * @date 06.01.2018
 * @license http://opensource.org/licenses/MIT
 * 
 */

class simplepress extends theme {

    function my_get_header() {
        echo "ich bin ein custom theme aus ../content/themes/simplepress/simplepress.php";
    }
    
    function my_object_path() {
        echo "i am a content from custom theme\n";
    }

}

?>
