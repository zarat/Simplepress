<?php

/**
 * @author Manuel Zarat
 */

if(!empty($_POST['host']) && !empty($_POST['user']) && !empty($_POST['password']) && !empty($_POST['database']) && !empty($_POST['adminemail']) && !empty($_POST['adminpass']) && !empty($_POST['admindisplayname'])) {

$dbhost = $_POST['host'];
$dbuser = $_POST['user'];
$dbpass = $_POST['password'];
$dbname = $_POST['database']; 

$adminemail = $_POST['adminemail'];
$adminpass = md5($_POST['adminpass']);
$admindisplayname = $_POST['admindisplayname']; 

$site_name = !empty($_POST['site_name']) ? $_POST['site_name'] : $_SERVER['SERVER_NAME'];

$conn = new mysqli($dbhost, $dbuser, $dbpass);

$query = "CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$conn->query($query) or die('Could not create database: ' . $dbname);

$query = "USE $dbname";
$conn->query($query) or die('Could not use database: ' . $dbname);

$filename = './import.sql';
$op_data = '';
$lines = file($filename);

foreach ($lines as $line) {
    if (substr($line, 0, 2) == '--' || $line == '') {
        continue;
    }
    $op_data .= $line;
    if (substr(trim($line), -1, 1) == ';') {
        $conn->query($op_data) or die('could not run insert script:op_data: ' . $op_data);
        $op_data = '';
    }
}

echo "<h1>Gratulation</h1><p>SimplePress wurde erfolgreich installiert. Gehe <a href='./login.php'>zum Administrationsbereich</a> oder <a href='./'>deiner Startseite</a></p>";
   
$configfile = './config.php';
$config = '
<?php
$dbhost = "' . $dbhost . '";
$dbuser = "' . $dbuser . '";
$dbpass = "' . $dbpass . '";
$dbname = "' . $dbname . '";
';

$config .= "?";
$config .= ">";

$configfile_tmp = fopen($configfile, 'w') or die('could not write config file: ' . $configfile);
fwrite($configfile_tmp, $config);
fclose($configfile_tmp); 

$query = $conn->prepare("INSERT INTO user (email, password, displayname) VALUES (?, ?, ?)");
$query->bind_param("sss", $adminemail, $adminpass, $admindisplayname);
if (!$stmt->execute()) {
    echo 'Could not create admin user, please reinstall!';
    return false;
}
    
} else {
    //No UserData given exeption
?>

<h1>Simplepress Installation</h1>
<p>Die ben&ouml;tigte Tabellen- und Feldstruktur ist bereits in der Datei "install.sql" vordefiniert. Diese Tabellenstruktur wird jetzt in deine Datenbank importiert.
Sollte die Datenbank bereits bestehen, wird</p>
<p>Gib deine Serverdaten ein und klicke danach auf weiter.</p>

<form name="installer" action="<?php echo "$_SERVER[PHP_SELF]"; ?>" method="post">
    <input type="text" name="host" placeholder="Host"><br /><br />
    <input type="text" name="user" placeholder="Benutzer"><br /><br />
    <input type="text" name="password" placeholder="Passwort"><br /><br />
    <input type="text" name="database" placeholder="Datenbank"><br /><br /><br />
    <input type="text" name="site_name" placeholder="Titel deiner Seite"><br /><br />
    
    <input type="text" name="adminemail" placeholder="Administrator Email"><br /><br />
    <input type="text" name="adminpass" placeholder="Administrator Passwort"><br /><br />
    <input type="text" name="admindisplayname" placeholder="Dein Anzeigename"><br /><br />
    <input type="submit" value="Installation starten">
</form>

<?php } ?>
