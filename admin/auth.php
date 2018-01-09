<?php
session_start();

//$loggedin = $_SESSION["loggedin"];
if ($_SESSION["loggedin"] != "1"){
	header("Location: ../login.php"); /* Redirect browser */ 
	/* Make sure that code below does not get executed when we redirect. */ 
	exit; 
}
//echo "ok";
?>
