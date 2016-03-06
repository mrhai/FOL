<?php

	include_once 'db.php';
		include_once 'authentication.php';
	if(isset($_GET["id"])){
		$id = $_GET["id"];
		
		$result = $conn->query("update film set `view` = `view`+1 WHERE id = $id");
	}
?>