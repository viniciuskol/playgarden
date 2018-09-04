<?php
require '../bd.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_GET["sensor1"]) && isset($_GET["sensor2"])) {
	$blocked = "false";
	if (isset($_GET["blocked"])){
		$blocked = "true";
	}
	echo saveSensorStatus($_GET["sensor1"],$_GET["sensor2"],$blocked);
} else {
	echo "error";
}

?>
