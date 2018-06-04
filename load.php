<?php

/**
 * @author Manuel Zarat
 */

define('DS', DIRECTORY_SEPARATOR);
define('ABSPATH', dirname(__file__) . DS);

/**
 * Die abstrakte Klasse core liegt nicht im Standard Klassenverzeichnis
 */
include 'system' . DS . 'core.php';

/**
 * Alle anderen Klassen werden beim Aufruf eingebunden
 */
spl_autoload_register(function ($class) {
    include 'system' . DS . 'classes' . DS . $class . '.php';
});

?>
