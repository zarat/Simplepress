<?php

/**
 * Login script triple-alpha
 *
 * @author Manuel Zarat
 *
 */

include "load.php";

require ("config.php"); 

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Login</title>
  <link href="../content/themes/simplepress/css/menu.css" rel="stylesheet" type="text/css" />
  <link href="../content/themes/simplepress/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="sp-main-wrapper">

<!-- HTML Header -->
    <div class="sp-main-header"> 

        <div class="sp-main-header-logo">

            <h1>SimplePress</h1>
            <h4>Einfaches, kostenloses Blog CMS</h4>
    
        </div>
    
    </div>
<!-- HTML Header Ende -->

<?php
$nav = new menu();
$nav->config(array("id" => 1));
$nav->html();
?>


<div class="sp-main-body">
<div class='sp-content'>

<!-- Hier kommt der Login -->

<?php

$system = new system();
 
if( isset( $_POST['formlogin'] ) ) { 

    $formlogin = $_POST['formlogin'];
    $formpass = md5($_POST['formpass']);      

    if( $user = $system->login($formlogin,$formpass) ) {
    
    $token = md5( $user . time() );
    $cfg = array( "table" => "user", "set" => "token='$token' where id=" . $user );
    $system->update( $cfg );    
        /**
         * Cookie clientseitig setzen wg Zeitzonen Offset
         */
        echo "
        <script>
        function setCookie(name, value, hours) {
            var d = new Date();
            d.setTime(d.getTime() + (hours*60*60*1000));
            var expires = \"expires=\" + d.toUTCString();
            document.cookie = name + \"=\" + value + \";\" + expires + \";path=/\";
        }
        setCookie('sp-uid','$token',1); 
        setTimeout( function() { window.location = window.location; }, 1000);
        </script>";
        
    } else {
    
        echo "Anmeldeversuch gescheitert.";
        
    }

} else {

if( $user = $system->auth() ) {
    echo "<h3>Hallo $user[displayname]</h3>\n<p>Du bist jetzt angemeldet - <a href='../admin'>zum Dashboard</a></p>";
} else {
    $form ='<form action="login.php" method="post" name="frm">'."\n";
    $form.='<center>'."\n";
    $form.='<table cellspacing="4" cellpadding="4" style="">'."\n";
    $form.='	<tr>'."\n";
    $form.='		<td>Email</td>'."\n";
    $form.='		<td><input type="text" name="formlogin" class="cssborder" autofocus></td>'."\n";
    $form.='		<td>Passwort</td>'."\n";
    $form.='		<td><input type="password" name="formpass" class="cssborder"></td>'."\n";
    $form.='    <td><input type="submit" value="login" class="cssborder"></td>'."\n";
    $form.='	</tr>'."\n";
    $form.='</table>'."\n";
    $form.='</center> 	 '."\n";
    $form.='</form>';
    echo $form;
}

}

?>

</div>

<div class='sp-sidebar'>

    <div class="sp-sidebar-item-box">
        <div class="sp-sidebar-item-box-head">Hinweis</div>
        <div class="sp-sidebar-item-box-body">Achte immer darauf, das dir keiner beim anmelden zusieht!</div>
    </div>

</div>

<div style='clear:both;'></div><!-- Der Footer -->

<div class="sp-footer" style="padding:10px;"></div>

</div>

</div> 

</body>
</html>
