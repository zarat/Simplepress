<?php

/**
 * Vorlage einer functions.php
 *
 * @author Manuel Zarat
 *
 */

$this->add_action('setup_theme','init_before');

function init_before() { 
    session_start();  
}

?>
