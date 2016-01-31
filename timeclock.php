<?php

//  Back button in case user needs to go back to main login screen
echo "<a class='btn btn-primary' href='index.php' style='height:60px; width:100px; font-size:22pt; margin-bottom:-150px; margin-top:5px; margin-left:5px;'>Back</a>";

// Receive Employee ID # from previous page
$id = $_POST['EmployeeIDinput'];

// Use Employee ID from previous page to find name of Employee in database

// Database Info
require_once ('dbconfig.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM Employees WHERE EmployeeID=" . $id . " LIMIT 1";
$result = $conn->query($sql);

do {
	$employee = $row["Name"];
} while ($row = $result->fetch_assoc());

echo "<h1 align='center' style='font-size:60pt;'>" . $employee . "</h1>";
$name = $employee;
if (($name == NULL) || ($name == "")) {
	header('Location: index.php');
}

// Get current time & date
$time = date("Y-m-d H:i:s");
$date = date("Y-m-d");

// Get day of week, to prepend to variable for searching in database
$DayOfWeek = date("D");
$DayOfWeek = strtoupper($DayOfWeek);

// Select records from last 7 days
$Days = 7;
$sql = "SELECT * FROM TimeClock WHERE Date > DATE_SUB( NOW(), INTERVAL " . $Days . " DAY) AND Name='" . $name . "' ORDER BY Name ASC LIMIT 1";
$result = $conn->query($sql);
$x = 0;

do {
	$x++;
	$DayIN = $row[$DayOfWeek . "-IN"];
	$LunchOUT = $row[$DayOfWeek . "-L-OUT"];
	$LunchIN = $row[$DayOfWeek . "-L-IN"];
	$DayOUT = $row[$DayOfWeek . "-OUT"];
	$NameOnRecord = $row[$Name];
} while ($row = $result->fetch_assoc());

// If the employee does not have a record for the current week, create a blank record for employee.
if (($x == 1) && (!$NameOnRecord)) {
	$sql = "INSERT INTO datbasename.TimeClock (Date,Name) VALUES ('" . $time . "','" . $name . "')";
	mysql_query($sql) or die(mysql_error());
	echo "<script>location.reload(true);</script>";
}
$conn->close();
?>

<html>
<title>Employee Time Clock</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<link rel="stylesheet" href="../css/bootstrap.min.css">
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<head></head>
<body onload="startTime()">
	<div class="container-fluid">
		<form class="form-horizontal"  action="submit-time.php" method="post">
			<fieldset>
			
			<div align="center">
	   		<div align="center">
			
				<!-- Check if employee has already recorded any times for today, if so, don't show button. -->
				<?php if ($DayIN == "0000-00-00 00:00:00") {
					echo "<button id='TimeIN-btn' style='width:200px; height:200px; margin:20px; font-size:30pt;' name='TimeIN-btn' class='btn btn-success' value='-IN'>Time<br />IN</button>";
				} ?>
				<?php if ($LunchOUT == "0000-00-00 00:00:00") {
					echo "<button id='LunchOUT-btn' style='width:200px; height:200px; margin:20px; font-size:30pt;' name='LunchOUT-btn' class='btn btn-danger' value='-L-OUT'>Lunch<br />OUT</button>";
				} ?>
				<?php if ($LunchIN == "0000-00-00 00:00:00") {
					echo "<button id='LunchIN-btn' style='width:200px; height:200px; margin:20px; font-size:30pt;' name='LunchIN-btn' class='btn btn-success' value='-L-IN'>Lunch<br />IN</button>";
				} ?>
				<?php if ($DayOUT == "0000-00-00 00:00:00") {
					echo "<button id='TimeOUT-btn' style='width:200px; height:200px; margin:20px; font-size:30pt;' name='TimeOUT-btn' class='btn btn-danger' value='-OUT'>Time<br />OUT</button>";
				} ?>
				<?php if (((($DayIN != "0000-00-00 00:00:00") && ($LunchOUT != "0000-00-00 00:00:00") && ($LunchIN != "0000-00-00 00:00:00") && ($DayOUT != "0000-00-00 00:00:00")))) {
					echo "<h1 align='center' style='font-size:30pt;'>Your Time Card is full for today.<br />Enjoy the rest of your day!</h1>";
				} ?>
			
	  			</div>
			</div>
			
			<?php
			// Pass name variable to be written to database on next page
			echo "<input type='hidden' name='name' value='" . $name . "'>";
			?>
			
			</fieldset>
		</form>
	</div>
	
	<div id="txt" align="center" style="font-size:50pt;"></div>
	
	<script> // Show current time
		function startTime() {
    			var today = new Date();
    			var h = today.getHours();
    			var m = today.getMinutes();
    			var s = today.getSeconds();
    			m = checkTime(m);
    			s = checkTime(s);
    			document.getElementById('txt').innerHTML =
    			h + ":" + m + ":" + s;
    			var t = setTimeout(startTime, 500);
		}
		function checkTime(i) {
    			if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    			return i;
		}
	</script>
	
	<?php
// Add flair to employee's punch-in screen.
if ($name == "Employee Name") {
	echo "<style> h1 { color:blue; } </style>";
	echo "<img style='margin-top:-120px;' height='30%' src='../images/animated.gif' align='left' />";
	echo "<img style='margin-top:-120px; margin-right:-230px; float:right;' height='30%' src='../images/animated.gif' align='left' />";
}
?>
	
</body>

</html>
