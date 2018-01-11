<?php

if(!empty($_POST['host']) && !empty($_POST['user']) && !empty($_POST['password']) && !empty($_POST['database']) && !empty($_POST['adminpass']) && !empty($_POST['adminlogin'])) {

$host = $_POST['host']; //mostly this is localhost if mysql server on the same machine.
$user = $_POST['user'];        	//database username here.
$password = $_POST['password'];   	//database password here.
$database = $_POST['database']; 		//the name of the database where you want the script installed in. 

if(!empty($_POST['site_name'])) { 
$site_name = $_POST['site_name'];
} else {
$site_name = $_SERVER['SERVER_NAME'];
}

if(!empty($_POST['adminpass'])) { 
$admin_pass = $_POST['adminpass'];
}

if(!empty($_POST['adminlogin'])) { 
$admin_login = $_POST['adminlogin'];
}

  // just the connection
$db = new mysqli($host,$user,$password) or die($db->error);

$init_1 = "CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$db->query($init_1) or die($db->error);

$init_2 = "USE $database";
$db->query($init_2) or die($db->error);

// Erstellt eine Tabelle für Objekte
$table_object = "CREATE TABLE IF NOT EXISTS `object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  `title` longtext NOT NULL,
  `keywords` longtext NOT NULL,
  `description` longtext NOT NULL,
  `content` longtext NOT NULL,
  `date` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `cat` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
$create_table_object = $db->query($table_object);

// Erstellt eine Tabelle für Einstellungen
$table_settings = "CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(30) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$create_table_settings = $db->query($table_settings);

// Erstellt eine Tabelle für Einstellungen
$table_object_meta = "CREATE TABLE IF NOT EXISTS `object_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `meta_key` varchar(30) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$create_table_object_meta = $db->query($table_object_meta);

$table_menu = "CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(10) unsigned NOT NULL,
  `menu_id` int(10) unsigned NOT NULL,
  `label` varchar(200) NOT NULL DEFAULT '',
  `link` varchar(100) NOT NULL DEFAULT '',
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
$create_table_menu = $db->query($table_menu);

$db->query("ALTER TABLE `menu` ADD PRIMARY KEY (`id`);");
$db->query("ALTER TABLE `menu` MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");

// Installationsverzeichnis festlegen
$this_basedir = basename(__DIR__);
$server_basedir = basename($_SERVER['DOCUMENT_ROOT']);
if($this_basedir != $server_basedir) {
    $basedir = $this_basedir;
} else {
    $basedir = false;
}

// Grundeinstellungen und Pfade in der DB speichern!
$table_settings_content = "INSERT INTO `settings` (`key`, `value`) VALUES
('site_name', '$site_name'),
('site_subtitle','Einfaches, kostenloses Blog CMS'),
('site_keywords', 'CMS, Homepage, Website'),
('site_description','Willkommen zu $site_name, meiner neuen SimplePress Website'),
('site_theme', 'simplepress');";

// Timestamp ermitteln
$date = new DateTime();
$actual_timestamp = $date->getTimestamp();

// Erste Kategorie eintragen
$insert_first_category = "INSERT INTO `object` (`type`, `title`, `content`, `status`, `date`) VALUES ('category', 'Allgemein', 'Deine erste Kategorie zu Allgemeinen Themen', 1, $actual_timestamp);";
$db->query($insert_first_category);

// Ersten Post eintragen
$insert_first_post = "INSERT INTO `object` (`type`, `title`, `cat`, `content`, `keywords`, `status`, `date`) VALUES ('post', 'Dein erster Blogeintrag', 1, 'Das ist dein erster Blogeintrag. Im Administrationbereich kannst du ihn bearbeiten, entfernen oder weitere Artikel schreiben.', 'blog,cms,website', 1, $actual_timestamp);";
$db->query($insert_first_post);
  
// Erste Seite eintragen
$insert_first_post = "INSERT INTO `object` (`type`, `title`, `cat`, `content`, `keywords`, `status`, `date`) VALUES ('page', 'About', 1, 'Das ist deine erste Unterseite. Im Administrationbereich kannst du sie bearbeiten, entfernen oder weitere Seiten anlegen.', 'blog,cms,website', 1, $actual_timestamp);";
$db->query($insert_first_post);

$insert_menu_items = "INSERT INTO `menu` (`id`, `menu_id`, `label`, `link`, `parent`, `sort`) VALUES (2, 1, 'Home', '../', 0, 0), (3, 1, 'About', '../?type=page&id=3', 0, 0);";
$db->query($insert_menu_items);

$create_table_settings_content = $db->query($table_settings_content);

if ($create_table_object && $create_table_settings) {

   echo "<p>SimplePress wurde erfolgreich installiert. Gehe <a href='./login.php'>zum Administrationsbereich</a> oder <a href='./'>deiner Startseite</a></p>";
   
$dateiname = './config';
$code = '
<?php
$dbhost = "' . $host . '";
$dbuser = "' . $user . '";
$dbpass = "' . $password . '";
$dbname = "' . $database . '";

$adminlogin = "' . $admin_login . '";
$adminpass = "' . $admin_pass . '";
';

$code .= "?";
$code .= ">";

$datei = fopen($dateiname.'.php', 'w');
fwrite($datei, $code);
fclose($datei);   
   
   // Installationsscript löschen
   
} else {

 echo "<p>Die Tabellen konnten nicht angelegt werden. <a href='./install.php'>erneut versuchen</a></p>";
 
}

} else {

?>

Willkommen zum Installationsassistenten. Gib deine Serverdaten ein und klicke auf weiter.

<form name="installer" action="<?php echo "$_SERVER[PHP_SELF]"; ?>" method="post">
<input type="text" name="host" placeholder="Host"><br /><br />
<input type="text" name="user" placeholder="Benutzer"><br /><br />
<input type="text" name="password" placeholder="Passwort"><br /><br />
<input type="text" name="database" placeholder="Datenbank"><br /><br /><br />
<input type="text" name="site_name" placeholder="Titel deiner Seite"><br /><br />
<input type="text" name="adminlogin" placeholder="Administrator Username"><br /><br />
<input type="text" name="adminpass" placeholder="Administrator Passwort"><br /><br />
<input type="submit" value="Installation starten">
</form>
