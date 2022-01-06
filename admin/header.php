<?php

/**
 * @author Manuel Zarat
 */

include("../load.php");

$system = new system();

if( !$system->auth() ) { header("Location: ../login.php"); }

?> 
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> -->
<!-- TinyMCE requires standard mode -->
<!DOCTYPE HTML>
<html>
<head>
<title>Adminpanel</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />

<link rel="stylesheet" type="text/css" href="./css/style.css"/>
<link rel="stylesheet" type="text/css" href="./css/menu.css"/>

<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/admin.js"></script>

<style>
.container1 input[type=text] {
padding:5px 0px;
margin:5px 5px 5px 0px;
}
.add_form_field1 {
    background-color: #1c97f3;
    border: none;
    color: white;
    padding: 8px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
	  border:1px solid #186dad;
}
.add_form_field {
    cursor:pointer;
}
input{
    border: 1px solid #1c97f3;
    height: 40px;
	margin-bottom:14px;
}
.delete{
    background-color: #fd1200;
    border: none;
    color: white;
    padding: 5px 5px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
}
</style>

<script type="text/javascript">
    function toggle(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
</script>

</head>

<body>

<div class="sp-main-wrapper">

    <div class="nav-container">
    <div>
    <label class="responsive_menu" for="responsive_menu">
        <span>Menu</span>
    </label>
    <input id="responsive_menu" type="checkbox">
        <ul class="menu">    
            <li><a href="./">Dashboard</a>
                <ul>
			     <li><a href="./theme.php">Theme</a></li>
			        <li><a href="./menu.php">Menu</a></li>
                </ul>
            </li> 
            <li><a href="#">Inhalte</a>
                <ul>
                    <li><a href="./taxonomy.php">Taxonomien</a></li>
                    <li><a href="./term.php">Terme</a></li>
                    <li><a href="./item.php">Items</a></li>
                
                </ul>
            </li>
            <li><a href="../">Zur Homepage</a>
                <ul>
                    <li><a href='../logout.php'>Logout</a></li>
                </ul>
            </li>        
        </ul>
    </div>
    </div>
