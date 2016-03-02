<?php
try {
	$sql = "select id, catalog_name from sub_catalog";
	include_once 'db.php';
	
		$result = $conn->query($sql);
		
		$data = mysqli_fetch_all($result,MYSQLI_ASSOC);
		
		echo json_encode($data);
	
}
catch(Exception $e)
{
    echo 0;
}
?>