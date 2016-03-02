<?php
try {

	include_once 'db.php';
	
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
catch(Exception $e)
{
    echo 0;
}
?>