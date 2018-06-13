<?php

/**
 * @author Manuel Zarat
 */

define('DS', DIRECTORY_SEPARATOR);
define('ABSPATH', dirname(__file__) . DS);                  
define('THEME_DIR', ABSPATH . DS . "content" . DS . "themes" . DS);

/**
 * Klassen werden zur Laufzeit eingebunden.
 * Die Klasse core liegt nicht darin!
 */
spl_autoload_register(function ($class) {
    include 'system' . DS . 'classes' . DS . $class . '.php';
});

include 'system' . DS . 'core.php';

?>
