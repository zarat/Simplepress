<?php

include 'system' . DS . 'core.php';

spl_autoload_register(function ($class_name) {
    include 'system' . DS . 'classes' . DS . $class_name . '.php';
});

?>
