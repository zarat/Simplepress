<?php

include "load.php";

$formpass=NULL;
$formlogin=NULL;

$system = new system();

require ("config.php"); 

$actual_theme = $system->settings('site_theme');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Login</title>

  <link href="../content/themes/<?php echo $system->settings('site_theme'); ?>/css/style.css" rel="stylesheet" type="text/css" />
  <link href="../content/themes/<?php echo $system->settings('site_theme'); ?>/css/menu.css" rel="stylesheet" type="text/css" />
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
$nav->config(array('id' => 1));
$nav->html();
?>


<div class="sp-main-body">
<div class='sp-content'>

<?php 
$system->logout(); 
?>

<script type="text/javascript"> 
setTimeout(window.location.replace("../login.php"), 1000);
</script>

<p>Du bist jetzt abgemeldet. Zum <a href='admin'>LOGIN</a></p>


</div>

<!-- Sidebar -->
<div class='sp-sidebar'>

<div class="sp-sidebar-item-box">
<div class="sp-sidebar-item-box-head">Hinweis</div>
<div class="sp-sidebar-item-box-body">Achte immer darauf, das dir keiner beim anmelden zusieht!</div>
</div>

</div>
<!-- Sidebar Ende -->

<div style="clear:both;"></div>
<div style='clear:both;'></div><!-- Der Footer -->
<div class="sp-footer" style="padding:10px;">&copy; 2017 SimplePress 0.1 | <a href='./rss.php'>RSS 2.0</a> | powered by <a href="https://github.com/zarat/simplepress" target="_blank">SimplePress</a></a></div>

<!-- Der Wrapper aus dem Header wird geschlossen -->
</div>

</div>

</div> 

</body>
</html>
