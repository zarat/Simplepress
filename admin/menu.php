<?php

include "header.php";


if(isset($_GET['action'])) {
    include "menu" . DS . $_GET['action'] . ".php";
} else {
    include "menu" . DS . "index.php";
}

include "footer.php";

?>