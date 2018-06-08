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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//DE">
<html>
<head>
	<title>Login</title>
    <style>
    body {
      background: #2d343d;
    }
    
    .login {
      margin: 20px auto;
      width: 300px;
      padding: 30px 25px;
      background: white;
      border: 1px solid #c4c4c4;
    }
    
    h1.login-title {
      margin: -28px -25px 25px;
      padding: 15px 25px;
      line-height: 30px;
      font-size: 25px;
      font-weight: 300;
      color: #ADADAD;
      text-align:center;
      background: #f7f7f7;
     
    }
    
    .login-input {
      width: 285px;
      height: 50px;
      margin-bottom: 25px;
      padding-left:10px;
      font-size: 15px;
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .login-input:focus {
        border-color:#6e8095;
        outline: none;
      }
    .login-button {
      width: 100%;
      height: 50px;
      padding: 0;
      font-size: 20px;
      color: #fff;
      text-align: center;
      background: #f0776c;
      border: 0;
      border-radius: 5px;
      cursor: pointer; 
      outline:0;
    }
    
    .login-lost
    {
      text-align:center;
      margin-bottom:0px;
    }
    
    .login-lost a
    {
      color:#666;
      text-decoration:none;
      font-size:13px;
    }
    </style>
</head>
<body>
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
            window.location = \"../admin\";
        }
        setCookie('sp-uid','$token',1); 
        </script>";
        
    } else {
    
        echo "<script>window.location = window.location</script>";
        
    }

} else { 

$form ='<form method="post" class="login" action="login.php" name="frm">'."\n";
$form.='    <h1 class="login-title">Simplepress Login</h1>'."\n";
$form.='    <input type="text" class="login-input" name="formlogin" placeholder="Email Adress" autofocus>'."\n";
$form.='    <input type="password" class="login-input" name="formpass" placeholder="Password">'."\n";
$form.='    <input type="submit" value="Lets Go" class="login-button">'."\n";
$form.='  <p class="login-lost"><a href="../">Zur Homepage</a></p>'."\n";
$form.='</form>';
echo $form;

} 

?>
</body>
</html>
