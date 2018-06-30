<?php

include "header.php";

if(isset($_GET['action'])) {
    include "item" . DS . $_GET['action'] . ".php";
} else {
    include "item" . DS . "index.php";
}

include "footer.php";

?>
