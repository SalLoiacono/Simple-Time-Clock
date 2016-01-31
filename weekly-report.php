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

// Customizable Variables
$Days = 7; // Amount of days to go back for reports
$PenaltyIN = 0; // Time user is clocked in automatically if they forget to clock in, but still clock out. (e.g. work starts at 8, but this can be set at 9 as a 1-hour penalty for not clocking in)
$PenaltyOUT = 0; // Time user is clocked out automatically if they forget to clock out, but still clock in.

// Show last 'x' days of reports from database
$sql = "SELECT * FROM TimeClock WHERE Date > DATE_SUB( NOW(), INTERVAL " . $Days . " DAY) ORDER BY Name ASC";
$result = $conn->query($sql);
$i = 0;

if ($result->num_rows > 0) {
	// output data of each row
	while ($row = $result->fetch_assoc()) {
		// Initialize variables and read from database
		$name = $row["Name"];
		$date = $row["Date"];
		
		
		//############################################################################################################################
		//###################################################| M O N D A Y |##########################################################
		//############################################################################################################################

		// Zero Values
		$MondayLunch = $MondayHOURS = $MondayTOTAL = $MondayOUT = $MondayIN = 0;

		// Check if employee clocked out/in
		$MonOutCheck = $row["MON-OUT"];

		if ($MonOutCheck == "0000-00-00 00:00:00") {
			$MonOut = "&nbsp;^";
		} else {
			$MonOut = "";
		}
		$MonInCheck = $row["MON-IN"];
		if ($MonInCheck == "0000-00-00 00:00:00") {
			$MonIn = "&nbsp;v";
		} else {
			$MonIn = "";
		}
		$MonLunchOutCheck = $row["MON-L-OUT"];
		if ($MonLunchOutCheck == "0000-00-00 00:00:00") {
			$MonLOut = "&nbsp;<";
		} else {
			$MonLOut = "";
		}
		$MonLunchInCheck = $row["MON-L-IN"];
		if ($MonLunchInCheck == "0000-00-00 00:00:00") {
			$MonLIn = "&nbsp;>";
		} else {
			$MonLIn = "";
		}

		// Read times and separate by hours and min in decimal form
		$MondayIN_H = date("H", strtotime($row["MON-IN"]));
		$MondayIN_M = date("i", strtotime($row["MON-IN"]));
		$MondayLunchOUT_H = date("H", strtotime($row["MON-L-OUT"]));
		$MondayLunchOUT_M = date("i", strtotime($row["MON-L-OUT"]));
		$MondayLunchIN_H = date("H", strtotime($row["MON-L-IN"]));
		$MondayLunchIN_M = date("i", strtotime($row["MON-L-IN"]));
		$MondayOUT_H = date("H", strtotime($row["MON-OUT"]));
		$MondayOUT_M = date("i", strtotime($row["MON-OUT"]));

		// Convert Min to Dec
		$MondayLunchOUT_M = $MondayLunchOUT_M / 60;
		$MondayLunchOUT_M = number_format((float)$MondayLunchOUT_M, 2);
		$MondayLunchIN_M = $MondayLunchIN_M / 60;
		$MondayLunchIN_M = number_format((float)$MondayLunchIN_M, 2);
		$MondayIN_M = $MondayIN_M / 60;
		$MondayIN_M = number_format((float)$MondayIN_M, 2);
		$MondayOUT_M = $MondayOUT_M / 60;
		$MondayOUT_M = number_format((float)$MondayOUT_M, 2);

		//Fix if employee did not check in/out
		if (((($MonIn == "") || ($MonLOut == "") || ($MonLIn == "") || ($MonOut == "")))) {
			if ($MonIn == "") {
				$MondayIN = $MondayIN_H + $MondayIN_M;
			} else {
				$MondayIN = $PenaltyIN;
			}
			if ($MonLOut == "") {
				$MondayLunchOUT = $MondayLunchOUT_H + $MondayLunchOUT_M;
			} else {
				$MondayLunchOUT = 0;
			}
			if ($MonLIn == "") {
				$MondayLunchIN = $MondayLunchIN_H + $MondayLunchIN_M;
			} else {
				$MondayLunchIN = 0;
			}
			if ($MonOut == "") {
				$MondayOUT = $MondayOUT_H + $MondayOUT_M;
			} else {
				$MondayOUT = $PenaltyOUT;
			}
		} else {
			$MondayIN = $MondayLunchOUT = $MondayLunchIN = $MondayOUT = 0;
		}
		$MondayHOURS = $MondayOUT - $MondayIN;

		// Fix for lunch if not clocked in/out
		if ($MondayIN != 0) {
			if (($MondayLunchOUT == 0) && ($MondayLunchIN == 0)) {
				if ($MondayHOURS >= 8) {
					$MondayLunch = 0.5;
				}
			} elseif (($MondayLunchIN != 0) && ($MondayLunchOUT == 0)) {
				if ($MondayHOURS >= 8) {
					$MondayLunch = 0.75;
				}
			} elseif (($MondayLunchOUT != 0) && ($MondayLunchIN == 0)) {
				if ($MondayHOURS >= 8) {
					$MondayLunch = 0.75;
				}
			} else {
				$MondayLunch = $MondayLunchIN - $MondayLunchOUT;
			}
		}

		// Find total hours worked
		$MondayTOTAL = $MondayHOURS - $MondayLunch;
		if ($MondayIN == 0) {
			$MondayTOTAL = 0;
		}
		$MondayTOTAL = number_format((float)$MondayTOTAL, 2);


		//################################################################################################################################
		//###################################################| T U E S D A Y |############################################################
		//################################################################################################################################

		// Zero Values
		$TuesdayLunch = $TuesdayHOURS = $TuesdayTOTAL = $TuesdayOUT = $TuesdayIN = 0;

		// Check if employee clocked out/in
		$TueOutCheck = $row["TUE-OUT"];
		if ($TueOutCheck == "0000-00-00 00:00:00") {
			$TueOut = "&nbsp;^";
		} else {
			$TueOut = "";
		}
		$TueInCheck = $row["TUE-IN"];
		if ($TueInCheck == "0000-00-00 00:00:00") {
			$TueIn = "&nbsp;v";
		} else {
			$TueIn = "";
		}
		$TueLunchOutCheck = $row["TUE-L-OUT"];
		if ($TueLunchOutCheck == "0000-00-00 00:00:00") {
			$TueLOut = "&nbsp;<";
		} else {
			$TueLOut = "";
		}
		$TueLunchInCheck = $row["TUE-L-IN"];
		if ($TueLunchInCheck == "0000-00-00 00:00:00") {
			$TueLIn = "&nbsp;>";
		} else {
			$TueLIn = "";
		}

		// Read times and separate by hours and min in decimal form
		$TuesdayIN_H = date("H", strtotime($row["TUE-IN"]));
		$TuesdayIN_M = date("i", strtotime($row["TUE-IN"]));
		$TuesdayLunchOUT_H = date("H", strtotime($row["TUE-L-OUT"]));
		$TuesdayLunchOUT_M = date("i", strtotime($row["TUE-L-OUT"]));
		$TuesdayLunchIN_H = date("H", strtotime($row["TUE-L-IN"]));
		$TuesdayLunchIN_M = date("i", strtotime($row["TUE-L-IN"]));
		$TuesdayOUT_H = date("H", strtotime($row["TUE-OUT"]));
		$TuesdayOUT_M = date("i", strtotime($row["TUE-OUT"]));

		// Convert Min to Dec
		$TuesdayLunchOUT_M = $TuesdayLunchOUT_M / 60;
		$TuesdayLunchOUT_M = number_format((float)$TuesdayLunchOUT_M, 2);
		$TuesdayLunchIN_M = $TuesdayLunchIN_M / 60;
		$TuesdayLunchIN_M = number_format((float)$TuesdayLunchIN_M, 2);
		$TuesdayIN_M = $TuesdayIN_M / 60;
		$TuesdayIN_M = number_format((float)$TuesdayIN_M, 2);
		$TuesdayOUT_M = $TuesdayOUT_M / 60;
		$TuesdayOUT_M = number_format((float)$TuesdayOUT_M, 2);

		//Fix if employee did not check in/out
		if (((($TueIn == "") || ($TueLOut == "") || ($TueLIn == "") || ($TueOut == "")))) {
			if ($TueIn == "") {
				$TuesdayIN = $TuesdayIN_H + $TuesdayIN_M;
			} else {
				$TuesdayIN = $PenaltyIN;
			}
			if ($TueLOut == "") {
				$TuesdayLunchOUT = $TuesdayLunchOUT_H + $TuesdayLunchOUT_M;
			} else {
				$TuesdayLunchOUT = 0;
			}
			if ($TueLIn == "") {
				$TuesdayLunchIN = $TuesdayLunchIN_H + $TuesdayLunchIN_M;
			} else {
				$TuesdayLunchIN = 0;
			}
			if ($TueOut == "") {
				$TuesdayOUT = $TuesdayOUT_H + $TuesdayOUT_M;
			} else {
				$TuesdayOUT = $PenaltyOUT;
			}
		} else {
			$TuesdayIN = $TuesdayLunchOUT = $TuesdayLunchIN = $TuesdayOUT = 0;
		}
		$TuesdayHOURS = $TuesdayOUT - $TuesdayIN;

		// Fix for lunch if not clocked in/out
		if ($TuesdayIN != 0) {
			if (($TuesdayLunchOUT == 0) && ($TuesdayLunchIN == 0)) {
				if ($TuesdayHOURS >= 8) {
					$TuesdayLunch = 0.5;
				}
			} elseif (($TuesdayLunchIN != 0) && ($TuesdayLunchOUT == 0)) {
				if ($TuesdayHOURS >= 8) {
					$TuesdayLunch = 0.75;
				}
			} elseif (($TuesdayLunchOUT != 0) && ($TuesdayLunchIN == 0)) {
				if ($TuesdayHOURS >= 8) {
					$TuesdayLunch = 0.75;
				}
			} else {
				$TuesdayLunch = $TuesdayLunchIN - $TuesdayLunchOUT;
			}
		}

		// Find total hours worked
		$TuesdayTOTAL = $TuesdayHOURS - $TuesdayLunch;
		if ($TuesdayIN == 0) {
			$TuesdayTOTAL = 0;
		}
		$TuesdayTOTAL = number_format((float)$TuesdayTOTAL, 2);


		//################################################################################################################################
		//###################################################| W E D N E S D A Y |########################################################
		//################################################################################################################################

		// Zero Values
		$WednesdayLunch = $WednesdayHOURS = $WednesdayTOTAL = $WednesdayOUT = $WednesdayIN = 0;

		// Check if employee clocked out/in
		$WedOutCheck = $row["WED-OUT"];
		if ($WedOutCheck == "0000-00-00 00:00:00") {
			$WedOut = "&nbsp;^";
		} else {
			$WedOut = "";
		}
		$WedInCheck = $row["WED-IN"];
		if ($WedInCheck == "0000-00-00 00:00:00") {
			$WedIn = "&nbsp;v";
		} else {
			$WedIn = "";
		}
		$WedLunchOutCheck = $row["WED-L-OUT"];
		if ($WedLunchOutCheck == "0000-00-00 00:00:00") {
			$WedLOut = "&nbsp;<";
		} else {
			$WedLOut = "";
		}
		$WedLunchInCheck = $row["WED-L-IN"];
		if ($WedLunchInCheck == "0000-00-00 00:00:00") {
			$WedLIn = "&nbsp;>";
		} else {
			$WedLIn = "";
		}

		// Read times and separate by hours and min in decimal form
		$WednesdayIN_H = date("H", strtotime($row["WED-IN"]));
		$WednesdayIN_M = date("i", strtotime($row["WED-IN"]));
		$WednesdayLunchOUT_H = date("H", strtotime($row["WED-L-OUT"]));
		$WednesdayLunchOUT_M = date("i", strtotime($row["WED-L-OUT"]));
		$WednesdayLunchIN_H = date("H", strtotime($row["WED-L-IN"]));
		$WednesdayLunchIN_M = date("i", strtotime($row["WED-L-IN"]));
		$WednesdayOUT_H = date("H", strtotime($row["WED-OUT"]));
		$WednesdayOUT_M = date("i", strtotime($row["WED-OUT"]));

		// Convert Min to Dec
		$WednesdayLunchOUT_M = $WednesdayLunchOUT_M / 60;
		$WednesdayLunchOUT_M = number_format((float)$WednesdayLunchOUT_M, 2);
		$WednesdayLunchIN_M = $WednesdayLunchIN_M / 60;
		$WednesdayLunchIN_M = number_format((float)$WednesdayLunchIN_M, 2);
		$WednesdayIN_M = $WednesdayIN_M / 60;
		$WednesdayIN_M = number_format((float)$WednesdayIN_M, 2);
		$WednesdayOUT_M = $WednesdayOUT_M / 60;
		$WednesdayOUT_M = number_format((float)$WednesdayOUT_M, 2);

		//Fix if employee did not check in/out
		if (((($WedIn == "") || ($WedLOut == "") || ($WedLIn == "") || ($WedOut == "")))) {
			if ($WedIn == "") {
				$WednesdayIN = $WednesdayIN_H + $WednesdayIN_M;
			} else {
				$WednesdayIN = $PenaltyIN;
			}
			if ($WedLOut == "") {
				$WednesdayLunchOUT = $WednesdayLunchOUT_H + $WednesdayLunchOUT_M;
			} else {
				$WednesdayLunchOUT = 0;
			}
			if ($WedLIn == "") {
				$WednesdayLunchIN = $WednesdayLunchIN_H + $WednesdayLunchIN_M;
			} else {
				$WednesdayLunchIN = 0;
			}
			if ($WedOut == "") {
				$WednesdayOUT = $WednesdayOUT_H + $WednesdayOUT_M;
			} else {
				$WednesdayOUT = $PenaltyOUT;
			}
		} else {
			$WednesdayIN = $WednesdayLunchOUT = $WednesdayLunchIN = $WednesdayOUT = 0;
		}
		$WednesdayHOURS = $WednesdayOUT - $WednesdayIN;

		// Fix for lunch if not clocked in/out
		if ($WednesdayIN != 0) {
			if (($WednesdayLunchOUT == 0) && ($WednesdayLunchIN == 0)) {
				if ($WednesdayHOURS >= 8) {
					$WednesdayLunch = 0.5;
				}
			} elseif (($WednesdayLunchIN != 0) && ($WednesdayLunchOUT == 0)) {
				if ($WednesdayHOURS >= 8) {
					$WednesdayLunch = 0.75;
				}
			} elseif (($WednesdayLunchOUT != 0) && ($WednesdayLunchIN == 0)) {
				if ($WednesdayHOURS >= 8) {
					$WednesdayLunch = 0.75;
				}
			} else {
				$WednesdayLunch = $WednesdayLunchIN - $WednesdayLunchOUT;
			}
		}

		// Find total hours worked
		$WednesdayTOTAL = $WednesdayHOURS - $WednesdayLunch;
		if ($WednesdayIN == 0) {
			$WednesdayTOTAL = 0;
		}
		$WednesdayTOTAL = number_format((float)$WednesdayTOTAL, 2);


		//##################################################################################################################################
		//###################################################| T H U R S D A Y |############################################################
		//##################################################################################################################################

		// Zero Values
		$ThursdayLunch = $ThursdayHOURS = $ThursdayTOTAL = $ThursdayOUT = $ThursdayIN = 0;

		// Check if employee clocked out/in
		$ThuOutCheck = $row["THU-OUT"];
		if ($ThuOutCheck == "0000-00-00 00:00:00") {
			$ThuOut = "&nbsp;^";
		} else {
			$ThuOut = "";
		}
		$ThuInCheck = $row["THU-IN"];
		if ($ThuInCheck == "0000-00-00 00:00:00") {
			$ThuIn = "&nbsp;v";
		} else {
			$ThuIn = "";
		}
		$ThuLunchOutCheck = $row["THU-L-OUT"];
		if ($ThuLunchOutCheck == "0000-00-00 00:00:00") {
			$ThuLOut = "&nbsp;<";
		} else {
			$ThuLOut = "";
		}
		$ThuLunchInCheck = $row["THU-L-IN"];
		if ($ThuLunchInCheck == "0000-00-00 00:00:00") {
			$ThuLIn = "&nbsp;>";
		} else {
			$ThuLIn = "";
		}

		// Read times and separate by hours and min in decimal form
		$ThursdayIN_H = date("H", strtotime($row["THU-IN"]));
		$ThursdayIN_M = date("i", strtotime($row["THU-IN"]));
		$ThursdayLunchOUT_H = date("H", strtotime($row["THU-L-OUT"]));
		$ThursdayLunchOUT_M = date("i", strtotime($row["THU-L-OUT"]));
		$ThursdayLunchIN_H = date("H", strtotime($row["THU-L-IN"]));
		$ThursdayLunchIN_M = date("i", strtotime($row["THU-L-IN"]));
		$ThursdayOUT_H = date("H", strtotime($row["THU-OUT"]));
		$ThursdayOUT_M = date("i", strtotime($row["THU-OUT"]));

		// Convert Min to Dec
		$ThursdayLunchOUT_M = $ThursdayLunchOUT_M / 60;
		$ThursdayLunchOUT_M = number_format((float)$ThursdayLunchOUT_M, 2);
		$ThursdayLunchIN_M = $ThursdayLunchIN_M / 60;
		$ThursdayLunchIN_M = number_format((float)$ThursdayLunchIN_M, 2);
		$ThursdayIN_M = $ThursdayIN_M / 60;
		$ThursdayIN_M = number_format((float)$ThursdayIN_M, 2);
		$ThursdayOUT_M = $ThursdayOUT_M / 60;
		$ThursdayOUT_M = number_format((float)$ThursdayOUT_M, 2);

		//Fix if employee did not check in/out
		if (((($ThuIn == "") || ($ThuLOut == "") || ($ThuLIn == "") || ($ThuOut == "")))) {
			if ($ThuIn == "") {
				$ThursdayIN = $ThursdayIN_H + $ThursdayIN_M;
			} else {
				$ThursdayIN = $PenaltyIN;
			}
			if ($ThuLOut == "") {
				$ThursdayLunchOUT = $ThursdayLunchOUT_H + $ThursdayLunchOUT_M;
			} else {
				$ThursdayLunchOUT = 0;
			}
			if ($ThuLIn == "") {
				$ThursdayLunchIN = $ThursdayLunchIN_H + $ThursdayLunchIN_M;
			} else {
				$ThursdayLunchIN = 0;
			}
			if ($ThuOut == "") {
				$ThursdayOUT = $ThursdayOUT_H + $ThursdayOUT_M;
			} else {
				$ThursdayOUT = $PenaltyOUT;
			}
		} else {
			$ThursdayIN = $ThursdayLunchOUT = $ThursdayLunchIN = $ThursdayOUT = 0;
		}
		$ThursdayHOURS = $ThursdayOUT - $ThursdayIN;

		// Fix for lunch if not clocked in/out
		if ($ThursdayIN != 0) {
			if (($ThursdayLunchOUT == 0) && ($ThursdayLunchIN == 0)) {
				if ($ThursdayHOURS >= 8) {
					$ThursdayLunch = 0.5;
				}
			} elseif (($ThursdayLunchIN != 0) && ($ThursdayLunchOUT == 0)) {
				if ($ThursdayHOURS >= 8) {
					$ThursdayLunch = 0.75;
				}
			} elseif (($ThursdayLunchOUT != 0) && ($ThursdayLunchIN == 0)) {
				if ($ThursdayHOURS >= 8) {
					$ThursdayLunch = 0.75;
				}
			} else {
				$ThursdayLunch = $ThursdayLunchIN - $ThursdayLunchOUT;
			}
		}

		// Find total hours worked
		$ThursdayTOTAL = $ThursdayHOURS - $ThursdayLunch;
		if ($ThursdayIN == 0) {
			$ThursdayTOTAL = 0;
		}
		$ThursdayTOTAL = number_format((float)$ThursdayTOTAL, 2);


		//################################################################################################################################
		//###################################################| F R I D A Y |##############################################################
		//################################################################################################################################

		// Zero Values
		$FridayLunch = $FridayHOURS = $FridayTOTAL = $FridayOUT = $FridayIN = 0;

		// Check if employee clocked out/in
		$FriOutCheck = $row["FRI-OUT"];
		if ($FriOutCheck == "0000-00-00 00:00:00") {
			$FriOut = "&nbsp;^";
		} else {
			$FriOut = "";
		}
		$FriInCheck = $row["FRI-IN"];
		if ($FriInCheck == "0000-00-00 00:00:00") {
			$FriIn = "&nbsp;v";
		} else {
			$FriIn = "";
		}
		$FriLunchOutCheck = $row["FRI-L-OUT"];
		if ($FriLunchOutCheck == "0000-00-00 00:00:00") {
			$FriLOut = "&nbsp;<";
		} else {
			$FriLOut = "";
		}
		$FriLunchInCheck = $row["FRI-L-IN"];
		if ($FriLunchInCheck == "0000-00-00 00:00:00") {
			$FriLIn = "&nbsp;>";
		} else {
			$FriLIn = "";
		}

		// Read times and separate by hours and min in decimal form
		$FridayIN_H = date("H", strtotime($row["FRI-IN"]));
		$FridayIN_M = date("i", strtotime($row["FRI-IN"]));
		$FridayLunchOUT_H = date("H", strtotime($row["FRI-L-OUT"]));
		$FridayLunchOUT_M = date("i", strtotime($row["FRI-L-OUT"]));
		$FridayLunchIN_H = date("H", strtotime($row["FRI-L-IN"]));
		$FridayLunchIN_M = date("i", strtotime($row["FRI-L-IN"]));
		$FridayOUT_H = date("H", strtotime($row["FRI-OUT"]));
		$FridayOUT_M = date("i", strtotime($row["FRI-OUT"]));

		// Convert Min to Dec
		$FridayLunchOUT_M = $FridayLunchOUT_M / 60;
		$FridayLunchOUT_M = number_format((float)$FridayLunchOUT_M, 2);
		$FridayLunchIN_M = $FridayLunchIN_M / 60;
		$FridayLunchIN_M = number_format((float)$FridayLunchIN_M, 2);
		$FridayIN_M = $FridayIN_M / 60;
		$FridayIN_M = number_format((float)$FridayIN_M, 2);
		$FridayOUT_M = $FridayOUT_M / 60;
		$FridayOUT_M = number_format((float)$FridayOUT_M, 2);

		//Fix if employee did not check in/out
		if (((($FriIn == "") || ($FriLOut == "") || ($FriLIn == "") || ($FriOut == "")))) {
			if ($FriIn == "") {
				$FridayIN = $FridayIN_H + $FridayIN_M;
			} else {
				$FridayIN = $PenaltyIN;
			}
			if ($FriLOut == "") {
				$FridayLunchOUT = $FridayLunchOUT_H + $FridayLunchOUT_M;
			} else {
				$FridayLunchOUT = 0;
			}
			if ($FriLIn == "") {
				$FridayLunchIN = $FridayLunchIN_H + $FridayLunchIN_M;
			} else {
				$FridayLunchIN = 0;
			}
			if ($FriOut == "") {
				$FridayOUT = $FridayOUT_H + $FridayOUT_M;
			} else {
				$FridayOUT = $PenaltyOUT;
			}
		} else {
			$FridayIN = $FridayLunchOUT = $FridayLunchIN = $FridayOUT = 0;
		}
		$FridayHOURS = $FridayOUT - $FridayIN;

		// Fix for lunch if not clocked in/out
		if ($FridayIN != 0) {
			if (($FridayLunchOUT == 0) && ($FridayLunchIN == 0)) {
				if ($FridayHOURS >= 8) {
					$FridayLunch = 0.5;
				}
			} elseif (($FridayLunchIN != 0) && ($FridayLunchOUT == 0)) {
				if ($FridayHOURS >= 8) {
					$FridayLunch = 0.75;
				}
			} elseif (($FridayLunchOUT != 0) && ($FridayLunchIN == 0)) {
				if ($FridayHOURS >= 8) {
					$FridayLunch = 0.75;
				}
			} else {
				$FridayLunch = $FridayLunchIN - $FridayLunchOUT;
			}
		}

		// Find total hours worked
		$FridayTOTAL = $FridayHOURS - $FridayLunch;
		if ($FridayIN == 0) {
			$FridayTOTAL = 0;
		}
		$FridayTOTAL = number_format((float)$FridayTOTAL, 2);


		//##################################################################################################################################
		//###################################################| S A T U R D A Y |############################################################
		//##################################################################################################################################

		// Zero Values
		$SaturdayLunch = $SaturdayHOURS = $SaturdayTOTAL = $SaturdayOUT = $SaturdayIN = 0;

		// Check if employee clocked out/in
		$SatOutCheck = $row["SAT-OUT"];
		if ($SatOutCheck == "0000-00-00 00:00:00") {
			$SatOut = "&nbsp;^";
		} else {
			$SatOut = "";
		}
		$SatInCheck = $row["SAT-IN"];
		if ($SatInCheck == "0000-00-00 00:00:00") {
			$SatIn = "&nbsp;v";
		} else {
			$SatIn = "";
		}
		$SatLunchOutCheck = $row["SAT-L-OUT"];
		if ($SatLunchOutCheck == "0000-00-00 00:00:00") {
			$SatLOut = "&nbsp;<";
		} else {
			$SatLOut = "";
		}
		$SatLunchInCheck = $row["SAT-L-IN"];
		if ($SatLunchInCheck == "0000-00-00 00:00:00") {
			$SatLIn = "&nbsp;>";
		} else {
			$SatLIn = "";
		}

		// Read times and separate by hours and min in decimal form
		$SaturdayIN_H = date("H", strtotime($row["SAT-IN"]));
		$SaturdayIN_M = date("i", strtotime($row["SAT-IN"]));
		$SaturdayLunchOUT_H = date("H", strtotime($row["SAT-L-OUT"]));
		$SaturdayLunchOUT_M = date("i", strtotime($row["SAT-L-OUT"]));
		$SaturdayLunchIN_H = date("H", strtotime($row["SAT-L-IN"]));
		$SaturdayLunchIN_M = date("i", strtotime($row["SAT-L-IN"]));
		$SaturdayOUT_H = date("H", strtotime($row["SAT-OUT"]));
		$SaturdayOUT_M = date("i", strtotime($row["SAT-OUT"]));

		// Convert Min to Dec
		$SaturdayLunchOUT_M = $SaturdayLunchOUT_M / 60;
		$SaturdayLunchOUT_M = number_format((float)$SaturdayLunchOUT_M, 2);
		$SaturdayLunchIN_M = $SaturdayLunchIN_M / 60;
		$SaturdayLunchIN_M = number_format((float)$SaturdayLunchIN_M, 2);
		$SaturdayIN_M = $SaturdayIN_M / 60;
		$SaturdayIN_M = number_format((float)$SaturdayIN_M, 2);
		$SaturdayOUT_M = $SaturdayOUT_M / 60;
		$SaturdayOUT_M = number_format((float)$SaturdayOUT_M, 2);

		//Fix if employee did not check in/out
		if (((($SatIn == "") || ($SatLOut == "") || ($SatLIn == "") || ($SatOut == "")))) {
			if ($SatIn == "") {
				$SaturdayIN = $SaturdayIN_H + $SaturdayIN_M;
			} else {
				$SaturdayIN = $PenaltyIN;
			}
			if ($SatLOut == "") {
				$SaturdayLunchOUT = $SaturdayLunchOUT_H + $SaturdayLunchOUT_M;
			} else {
				$SaturdayLunchOUT = 0;
			}
			if ($SatLIn == "") {
				$SaturdayLunchIN = $SaturdayLunchIN_H + $SaturdayLunchIN_M;
			} else {
				$SaturdayLunchIN = 0;
			}
			if ($SatOut == "") {
				$SaturdayOUT = $SaturdayOUT_H + $SaturdayOUT_M;
			} else {
				$SaturdayOUT = $PenaltyOUT;
			}
		} else {
			$SaturdayIN = $SaturdayLunchOUT = $SaturdayLunchIN = $SaturdayOUT = 0;
		}
		$SaturdayHOURS = $SaturdayOUT - $SaturdayIN;

		// Fix for lunch if not clocked in/out
		if ($SaturdayIN != 0) {
			if (($SaturdayLunchOUT == 0) && ($SaturdayLunchIN == 0)) {
				if ($SaturdayHOURS >= 8) {
					$SaturdayLunch = 0.5;
				}
			} elseif (($SaturdayLunchIN != 0) && ($SaturdayLunchOUT == 0)) {
				if ($SaturdayHOURS >= 8) {
					$SaturdayLunch = 0.75;
				}
			} elseif (($SaturdayLunchOUT != 0) && ($SaturdayLunchIN == 0)) {
				if ($SaturdayHOURS >= 8) {
					$SaturdayLunch = 0.75;
				}
			} else {
				$SaturdayLunch = $SaturdayLunchIN - $SaturdayLunchOUT;
			}
		}

		// Find total hours worked
		$SaturdayTOTAL = $SaturdayHOURS - $SaturdayLunch;
		if ($SaturdayIN == 0) {
			$SaturdayTOTAL = 0;
		}
		$SaturdayTOTAL = number_format((float)$SaturdayTOTAL, 2);


		//##################################################################################################################################
		//###################################################| S U N D A Y |################################################################
		//##################################################################################################################################

		// Zero Values
		$SundayLunch = $SundayHOURS = $SundayTOTAL = $SundayOUT = $SundayIN = 0;

		// Check if employee clocked out/in
		$SunOutCheck = $row["SUN-OUT"];
		if ($SunOutCheck == "0000-00-00 00:00:00") {
			$SunOut = "&nbsp;^";
		} else {
			$SunOut = "";
		}
		$SunInCheck = $row["SUN-IN"];
		if ($SunInCheck == "0000-00-00 00:00:00") {
			$SunIn = "&nbsp;v";
		} else {
			$SunIn = "";
		}
		$SunLunchOutCheck = $row["SUN-L-OUT"];
		if ($SunLunchOutCheck == "0000-00-00 00:00:00") {
			$SunLOut = "&nbsp;<";
		} else {
			$SunLOut = "";
		}
		$SunLunchInCheck = $row["SUN-L-IN"];
		if ($SunLunchInCheck == "0000-00-00 00:00:00") {
			$SunLIn = "&nbsp;>";
		} else {
			$SunLIn = "";
		}

		// Read times and separate by hours and min in decimal form
		$SundayIN_H = date("H", strtotime($row["SUN-IN"]));
		$SundayIN_M = date("i", strtotime($row["SUN-IN"]));
		$SundayLunchOUT_H = date("H", strtotime($row["SUN-L-OUT"]));
		$SundayLunchOUT_M = date("i", strtotime($row["SUN-L-OUT"]));
		$SundayLunchIN_H = date("H", strtotime($row["SUN-L-IN"]));
		$SundayLunchIN_M = date("i", strtotime($row["SUN-L-IN"]));
		$SundayOUT_H = date("H", strtotime($row["SUN-OUT"]));
		$SundayOUT_M = date("i", strtotime($row["SUN-OUT"]));

		// Convert Min to Dec
		$SundayLunchOUT_M = $SundayLunchOUT_M / 60;
		$SundayLunchOUT_M = number_format((float)$SundayLunchOUT_M, 2);
		$SundayLunchIN_M = $SundayLunchIN_M / 60;
		$SundayLunchIN_M = number_format((float)$SundayLunchIN_M, 2);
		$SundayIN_M = $SundayIN_M / 60;
		$SundayIN_M = number_format((float)$SundayIN_M, 2);
		$SundayOUT_M = $SundayOUT_M / 60;
		$SundayOUT_M = number_format((float)$SundayOUT_M, 2);

		//Fix if employee did not check in/out
		if (((($SunIn == "") || ($SunLOut == "") || ($SunLIn == "") || ($SunOut == "")))) {
			if ($SunIn == "") {
				$SundayIN = $SundayIN_H + $SundayIN_M;
			} else {
				$SundayIN = $PenaltyIN;
			}
			if ($SunLOut == "") {
				$SundayLunchOUT = $SundayLunchOUT_H + $SundayLunchOUT_M;
			} else {
				$SundayLunchOUT = 0;
			}
			if ($SunLIn == "") {
				$SundayLunchIN = $SundayLunchIN_H + $SundayLunchIN_M;
			} else {
				$SundayLunchIN = 0;
			}
			if ($SunOut == "") {
				$SundayOUT = $SundayOUT_H + $SundayOUT_M;
			} else {
				$SundayOUT = $PenaltyOUT;
			}
		} else {
			$SundayIN = $SundayLunchOUT = $SundayLunchIN = $SundayOUT = 0;
		}
		$SundayHOURS = $SundayOUT - $SundayIN;

		// Fix for lunch if not clocked in/out
		if ($SundayIN != 0) {
			if (($SundayLunchOUT == 0) && ($SundayLunchIN == 0)) {
				if ($SundayHOURS >= 8) {
					$SundayLunch = 0.5;
				}
			} elseif (($SundayLunchIN != 0) && ($SundayLunchOUT == 0)) {
				if ($SundayHOURS >= 8) {
					$SundayLunch = 0.75;
				}
			} elseif (($SundayLunchOUT != 0) && ($SundayLunchIN == 0)) {
				if ($SundayHOURS >= 8) {
					$SundayLunch = 0.75;
				}
			} else {
				$SundayLunch = $SundayLunchIN - $SundayLunchOUT;
			}
		}

		// Find total hours worked
		$SundayTOTAL = $SundayHOURS - $SundayLunch;
		if ($SundayIN == 0) {
			$SundayTOTAL = 0;
		}
		$SundayTOTAL = number_format((float)$SundayTOTAL, 2);


		//##################################################################################################################################
		//#################################################| E N D  O F  W E E K |##########################################################
		//##################################################################################################################################

		// Find total hours worked for the week
		$TotalHours = $MondayTOTAL + $TuesdayTOTAL + $WednesdayTOTAL + $ThursdayTOTAL + $FridayTOTAL + $SaturdayTOTAL + $SundayTOTAL;

		// If first time, create table and print out first record
		if ($i == 0) {
			echo "<h1 align='center'>Payroll Report for last " . $Days . " day(s)</h1>\n";

			// Create Table
			echo "<table class='pure-table pure-table-bordered' align='center' width='500px'>";
			echo "<tr style='background:#ddd; border: 1px solid black;'>";
			echo "<th width='300px' align='center' style='border: 1px solid black;'>" . "&nbsp;Employee&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;Date&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;Thursday&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;Friday&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;Saturday&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;Sunday&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;Monday&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;Tuesday&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;Wednesday&nbsp;" . "</th>";
			echo "<th width='100px' align='center' style='border: 1px solid black;'>" . "&nbsp;TOTAL&nbsp;" . "</th>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align='center' style='font-weight:900;'>" . $name . "</td>";
			echo "<td align='center'>" . $date . "</td>";
			echo "<td align='center'>" . $ThursdayTOTAL . $ThuIn . $ThuLOut . $ThuLIn . $ThuOut . "</td>";
			echo "<td align='center'>" . $FridayTOTAL . $FriIn . $FriLOut . $FriLIn . $FriOut . "</td>";
			echo "<td align='center'>" . $SaturdayTOTAL . $SatIn . $SatLOut . $SatLIn . $SatOut . "</td>";
			echo "<td align='center'>" . $SundayTOTAL . $SunIn . $SunLOut . $SunLIn . $SunOut . "</td>";
			echo "<td align='center'>" . $MondayTOTAL . $MonIn . $MonLOut . $MonLIn . $MonOut . "</td>";
			echo "<td align='center'>" . $TuesdayTOTAL . $TueIn . $TueLOut . $TueLIn . $TueOut . "</td>";
			echo "<td align='center'>" . $WednesdayTOTAL . $WedIn . $WedLOut . $WedLIn . $WedOut . "</td>";
			echo "<td align='center' style='font-size:16pt;'><b>" . $TotalHours . "</b></td>";
			echo "</tr>";
			$i++;
		} else {
			if ($i % 2 != 0) {
				echo "<tr class='pure-table-odd'>";
				echo "<td align='center' style='font-weight:900;'>" . $name . "</td>";
				echo "<td align='center'>" . $date . "</td>";
				echo "<td align='center'>" . $ThursdayTOTAL . $ThuIn . $ThuLOut . $ThuLIn . $ThuOut . "</td>";
				echo "<td align='center'>" . $FridayTOTAL . $FriIn . $FriLOut . $FriLIn . $FriOut . "</td>";
				echo "<td align='center'>" . $SaturdayTOTAL . $SatIn . $SatLOut . $SatLIn . $SatOut . "</td>";
				echo "<td align='center'>" . $SundayTOTAL . $SunIn . $SunLOut . $SunLIn . $SunOut . "</td>";
				echo "<td align='center'>" . $MondayTOTAL . $MonIn . $MonLOut . $MonLIn . $MonOut . "</td>";
				echo "<td align='center'>" . $TuesdayTOTAL . $TueIn . $TueLOut . $TueLIn . $TueOut . "</td>";
				echo "<td align='center'>" . $WednesdayTOTAL . $WedIn . $WedLOut . $WedLIn . $WedOut . "</td>";
				echo "<td align='center' style='font-size:16pt;'><b>" . $TotalHours . "</b></td>";
				echo "</tr>";
			}
			if ($i % 2 == 0) {
				echo "<tr>";
				echo "<td align='center' style='font-weight:900;'>" . $name . "</td>";
				echo "<td align='center'>" . $date . "</td>";
				echo "<td align='center'>" . $ThursdayTOTAL . $ThuIn . $ThuLOut . $ThuLIn . $ThuOut . "</td>";
				echo "<td align='center'>" . $FridayTOTAL . $FriIn . $FriLOut . $FriLIn . $FriOut . "</td>";
				echo "<td align='center'>" . $SaturdayTOTAL . $SatIn . $SatLOut . $SatLIn . $SatOut . "</td>";
				echo "<td align='center'>" . $SundayTOTAL . $SunIn . $SunLOut . $SunLIn . $SunOut . "</td>";
				echo "<td align='center'>" . $MondayTOTAL . $MonIn . $MonLOut . $MonLIn . $MonOut . "</td>";
				echo "<td align='center'>" . $TuesdayTOTAL . $TueIn . $TueLOut . $TueLIn . $TueOut . "</td>";
				echo "<td align='center'>" . $WednesdayTOTAL . $WedIn . $WedLOut . $WedLIn . $WedOut . "</td>";
				echo "<td align='center' style='font-size:16pt;'><b>" . $TotalHours . "</b></td>";
				echo "</tr>";
			}
			$i++;
		}
	}
} else {
	echo "0 results";
}
if ($i <= 1) {
	echo "<tr>";
	echo "<td align='center' style='font-weight:900;'>" . $name . "</td>";
	echo "<td align='center'>" . $date . "</td>";
	echo "<td align='center'>" . $ThursdayTOTAL . $ThuIn . $ThuLOut . $ThuLIn . $ThuOut . "</td>";
	echo "<td align='center'>" . $FridayTOTAL . $FriIn . $FriLOut . $FriLIn . $FriOut . "</td>";
	echo "<td align='center'>" . $SaturdayTOTAL . $SatIn . $SatLOut . $SatLIn . $SatOut . "</td>";
	echo "<td align='center'>" . $SundayTOTAL . $SunIn . $SunLOut . $SunLIn . $SunOut . "</td>";
	echo "<td align='center'>" . $MondayTOTAL . $MonIn . $MonLOut . $MonLIn . $MonOut . "</td>";
	echo "<td align='center'>" . $TuesdayTOTAL . $TueIn . $TueLOut . $TueLIn . $TueOut . "</td>";
	echo "<td align='center'>" . $WednesdayTOTAL . $WedIn . $WedLOut . $WedLIn . $WedOut . "</td>";
	echo "<td align='center' style='font-size:16pt;'><b>" . $TotalHours . "</b></td>";
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
