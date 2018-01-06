<?php 

require 'load.php';

$project = new system();

$theme = new theme();

$theme->set_include('header','<title>project</title>');
$theme->set_include('header','<script src="https://simplepress.ml/admin/js/admin.js"></script>');
$theme->set_include('header','<link rel="stylesheet" href="../content/themes/' . $project->settings('site_theme') . '/css/style.css">');
$theme->set_include('header','<link rel="stylesheet" href="../content/themes/' . $project->settings('site_theme') . '/css/menu.css">');
$theme->set_include('header',"<script src='../content/themes/" . $project->settings('site_theme') . "/js/externallinks.js'></script>");
$theme->set_include('footer','<!-- powered by qwerty -->');

$theme->display_page();

?>
