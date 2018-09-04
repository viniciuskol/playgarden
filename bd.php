<?php
$servername = "localhost";
$username = "username";
$password = 'password';
$dbname = "dbname";

$maxpoints = [2500,3000,5000,7000];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
error_reporting(E_ERROR | E_PARSE);
ini_set('error_reporting', E_ALL);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if (isset($_GET["questions"])){
	echo getQuestions($_GET["categories"]);
}

if (isset($_GET["ranking"])){
	echo getRanking();
}

if (isset($_GET["weekranking"])){
	echo getWeekRanking();
}
	
if (isset($_POST["saveranking"])){
	saveRanking();
}
if (isset($_GET["sensorstatus"])){
	echo getSensorStatus();
}
if (isset($_POST["savesensorstatus"])){
	echo saveSensorStatus($_POST["sensor1"],$_POST["sensor2"],$_POST["blocked"]);
}
if (isset($_GET["wateringstatus"])){
	echo getWateringStatus();
}

if (isset($_POST["savewatering"])){
	echo saveWatering();
}
if (isset($_POST["savewateringstatus"])){
	echo changeWateringStatus($_POST["status"]);
}
if (isset($_GET["getpoints"])){
	echo getPoints();
}

if (isset($_GET["getlastwateringstatus"])){
	echo getLastWateringStatus();
}

if (isset($_GET["geterrorstatus"])){
	echo getErrorStatus();
}

function changeErrorStatus($errorstatus){
    global $conn;
	$sql = "DELETE FROM `sensor_error` WHERE `id`=1; INSERT INTO `sensor_error` (`error`) VALUES(".$errorstatus.")";
	$response = "error";
	if (mysqli_multi_query($conn, $sql)) {
		$response = "ok";
	}
    return '';
}


function getErrorStatus(){
    global $conn;
	$sql = "SELECT `error` FROM `sensor_status` ORDER BY `id` LIMIT 1";
	$status = false;
	if ($result = mysqli_query($conn, $sql)) {
		while($row = $result->fetch_object()) {
			foreach($row as $key => $col){
				if ($key=="error") {
					if (intval($col) == 1){
						$status = true;
					}
				}
			}
		}
    }
	return $status;
}


function getSensorStatus(){
	global $conn;
	$sql = "SELECT * FROM `sensor_status` ORDER BY `id` LIMIT 1";
	$json = '[{"sensor1":"1","sensor2":"0"}]';
	$row_array;
	if ($result = mysqli_query($conn, $sql)) {
    while($row = $result->fetch_object()) {
        foreach($row as $key => $col){
           	$col_array[$key] = utf8_encode($col);
        }
        $row_array[] =  $col_array;
    }
		if(isset($row_array))
			$json = json_encode($row_array);
	}
	return $json;
}

function saveSensorStatus($sensor1, $sensor2, $blocked){
	global $conn;
	$sql = "DELETE FROM `sensor_status` WHERE `id`=1; INSERT INTO `sensor_status` (`sensor1`, `sensor2`, `blocked`) VALUES(".$sensor1.", ".$sensor2.", ".$blocked.")";
	$response = "error";
	if (mysqli_multi_query($conn, $sql)) {
		$response = "ok";
	}
	return $response;
}

function getLastWateringStatus(){
    global $conn;
	$sql = "SELECT `date` FROM watering_status LIMIT 1";
	$status = '';
	if ($result = mysqli_query($conn, $sql)) {
		while($row = $result->fetch_object()) {
			foreach($row as $key => $col){
				if ($key=="date") {
                    $status = $col;
				}
			}
		}
    }
	return $status;
}

function getWateringStatus(){
	global $conn;
	$sql = "SELECT `status` FROM watering_status LIMIT 1";
	$status = false;
	if ($result = mysqli_query($conn, $sql)) {
		while($row = $result->fetch_object()) {
			foreach($row as $key => $col){
				if ($key=="status") {
					if (intval($col) == 1){
						$status = true;
					}
				}
			}
		}
        $sql = "UPDATE watering_status SET `date` = CURRENT_TIMESTAMP WHERE id=1";    
        mysqli_query($conn, $sql);
    }
	return $status;
}

function saveWatering(){
	global $conn;
	$sql = "INSERT INTO watering (`timestamp`) VALUES (CURRENT_TIMESTAMP)";
	if (mysqli_query($conn, $sql)) {
		changeWateringStatus(1);
	}
}

function changeWateringStatus($status){
	global $conn;
	$sql = "SELECT * FROM watering_status";
	if ($result = mysqli_query($conn, $sql)) {
		if (mysqli_num_rows($result)<=0){
			$sql = "INSERT INTO watering_status (`status`) VALUE (".$status.")";
		} else {
			$sql = "UPDATE watering_status SET `status` = ".$status." WHERE id=1";
		}
	}
	mysqli_query($conn, $sql);
}

function saveRanking(){
	global $conn;
	date_default_timezone_set('America/Sao_Paulo');
	$str_date = mktime($_POST['startedat']['hours'], $_POST['startedat']['minutes'], $_POST['startedat']['seconds'], $_POST['startedat']['month'], $_POST['startedat']['day'], $_POST['startedat']['year']);
	
	$sql = "SELECT * FROM ranking WHERE `userid` = " . $_POST['userid'];
	if ($result = mysqli_query($conn, $sql)) {
		if (mysqli_num_rows($result)<=0){
			$sql = "INSERT INTO ranking (`userid`, `name`, `points`, `startedat`, `duration`, `questions`, `categories`, `corrects`, `consent`) VALUES (" . $_POST['userid'] . ",'" . utf8_encode($_POST['name']) . "'," . $_POST['points'] . ",'" . date('Y-m-d H:i:s',$str_date) . "'," . $_POST['duration'] . ",'" . $_POST['questions'] . "','" . $_POST['categories'] . "'," . $_POST['corrects'] .",". $_POST['consent'] .")";
		} else {
			$sql = "UPDATE ranking SET `name`='" . utf8_encode($_POST['name']) . "', `points`=" . $_POST['points'] . ", `startedat`='" . date('Y-m-d H:i:s',$str_date) . "', `duration`=" . $_POST['duration'] . ", `questions`='" . $_POST['questions'] . "', `categories`='" . $_POST['categories'] . "', `corrects`=" . $_POST['corrects'] .", `consent`=". $_POST['consent'] ." WHERE `userid`=" . $_POST['userid'];
		}
	}
	
	if (mysqli_query($conn, $sql)) {
		echo "OK";
	}
}

function getQuestions($categories){
	global $conn;
	$strcategories = "";
	if (isset($categories)){
		$categories = json_decode($categories);
		for ($i = 0; $i < count($categories); $i++) {
			$strcategories .= "`cat_id` = ".$categories[$i];
			if ($i < count($categories)-1) {
				$strcategories .= " OR ";
			}
		} 
	}
	$sql = "SELECT DISTINCT * FROM `quiz` WHERE ".$strcategories." ORDER BY RAND() LIMIT 0,50";
	$json = "[]";
	$row_array;
	if ($result = mysqli_query($conn, $sql)) {
    while($row = $result->fetch_object()) {
        foreach($row as $key => $col){
           	$col_array[$key] = utf8_encode(str_replace( "'", "&quot;",str_replace( '"', "&quot;",$col)));
        }
        $row_array[] =  $col_array;
    }
	if(isset($row_array))
		$json = json_encode($row_array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	return $json;
}

function getPoints(){
	global $conn;
	global $maxpoints;
	$sql = "SELECT SUM(points)as total FROM `ranking` WHERE DATE(`startedat`) = CURDATE()";
	$temp = array("total" => $maxpoints[0], "total" => 0);
	$json =  json_encode($temp, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	if ($result = mysqli_query($conn, $sql)) {
		$col_array["total"] = $maxpoints[0];
		$row_array[] = $col_array;
		while($row = $result->fetch_object()) {
			if (intval($row->total)) {
				$col_array["total"] = $row->total % $maxpoints[0];
				$row_array[] = $col_array;
			} else {
				$col_array["total"] = 0;
				$row_array[] = $col_array;
			}
		}
		if(isset($row_array))
			$json = json_encode($row_array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	return $json;
}

function getRanking(){
	global $conn;
	global $maxpoints;
	$sql = "SELECT * FROM `ranking` WHERE NAME != '' AND DATE(`startedat`) = CURDATE() ORDER BY `points` DESC LIMIT 0,10";
	$json = "[]";
	$row_array;
	if ($result = mysqli_query($conn, $sql)) {
		while($row = $result->fetch_object()) {
			foreach($row as $key => $col){
				$col_array[$key] = utf8_encode($col);
				if ($col == "") {
					$col = 0;
				} else {
					$col = $col;
				}
					$col_array[$key] = utf8_encode($col);
			}
			$row_array[] =  $col_array;
		}
		if(isset($row_array))
			$json = json_encode($row_array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	return $json;	
}


function getRanking2(){
	global $conn;
	global $maxpoints;
	$sql = "SELECT * FROM `ranking` WHERE NAME != ''";
	$json = "[]";
	$row_array;
	if ($result = mysqli_query($conn, $sql)) {
		while($row = $result->fetch_object()) {
			foreach($row as $key => $col){
				$col_array[$key] = utf8_encode($col);
				if ($key == "name") {
					$col_array[$key] = utf8_decode($col);
				}
			}
			$row_array[] =  $col_array;
		}
		if(isset($row_array))
			$json = json_encode($row_array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	return $json;	
}

function getWeekRanking(){
	global $conn;
	global $maxpoints;
	$sql = "SELECT MAX(points)as total FROM `ranking` WHERE DATE(`startedat`) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
	$json = "[]";
	$row_array;
	if ($result = mysqli_query($conn, $sql)) {
		while($row = $result->fetch_object()) {
			foreach($row as $key => $col){
				$col_array[$key] = utf8_encode($col);
				if ($col == "") {
					$col = 0;
				} else {
					$col = intval($col);
				}
					$col_array[$key] = utf8_encode($col);
			}
			$row_array[] =  $col_array;
		}
		if(isset($row_array))
			$json = json_encode($row_array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	return $json;		
}
?>
