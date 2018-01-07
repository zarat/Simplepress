<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * @license http://opensource.org/licenses/MIT
 * 
 * Datei wird in THEME::display() included
 *
 */

/**
 * Includes sind dazu da, an bestimmten Positionen innerhalb eines Themes Inhalte einfuegen.
 * 
 */
$this->set_include('header','<title>SimplePress beta</title>');
$this->set_include('header','<link rel="stylesheet" href="../content/themes/simplepress/css/style.css">');
$this->set_include('header','<link rel="stylesheet" href="../content/themes/simplepress/css/menu.css">');
$this->set_include('footer','<!-- powered by Simplepress - https://github.com/zarat/simplepress -->');

?>
