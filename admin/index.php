<?php

include "header.php";


if(isset($_GET['page'])) {
    include $_GET['page'] . ".php";
} else {
    include "dashboard.php";
}

include "footer.php";

?>
