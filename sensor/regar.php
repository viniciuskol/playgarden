<?php 
require '../bd.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (getWateringStatus()) {
	echo "15";
	changeWateringStatus(0);
} else {
	echo "0";
}
?>