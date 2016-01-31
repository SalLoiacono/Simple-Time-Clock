<?php

// Check if already clocked for specific request
$name = $_POST['name'];

// Read which button user pressed
$TimeIN = $_POST['TimeIN-btn'];
$TimeOUT = $_POST['TimeOUT-btn'];
$LunchIN = $_POST['LunchIN-btn'];
$LunchOUT = $_POST['LunchOUT-btn'];

// Append string value from button user pressed
$mode = $TimeIN . $TimeOUT . $LunchIN . $LunchOUT;
$time = date("Y-m-d H:i:s");
$date = date("Y-m-d");
$DayOfWeek = date("D");
$DayOfWeek = strtoupper($DayOfWeek);
$column = $DayOfWeek . $mode;

// Database Info
require_once ('dbconfig.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `" . $column . "` FROM TimeClock WHERE Date='" . $date . "' AND Name='" . $name . "' ORDER BY Date ASC LIMIT 1";
$result = $conn->query($sql);
do {
	$field = $row[$column];
} while ($row = $result->fetch_assoc());

// Write to timeclock
$Days = 7;
$sql = "UPDATE databasename.TimeClock SET `" . $column . "`='" . $time . "' WHERE Date > DATE_SUB( NOW(), INTERVAL " . $Days . " DAY) AND Name='" . $name . "'";
mysql_query($sql) or die(mysql_error());
?>

<html>
<title></title>
<head></head>
<body>
	<br /><br />
	<script>window.location.href = "index.php";</script>
</body>
</html>
