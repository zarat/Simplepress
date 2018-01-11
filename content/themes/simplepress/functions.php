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
$this->set_include("header","<title>SimplePress beta</title>");

$this->set_include("header","<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />");
$this->set_include("header","<meta name='viewport' content='width=device-width, initial-scale=1.0' />");
$this->set_include("header","<meta name='generator' content='SimplePress - https://github.com/zarat/simplepress' />");
$this->set_include("header","<meta name='description' content='Mein neues CMS'>");

$this->set_include("header","<link rel='stylesheet' href='../content/themes/simplepress/css/style.css'>");
$this->set_include("header","<link rel='stylesheet' href='../content/themes/simplepress/css/menu.css'>");
$this->set_include("header","<script src='../content/themes/simplepress/js/externallinks.js'></script>");

$this->set_include("footer","<!-- powered by SimplePress -->");

?>