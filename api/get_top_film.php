<?php

include_once 'db.php';

$result = $conn->query("select * from film order by view desc limit 10");

echo json_encode(mysqli_fetch_all($result,MYSQLI_ASSOC));

?>