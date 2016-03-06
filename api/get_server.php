<?php
include_once 'db.php';
	include_once 'authentication.php';
if(isset($_GET["id"]) && isset($_GET["part"])){
	$filmId = $_GET["id"];
	$part = $_GET["part"];
	
	$server = $conn->query("select part,link from film_server where filmid = $filmId and part = $part");
	
	$server = mysqli_fetch_all($server,MYSQLI_ASSOC);
	
	echo json_encode($server[0]);
}

?>