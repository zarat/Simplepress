<?php

session_start();

if ($_SESSION["loggedin"] != "1"){
	header("Location: ../login.php"); /* Redirect browser */  
	exit; 
}

?>