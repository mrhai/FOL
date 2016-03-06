<?php
try {

	include_once 'db.php';
	include_once 'authentication.php';
		include_once 'authentication.php';
	if(isset($_GET["type"])){
		$type = $_GET["type"];
		
		switch($type){
			case 1:
				$result = $conn->query("select id,catalog_name from sub_catalog where catalog_id = 1");
				break;
			case 2:
				$result = $conn->query("select id,catalog_name from sub_catalog where catalog_id = 2");
				break;
			case 3:
				$result = $conn->query("select id,catalog_name from catalog where id in (3,4)");
				break;
		}
		
		echo json_encode(mysqli_fetch_all($result,MYSQLI_ASSOC));
	}
	
	if(false){
	$result = $conn->query("select id,catalog_name from catalog");
	
	$result_array = array();
	
	while($row = $result->fetch_assoc()) {
		
		$result_sub = $conn->query("select id,catalog_name from sub_catalog where catalog_id = $row[id]");
		
		array_push($result_array,array(
			"id" => $row["id"],
			"catalog_name" => $row["catalog_name"],
			"sub" => mysqli_fetch_all($result_sub,MYSQLI_ASSOC)
		));
	}

	echo json_encode($result_array);
	}
	
}
catch(Exception $e)
{
    echo 0;
}
?>