<html>
<title>Employee Time Clock</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<link rel="stylesheet" href="../css/bootstrap.min.css">
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>


<style>
	input {
		#-webkit-text-security: disc;
		#text-security: disc;
	}
</style>

<head></head>

<body onload="startTime()">
	<div class="container-fluid">
		<form autocomplete="off" class="form-horizontal" action="timeclock.php" method="post">
			<fieldset>
				<br /><br />
				<br /><br />
				
				<div id="txt" align="center" style="font-size:50pt; margin-top:-50px; margin-bottom:50px;"></div>
				
				<!-- Script to show running time -->
				<script>
					function startTime() {
					  	var today = new Date();
					  	var h = today.getHours();
					  	var m = today.getMinutes();
					  	var s = today.getSeconds();
					  	m = checkTime(m);
					  	s = checkTime(s);
					  	document.getElementById('txt').innerHTML = h + ":" + m + ":" + s;
					  	var t = setTimeout(startTime, 500);
				  	}
			
					function checkTime(i) {
						if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
						return i;
					}
				</script>
			
				<!-- Employee ID Input -->
	  			<div align="center">
	  				<input style="font-size: 40pt; height:100px; width:400px;" id="EmployeeIDinput" name="EmployeeIDinput" type="tel" placeholder="Employee ID #" class="form-control input-md" required=""/>
	  				<span class="help-block">Enter ID # Above</span>
  				</div>
		
		
				<!-- Button -->
				<div align = "center">
					<div align="center">
		    			<button style="height:200px; width:200px; font-size: 40pt;" id="SubmitID-btn" name="SubmitID-btn" class="btn btn-primary">Submit</button>
	  				</div>
				</div>
	
			</fieldset>
		</form>
	</div>
</body>
</html>
