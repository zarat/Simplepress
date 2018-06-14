<?php

include "header.php";

if(isset($_GET['action'])) {
    include "taxonomy" . DS . $_GET['action'] . ".php";
} else {
    include "taxonomy" . DS . "index.php";
}

include "footer.php";

?>