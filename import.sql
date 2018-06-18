--
-- Simplepress SQL Import
--
-- Beispieljoin Terms
--
-- SELECT tm.taxonomy, t.name
-- FROM term t
-- INNER JOIN term_meta tm ON t.term_id = tm.term_id
-- INNER JOIN term_relation tr ON tm.term_id = tr.term_meta_id
-- INNER JOIN item i ON tr.object_id = i.id
-- WHERE tr.object_id = 3
--

SET NAMES utf8;
SET time_zone = '+02:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `menu`
--

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

--
-- Daten für Tabelle `menu`
--

INSERT INTO `menu` (`id`, `menu_id`, `label`, `link`, `parent`, `sort`) VALUES
(NULL,	1,	'Home',	'../',	0,	1),
(NULL,	1,	'About',	'../?type=page&id=2',	0,	2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `item`
--

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

--
-- Daten für Tabelle `item`
--

INSERT INTO `item` (`id`, `type`, `title`, `keywords`, `description`, `content`, `date`, `status`, `category`) VALUES
(1,	'category',	'Allgemein',	'homepage,blog,simplepress', 'Allgemeine Themen', '',	1491560699,	1,	1),
(2,	'page',	'About',	'homepage,blog,simplepress',	'',	'Das ist eine Testseite. Auch Seiten kannst du im &lt;a href=&quot;../admin&quot;&gt;Adminbereich&lt;/a&gt; bearbeiten und entfernen.',	1515507779,	1,	1),
(3,	'post',	'Dein neuer Blog',	'',	'',	'Willkommen zu deinem neuen Blog! Das ist ein erster Post, den du im &lt;a href=&quot;../admin&quot;&gt;Adminbereich&lt;/a&gt; bearbeiten oder wieder entfernen kannst. Sieh dich dort am besten gleich mal um und dann auf ans bloggen!',	1515107311,	1,	1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `item_meta`
--

DROP TABLE IF EXISTS `item_meta`;
CREATE TABLE `item_meta` (
  `meta_id` int(10) NOT NULL AUTO_INCREMENT,
  `meta_item_id` int(10) NOT NULL,
  `meta_key` varchar(30) NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `key` varchar(30) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` (`key`, `value`) VALUES
('site_title',	'SimplePress'),
('site_subtitle',	'Just another simplepress blog'),
('site_keywords',	'CMS, Homepage, Website'),
('site_description',	'Simplepress ist ein objektorientiertes CMS in PHP und SQL'),
('site_theme',	'simplepress'),
('site_language',	'de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `displayname` varchar(30) NOT NULL,
  `token` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `term_taxonomy`
--

CREATE TABLE `term_taxonomy` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `taxonomy` varchar(30) NOT NULL DEFAULT '',
  `parent` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `term_taxonomy`
--

INSERT INTO `term_taxonomy` (`taxonomy`) VALUES ('type'), ('tag');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `term`
--

CREATE TABLE `term` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `taxonomy_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `term`
--

INSERT INTO `term` (`name`,`taxonomy_id`) VALUES ('page', 1), ('post', 1), ('wichtig', 2), ('unwichtig', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `term_relation`
--

CREATE TABLE `term_relation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) NOT NULL DEFAULT '0',
  `taxonomy_id` int(10) NOT NULL DEFAULT '0',
  `term_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `term_relation`
-- post, page
--

INSERT INTO `term_relation` (`object_id`, `taxonomy_id`, `term_id`) VALUES (3, 1, 2), (2, 1, 1);
