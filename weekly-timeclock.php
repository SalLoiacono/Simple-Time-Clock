<script> window.print(); </script>
<?php
// Database Info
require_once ('dbconfig.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$Days = 7;
$sql = "SELECT * FROM TimeClock WHERE Date > DATE_SUB( NOW(), INTERVAL " . $Days . " DAY) ORDER BY Name ASC";
$result = $conn->query($sql);
$i = 0;
$warning = 0;
if ($result->num_rows > 0) {
	// output data of each row
	//	do {
	while ($row = $result->fetch_assoc()) {
		$name = $row["Name"];
		$date = $row["Date"];
		$MON_IN = $row["MON-IN"];
		$MON_L_OUT = $row["MON-L-OUT"];
		$MON_L_IN = $row["MON-L-IN"];
		$MON_OUT = $row["MON-OUT"];
		$TUE_IN = $row["TUE-IN"];
		$TUE_L_OUT = $row["TUE-L-OUT"];
		$TUE_L_IN = $row["TUE-L-IN"];
		$TUE_OUT = $row["TUE-OUT"];
		$WED_IN = $row["WED-IN"];
		$WED_L_OUT = $row["WED-L-OUT"];
		$WED_L_IN = $row["WED-L-IN"];
		$WED_OUT = $row["WED-OUT"];
		$THU_IN = $row["THU-IN"];
		$THU_L_OUT = $row["THU-L-OUT"];
		$THU_L_IN = $row["THU-L-IN"];
		$THU_OUT = $row["THU-OUT"];
		$FRI_IN = $row["FRI-IN"];
		$FRI_L_OUT = $row["FRI-L-OUT"];
		$FRI_L_IN = $row["FRI-L-IN"];
		$FRI_OUT = $row["FRI-OUT"];
		$SAT_IN = $row["SAT-IN"];
		$SAT_L_OUT = $row["SAT-L-OUT"];
		$SAT_L_IN = $row["SAT-L-IN"];
		$SAT_OUT = $row["SAT-OUT"];
		$SUN_IN = $row["SUN-IN"];
		$SUN_L_OUT = $row["SUN-L-OUT"];
		$SUN_L_IN = $row["SUN-L-IN"];
		$SUN_OUT = $row["SUN-OUT"];
		if ($i == 0) {
			echo "<h1 align='center'>Payroll Report for last " . $Days . " day(s)</h1>\n";
			// Create Table
			echo "<table class='pure-table pure-table-bordered' border='1' align='center' width='500px' style='border: 1px solid black; border-collapse: collapse;'>";
			echo "<tr>";
			echo "<th width='300px'>" . "&nbsp;Employee&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;Date&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;THU-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;THU-L-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;THU-L-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;THU-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;FRI-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;FRI-L-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;FRI-L-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;FRI-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;SAT-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;SAT-L-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;SAT-L-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;SAT-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;SUN-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;SUN-L-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;SUN-L-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;SUN-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;MON-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;MON-L-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;MON-L-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;MON-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;TUE-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;TUE-L-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;TUE-L-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;TUE-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;WED-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;WED-L-OUT&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;WED-L-IN&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;WED-OUT&nbsp;" . "</th>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align='center'>" . $name . "</td>";
			echo "<td align='center'>" . $date . "</td>";
			echo "<td align='center'>" . $THU_IN . "</td>";
			echo "<td align='center'>" . $THU_L_OUT . "</td>";
			echo "<td align='center'>" . $THU_L_IN . "</td>";
			echo "<td align='center'>" . $THU_OUT . "</td>";
			echo "<td align='center'>" . $FRI_IN . "</td>";
			echo "<td align='center'>" . $FRI_L_OUT . "</td>";
			echo "<td align='center'>" . $FRI_L_IN . "</td>";
			echo "<td align='center'>" . $FRI_OUT . "</td>";
			echo "<td align='center'>" . $SAT_IN . "</td>";
			echo "<td align='center'>" . $SAT_L_OUT . "</td>";
			echo "<td align='center'>" . $SAT_L_IN . "</td>";
			echo "<td align='center'>" . $SAT_OUT . "</td>";
			echo "<td align='center'>" . $SUN_IN . "</td>";
			echo "<td align='center'>" . $SUN_L_OUT . "</td>";
			echo "<td align='center'>" . $SUN_L_IN . "</td>";
			echo "<td align='center'>" . $SUN_OUT . "</td>";
			echo "<td align='center'>" . $MON_IN . "</td>";
			echo "<td align='center'>" . $MON_L_OUT . "</td>";
			echo "<td align='center'>" . $MON_L_IN . "</td>";
			echo "<td align='center'>" . $MON_OUT . "</td>";
			echo "<td align='center'>" . $TUE_IN . "</td>";
			echo "<td align='center'>" . $TUE_L_OUT . "</td>";
			echo "<td align='center'>" . $TUE_L_IN . "</td>";
			echo "<td align='center'>" . $TUE_OUT . "</td>";
			echo "<td align='center'>" . $WED_IN . "</td>";
			echo "<td align='center'>" . $WED_L_OUT . "</td>";
			echo "<td align='center'>" . $WED_L_IN . "</td>";
			echo "<td align='center'>" . $WED_OUT . "</td>";
			echo "</tr>";
			$i++;
		} else {
			echo "<tr>";
			echo "<td align='center'>" . $name . "</td>";
			echo "<td align='center'>" . $date . "</td>";
			echo "<td align='center'>" . $THU_IN . "</td>";
			echo "<td align='center'>" . $THU_L_OUT . "</td>";
			echo "<td align='center'>" . $THU_L_IN . "</td>";
			echo "<td align='center'>" . $THU_OUT . "</td>";
			echo "<td align='center'>" . $FRI_IN . "</td>";
			echo "<td align='center'>" . $FRI_L_OUT . "</td>";
			echo "<td align='center'>" . $FRI_L_IN . "</td>";
			echo "<td align='center'>" . $FRI_OUT . "</td>";
			echo "<td align='center'>" . $SAT_IN . "</td>";
			echo "<td align='center'>" . $SAT_L_OUT . "</td>";
			echo "<td align='center'>" . $SAT_L_IN . "</td>";
			echo "<td align='center'>" . $SAT_OUT . "</td>";
			echo "<td align='center'>" . $SUN_IN . "</td>";
			echo "<td align='center'>" . $SUN_L_OUT . "</td>";
			echo "<td align='center'>" . $SUN_L_IN . "</td>";
			echo "<td align='center'>" . $SUN_OUT . "</td>";
			echo "<td align='center'>" . $MON_IN . "</td>";
			echo "<td align='center'>" . $MON_L_OUT . "</td>";
			echo "<td align='center'>" . $MON_L_IN . "</td>";
			echo "<td align='center'>" . $MON_OUT . "</td>";
			echo "<td align='center'>" . $TUE_IN . "</td>";
			echo "<td align='center'>" . $TUE_L_OUT . "</td>";
			echo "<td align='center'>" . $TUE_L_IN . "</td>";
			echo "<td align='center'>" . $TUE_OUT . "</td>";
			echo "<td align='center'>" . $WED_IN . "</td>";
			echo "<td align='center'>" . $WED_L_OUT . "</td>";
			echo "<td align='center'>" . $WED_L_IN . "</td>";
			echo "<td align='center'>" . $WED_OUT . "</td>";
			echo "</tr>";
			$i++;
		}
	}
} else {
	echo "0 results";
}
if ($i <= 1) {
	echo "<tr>";
	echo "<td align='center'>" . $name . "</td>";
	echo "<td align='center'>" . $date . "</td>";
	echo "<td align='center'>" . $name . "</td>";
	echo "<td align='center'>" . $date . "</td>";
	echo "<td align='center'>" . $THU_IN . "</td>";
	echo "<td align='center'>" . $THU_L_OUT . "</td>";
	echo "<td align='center'>" . $THU_L_IN . "</td>";
	echo "<td align='center'>" . $THU_OUT . "</td>";
	echo "<td align='center'>" . $FRI_IN . "</td>";
	echo "<td align='center'>" . $FRI_L_OUT . "</td>";
	echo "<td align='center'>" . $FRI_L_IN . "</td>";
	echo "<td align='center'>" . $FRI_OUT . "</td>";
	echo "<td align='center'>" . $SAT_IN . "</td>";
	echo "<td align='center'>" . $SAT_L_OUT . "</td>";
	echo "<td align='center'>" . $SAT_L_IN . "</td>";
	echo "<td align='center'>" . $SAT_OUT . "</td>";
	echo "<td align='center'>" . $SUN_IN . "</td>";
	echo "<td align='center'>" . $SUN_L_OUT . "</td>";
	echo "<td align='center'>" . $SUN_L_IN . "</td>";
	echo "<td align='center'>" . $SUN_OUT . "</td>";
	echo "<td align='center'>" . $MON_IN . "</td>";
	echo "<td align='center'>" . $MON_L_OUT . "</td>";
	echo "<td align='center'>" . $MON_L_IN . "</td>";
	echo "<td align='center'>" . $MON_OUT . "</td>";
	echo "<td align='center'>" . $TUE_IN . "</td>";
	echo "<td align='center'>" . $TUE_L_OUT . "</td>";
	echo "<td align='center'>" . $TUE_L_IN . "</td>";
	echo "<td align='center'>" . $TUE_OUT . "</td>";
	echo "<td align='center'>" . $WED_IN . "</td>";
	echo "<td align='center'>" . $WED_L_OUT . "</td>";
	echo "<td align='center'>" . $WED_L_IN . "</td>";
	echo "<td align='center'>" . $WED_OUT . "</td>";
	echo "</tr>";
}

echo "<div align='center'>(v) Not Clocked IN&nbsp;&nbsp;&nbsp;(<) Lunch Not Clocked Out&nbsp;&nbsp;&nbsp;(>) Lunch Not Clocked In&nbsp;&nbsp;&nbsp;(^) Not Clocked Out</div>";
echo "<br />";
$conn->close();
?>



<html>
<title></title>
<head>
<link rel="stylesheet" href="../css/pure/pure-min.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css" media="print">
  @page { size: landscape; }
</style>
</head>
<body>

</body>
</html>
