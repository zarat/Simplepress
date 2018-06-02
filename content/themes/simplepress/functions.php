<?php

/**
 * @author Manuel Zarat
 */

/**
 * Eine Funktion definieren..
 */
function start_session() { 
    session_start();  
}

/**
 * Und in das Theme einhaken.
 */
$this->add_action('init','start_session');

?>
