SET NAMES utf8;
SET time_zone = '+02:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) unsigned NOT NULL,
  `label` varchar(30) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `link` varchar(150) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menu` (`id`, `menu_id`, `label`, `link`, `parent`, `sort`) VALUES
(NULL,	1,	'Home',	'../',	0,	1),
(NULL,	1,	'About',	'../?type=page&id=2',	0,	2);

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `title` varchar(150) NOT NULL,
  `keywords` varchar(150) NOT NULL,
  `description` varchar(150) NOT NULL,
  `content` longtext NOT NULL,
  `date` int(15) NOT NULL,
  `status` int(1) NOT NULL,
  `category` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `item` (`id`, `type`, `title`, `keywords`, `description`, `content`, `date`, `status`, `category`) VALUES
(1,	'category',	'Allgemein',	'homepage,blog,simplepress', 'Allgemeine Themen', '',	1491560699,	1,	1),
(2,	'page',	'About',	'homepage,blog,simplepress',	'',	'Hallo auf deiner neuen Homepage. Seiten wie diese werden nicht automatisch in hierarchischen Archiven angelegt. Du kannst sie im Men&uuml;manager anordnen.',	1515507779,	1,	1),
(3,	'post',	'Dein neuer Blog',	'',	'',	'Willkommen zu deinem neuen Blog! Das ist ein erster Post, den du im <a href="../admin">Adminbereich</a> bearbeiten oder wieder entfernen kannst. Sieh dich dort am besten gleich mal um und dann auf ans bloggen!',	1515107311,	1,	1);

DROP TABLE IF EXISTS `item_meta`;
CREATE TABLE `item_meta` (
  `meta_id` int(10) NOT NULL AUTO_INCREMENT,
  `meta_item_id` int(10) NOT NULL,
  `meta_key` varchar(30) NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `key` varchar(30) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`key`, `value`) VALUES
('site_title',	'SimplePress'),
('site_subtitle',	'Just another simplepress blog'),
('site_keywords',	'CMS, Homepage, Website'),
('site_description',	'Simplepress ist ein objektorientiertes CMS in PHP und SQL'),
('site_theme',	'simplepress'),
('site_language',	'de');
