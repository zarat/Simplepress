<?php

/**
 * Vorlage einer functions.php
 *
 * @author Manuel Zarat
 *
 */

/**
 * Includes sind dazu da, an bestimmten Positionen innerhalb eines Themes Inhalte einfuegen.
 * 
 */
$this->set_include("header","<link rel='stylesheet' href='../content/themes/simplepress/css/menu.css'>");
$this->set_include("header","<script src='../content/themes/simplepress/js/externallinks.js'></script>");

$this->set_include("footer","<!-- powered by SimplePress -->");

?>
