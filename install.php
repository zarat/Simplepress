<?php

/**
 * Installer
 * 
 * Ein SQL Dump muss im ROOT Verzeichnis vorhanden sein.
 *
 * @author Manuel Zarat
 *
 */

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

$conn = new mysqli($host, $user, $password);

$init_1 = "CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$conn->query($init_1) or die($db->error);
$init_2 = "USE $database";
$conn->query($init_2) or die($db->error);

$filename = './import.sql';
$op_data = '';
$lines = file($filename);

foreach ($lines as $line) {
    if (substr($line, 0, 2) == '--' || $line == '') {
        continue;
    }
    $op_data .= $line;
    if (substr(trim($line), -1, 1) == ';') {
        $conn->query($op_data);
        $op_data = '';
    }
}

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

<?php } ?>
