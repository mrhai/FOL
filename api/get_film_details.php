<?php
include_once 'db.php';

if(isset($_GET["id"])){
	$filmId = $_GET["id"];
	
	$film_detail = $conn->query("select * from film where id = $filmId");
	
	$film_detail = mysqli_fetch_all($film_detail,MYSQLI_ASSOC);
	
	echo json_encode($film_detail[0]);
}

?>