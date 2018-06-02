<?php

/**
 * @author Manuel Zarat
 */

include("../load.php");
include("auth.php");

$system = new system();

$page = NULL;
if(isset($_GET['page'])){
	$page = $_GET['page'];
}
?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Adminpanel</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />

<link href="../content/themes/simplepress/css/menu.css" rel="stylesheet" type="text/css" />
<link href="../content/themes/simplepress/css/style.css" rel="stylesheet" type="text/css" />

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
            <li><a href="./?page=dashboard">Dashboard</a>
                <ul>
					<li><a href="./?page=edit_file">Theme Editor</a></li>
                </ul>
            </li>
            <li><a href="./?page=menu_edit&menu_id=1">Navigation</a></li>        
            <li><a href="#">Inhalte</a>
                <ul>
                    <li><a href="./?page=item_list&type=page">Seiten</a></li>
                    <li><a href="./?page=item_list&type=post">Artikel</a></li>
                    <li><a href="./?page=item_list&type=category">Kategorien</a></li>
                
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

<?php

if(isset($_GET['page'])) {
    include $_GET['page'] . ".php";
} else {
    include "dashboard.php";
}

?>
<div style="clear:both;"></div>
</div>
</body>
</html>
