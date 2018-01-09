<?php
session_start();

include "load.php";

$formpass=NULL;
$formlogin=NULL;
?>
<?php require ("config.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Login</title>

  <link href="../content/themes/default/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="main-wrapper">

<!-- HTML Header -->
    <div class="main-header"> 

        <div class="main-header-logo">

            <h1>SimplePress</h1>
            <h4>Einfaches, kostenloses Blog CMS</h4>
    
        </div>
    
    </div>
<!-- HTML Header Ende -->

<?php
$nav = new menu();
$nav->config(array('id' => 1));
$nav->html();
?>


<div class="main-body">
<div class='content'>

<!-- Hier kommt der Login -->

<?php
session_destroy();
?>
<script language="javascript">
<!-- 
location.replace("admin");
-->
</script>
	<h4><a href='admin'>Du bist jetzt abgemeldet</a></h4>


</div>

<!-- Sidebar -->
<div class='sidebar'>

<div class="sidebar-item-box">
<div class="sidebar-item-box-head">Hinweis</div>
<div class="sidebar-item-box-body">Achte immer darauf, das dir keiner beim anmelden zusieht!</div>
</div>

</div>
<!-- Sidebar Ende -->

<div style="clear:both;"></div>
<div style='clear:both;'></div><!-- Der Footer -->
<div class="footer" style="padding:10px;">&copy; 2017 SimplePress 0.1 | <a href='./rss.php'>RSS 2.0</a> | powered by <a href="https://github.com/zarat/simplepress" target="_blank">SimplePress</a></a></div>

<!-- Der Wrapper aus dem Header wird geschlossen -->
</div>

</div>

</div> 

</body>
</html>
