<?php 

define('DS', DIRECTORY_SEPARATOR);
define('ABSPATH', dirname(__file__) . DS);

require 'load.php';

//where should plugins take action?

$project = new system();



$theme = new theme();

$theme->set_include('header','<title>project</title>');
$theme->set_include('header','<script src="https://simplepress.ml/admin/js/admin.js"></script>');
$theme->set_include('header','<link rel="stylesheet" href="../content/themes/default/css/style.css">');
$theme->set_include('footer','<!-- powered by qwerty -->');

$theme->display_page();

?>