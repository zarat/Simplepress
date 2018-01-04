<?php

class database extends core {

	private $database = null;
	private $db_type = null;

	function load() {
		if(is_file(ABSPATH . "config.php")) { include ABSPATH . "config.php"; } else { include ABSPATH . "install.php"; exit(); } 
	}

}

?>
