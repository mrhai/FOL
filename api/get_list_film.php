<?php
$pagging = 20;
include_once 'db.php';
	include_once 'authentication.php';
$page_max = 20;

if(isset($_GET["type"]) && isset($_GET["page"])){
	$type = $_GET["type"];
	$page = $_GET["page"];
	$id = $_GET["id"];
	$start = ($page - 1)*$page_max;
	
	switch($type){
		
		case " ":
			$result = $conn->query("select * from film order by id desc limit $start,$pagging");
			$result_page = $conn->query("select count(*) as count from film");
			break;
		case "TL":
			$result = $conn->query("select * from film where type = $id order by id desc limit $start,$pagging");
			$result_page = $conn->query("select count(*) as count from film where type = $id");
			break;
		case "QG":
			$result = $conn->query("select * from film where country = $id order by id desc limit $start,$pagging");
			$result_page = $conn->query("select count(*) as count from film where country = $id");
			break;
		case "ONLY":
			$result = $conn->query("select * from film where catalog = $id order by id desc limit $start,$pagging");
			$result_page = $conn->query("select count(*) as count from film where catalog = $id");
			break;
	}
	$page = $result_page->fetch_assoc();
	$data = mysqli_fetch_all($result,MYSQLI_ASSOC);
	$page = floor($page["count"]/$page_max) + 1;
	
	$result = array(
		"page" => $page,
		"film" => $data
		
	);
			echo json_encode($result);
}
?>