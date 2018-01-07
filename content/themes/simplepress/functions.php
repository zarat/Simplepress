<?php

/**
 * Vorlage eines Themes
 *
 * @author Manuel Zarat
 * @date 05.01.2018
 * @license http://opensource.org/licenses/MIT
 * 
 * Datei wird in THEME::display() included
 *
 */

//global $theme;

/**
 * Includes sind dazu da, an bestimmten Positionen innerhalb eines Themes Inhalte einfuegen.
 * 
 */
$this->set_include('header','<title>SimplePress beta</title>');
$this->set_include('header','<link rel="stylesheet" href="../content/themes/simplepress/css/style.css">');
$this->set_include('header','<link rel="stylesheet" href="../content/themes/simplepress/css/menu.css">');
$this->set_include('footer','<!-- powered by qwerty -->');

/**
 * Trigger sind dazu da, um zu bestimmten Zeitpunkten waehrend des Seitenaufbau Funktionen aufrufen zu koennen.
 * 
 */

/**
 * Filter sind dazu da, um Funktionen im Theme zu ueberladen bzw ueberschreiben zu koennen.
 * 
 */
  


?>