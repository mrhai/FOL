<?php
include_once 'db.php';

if(isset($_GET["id"]) && isset($_GET["part"])){
	$filmId = $_GET["id"];
	$part = $_GET["part"];
	
	$server = $conn->query("select part,link from film_server where filmid = $filmId");
	
	$server = mysqli_fetch_all($server,MYSQLI_ASSOC);
	
	echo json_encode($server[0]);
}

?>