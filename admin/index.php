<?php

//header("Content-Type: text/html; charset=utf-8");

include("../load.php");
include("auth.php");

$system = new system();
$settings = $system->settings();
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
<script type="text/javascript" src="./js/jquery.dataTables.js"></script>
<script type="text/javascript" src="./js/jquery.dataTables.min.js"></script>

<link href="./css/jquery.dataTables_themeroller.min.css" type="text/css" rel="stylesheet">
<link href="./css/jquery.dataTables_themeroller.css" type="text/css" rel="stylesheet">
<link href="./css/jquery.dataTables.css" type="text/css" rel="stylesheet">

<script type="text/javascript">
$(document).ready(function() {
    $('table#example').dataTable( {
        "paging":   true,
        "ordering": false,
        "info":     true
    } );
} );
</script>
<!-- Der selbst gehostete will auf Mobiles nicht angezeigt werden :S https://cdn.ckeditor.com/4.5.10/standard/ckeditor.js -->
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>

</head>

<body>

<div class="main-wrapper">


<div class="nav-container">

<div>

<label class="responsive_menu" for="responsive_menu">

<span>Menu</span>

</label>

<input id="responsive_menu" type="checkbox">

    <ul class="menu">
    
        <li><a href="./?page=config_pages">Einstellungen</a></li>
        
        <li><a href="#">Inhalte</a>
        
                <ul>
                
                    <li><a href="./?page=item_list&type=page">Seiten</a></li>
                    
                    <li><a href="./?page=item_list&type=post">Artikel</a></li>
                    
                    <li><a href="./?page=item_list&type=category">Kategorien</a></li>
                    
                    <li><a href="./?page=item_list&type=comment">Kommentare</a></li>
                
                </ul>
                
        </li>
                
        <li><a href="../">Zur Homepage</a></li>
	<li><a href='../logout.php'>Logout</a></li>
    
    </ul>

</div>

</div>

<div class="admin-content">

<?php

if(isset($_GET['page'])) {

    include $_GET['page'] . ".php";

} else {

include "config_pages.php";

}

?>


</div>

</div>
</body>
</html>
