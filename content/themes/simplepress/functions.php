<?php

/**
 * Vorlage einer functions.php
 *
 * Wird in Klasse theme eingebunden und kann Funktionen darin nutzen.
 *
 * @author Manuel Zarat
 *
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
$this->add_action('setup_theme','start_session');

?>
