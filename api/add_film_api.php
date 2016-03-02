<?php
try {
	$sql_select_maxid = "select IFNULL(max(id),0) as id from film";
	$sql_insert_film = "INSERT INTO film VALUES (%id, %name, %description,%image,%view,%times,%type,%country,%catalog,'%link')";
	$sql_insert_film_server = "INSERT INTO film_server(filmid,part,link) VALUES ('%filmid','%part','%link')";
	$cons_server_hr = " \n";
	include_once 'db.php';
	include_once 'get_link_picasa.php';
	header('Content-Type: text/html; charset="UTF-8"');
	
	if(isset($_GET["data"])){
		
		$data = json_decode($_GET["data"],false);

		$type = $data -> insert;
		
		$server = array();
		$film_id = 0;
		$film_view = 0;
		
		
		$tok = strtok($data -> server, $cons_server_hr);

		while ($tok !== false) {
			array_push($server,$tok);
			$tok = strtok($cons_server_hr);
		}
		
		if($type == 1){//insert
			$result = $conn->query($sql_select_maxid);
			
			while($row = $result->fetch_assoc()) {
				$film_id = $row["id"] + 1;
			}

			$link_film = "$film_id-".str_replace(" ","-",convert_vi_to_en($data -> name)).".html";
			$sql_insert_film = str_replace("%link",$link_film,str_replace("%catalog",$data -> catalog,str_replace("%country",$data -> country,str_replace("%type",$data -> type,str_replace("%times","'".$data -> times."'",str_replace("%view","'".$film_view."'",
			str_replace("%image","'".$data -> image."'",str_replace("%description","'".$data -> description."'",
			str_replace("%name","'".$data -> name."'",str_replace("%id","'".$film_id."'",$sql_insert_film))))))))));

			$result = $conn->query($sql_insert_film);
			
			if($result == 1){
				foreach ($server as $key => $value){
							
						if($data -> serverType == 1){//picasa
							$server = getPicasaGoogle($value);
							$link = $value;
							
							foreach ($server as $keys => $value){
								$link = $value;
							}
							
						}else{
							$link = $value;
						}
						
						$sql_insert_server = str_replace("%link",$link,str_replace("%part",$key + 1,str_replace("%filmid",$film_id,$sql_insert_film_server)));
						$conn->query($sql_insert_server);
				}
			}
			
			echo 1;
		}

	}
	

}
catch(Exception $e)
{
    echo 0;
}

	function convert_vi_to_en($str) {
	  $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	  $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
	  $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
	  $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
	  $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
	  $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
	  $str = preg_replace("/(đ)/", 'd', $str);
	  $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
	  $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
	  $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
	  $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
	  $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	  $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
	  $str = preg_replace("/(Đ)/", 'D', $str);
	  //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
	  return $str;
	}
?>