<?php
include_once 'db.php';

if(isset($_GET["id"])){
	$filmId = $_GET["id"];
	
	$server_count = $conn->query("select part from film_server where filmid = $filmId");
	
	$server_count = mysqli_fetch_all($server_count,MYSQLI_ASSOC);
	
	echo json_encode($server_count);
}

?>