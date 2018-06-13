<?php

include "header.php";

if(isset($_GET['action'])) {
    include "item" . DS . $_GET['action'] . ".php";
} 

include "footer.php";

?>