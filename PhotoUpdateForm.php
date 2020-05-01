<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	
	include("QueryFunctions.php");
	
	$PhotoID = $_GET['update'];
	$Photo=getPhoto($PhotoID);
	$photographers = getPhotographersWithPID();
	$location = getLocations();
?>

<html>
	<head>
		<link rel="stylesheet" href="css/loginstyle.css">
		<link rel="stylesheet" href="css/servicestyle.css">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>

	<body>
		<br><br><br>
		<div class = "main" id="wrapper" name="wrapper">
			<div class = "form1">
				<form action="PhotoPortal.php">
					<p class="sign" >Update Photo</p><br>
					<div class="photo-form-input">
						<label for="PhotoID">PhotoID:</label><input type="text" name="PhotoID" value = <?php echo $Photo[0]?> size=7 class = "new-form-input" readonly="readonly"><br>
						<label for="Speed">Speed:</label><input type="text" name="Speed" value = <?php echo $Photo[1]?> size=7 class = "new-form-input"><br>
						<label for="Film">Film:</label><input type="text" name="Film" value = <?php echo $Photo[2]?> size=7 class = "new-form-input"><br>
						<label for="F-Stop">F-Stop:</label><input type="text" name="F-Stop" value = <?php echo $Photo[3]?> size=7 class = "new-form-input"><br>
						<label for="Color">Color/B&W:</label>
						<select name = "Color" class = "new-form-input" selected=<?php echo $Photo[4]?>>
							<?php
								echo "<option value =\"Color\" ";
								if($Photo[4]=="Color"){echo "selected";}
								echo ">Color</option>";
								echo "<option value =\"B&W\" ";
								if($Photo[4]=="B&W"){echo "selected";}
								echo ">B&W</option>";
							?>
						</select><br>
						<label for="Resolution">Resolution:</label><input type="text" name="Resolution" value = <?php echo $Photo[5]?> size=8 class = "new-form-input"><br>
						<label for="Price">Price:</label><input type="text" name="Price" value = <?php echo $Photo[6]?> size=7 class = "new-form-input"><br>
						<label for="Date">Date:</label><input type="text" name="Date" value = <?php echo $Photo[7]?> size=9 class = "new-form-input"><br>
						<label for="TransID">TransID:</label><input type="text" name="TransID" size=6 class = "new-form-input" value = <?php echo $Photo[8]?>><br>
						<label for="Photographer">Photographer:</label>
						<select name = "PID" class = "new-form-input" selected = <?php echo $Photo[9]?>>
						<?php
							while($row = mysqli_fetch_row ($photographers)){
								echo "<option value=\"$row[1]\" ";
								if($row[1]==$Photo[9]){echo "selected";}
								echo ">$row[1], $row[0]</option>";
							}
						?>
						</select><br>					
					</div>
					<button type="submit" name="func" value="Update" id = "submit-button">Update Information</button>
				</form>
			</div>
		</div>
	</body>
</html>