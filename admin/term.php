<?php

include "header.php";

if(isset($_GET['action'])) {
    include "term" . DS . $_GET['action'] . ".php";
} else {
    include "term" . DS . "index.php";
}

include "footer.php";

?>