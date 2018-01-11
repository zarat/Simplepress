SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) unsigned NOT NULL,
  `label` varchar(200) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `link` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menu` (`id`, `menu_id`, `label`, `link`, `parent`, `sort`) VALUES
(NULL,	1,	'Home',	'../',	0,	1),
(NULL,	1,	'About',	'../?type=page&id=3',	0,	2);

DROP TABLE IF EXISTS `object`;
CREATE TABLE `object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  `title` longtext NOT NULL,
  `keywords` longtext NOT NULL,
  `description` longtext NOT NULL,
  `content` longtext NOT NULL,
  `date` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `object` (`id`, `type`, `title`, `keywords`, `description`, `content`, `date`, `status`, `category`) VALUES
(1,	'category',	'Allgemein',	'',	'',	'Deine erste Kategorie zu allen Allgemeinen Themen',	1491560699,	1,	'1'),
(2,	'post',	'Ein erster Post',	'',	'',	'Willkommen zu deinem neuen Blog! Das ist dein erster Post, den du jederzeit im Adminbereich bearbeiten oder wieder entfernen kannst. Also los, auf ans bloggen!',	1515107311,	1,	'1'),
(3,	'page',	'About',	'',	'',	'Seiten werden nicht automatisch in hierarchischen Archiven angelegt.',	1515507779,	1,	'1');

DROP TABLE IF EXISTS `object_meta`;
CREATE TABLE `object_meta` (
  `meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_item_id` int(11) NOT NULL,
  `meta_key` varchar(30) NOT NULL,
  `meta_value` varchar(150) NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `key` varchar(30) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`key`, `value`) VALUES
('site_name',	'SimplePress 2.0'),
('site_subtitle',	'while(!perfect) { develop() }'),
('site_keywords',	'CMS, Homepage, Website'),
('site_description',	'Objektorientiertes CMS in PHP und MySQL'),
('site_theme',	'simplepress'),
('site_language',	'de');
