<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * @license http://opensource.org/licenses/MIT
 * 
 */

/**
 * Einige Konstanten definieren
 * 
 */
define('DS', DIRECTORY_SEPARATOR);
define('ABSPATH', dirname(__file__) . DS);

/**
 * Die Klasse core einbinden
 * 
 */
include 'system' . DS . 'core.php';

/**
 * Alle anderen Klassen dynamisch zur Laufzeit einbinden
 * 
 */
spl_autoload_register(function ($class) {
    include 'system' . DS . 'classes' . DS . $class . '.php';
});

?>
