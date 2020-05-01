<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	
	include("QueryFunctions.php");
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
					<p class="sign" >Create Photo</p><br>
					<div class="photo-form-input">
						<label for="Speed">Speed:</label><input type="text" name="Speed" value = "ex. 0.01" size=7 class = "new-form-input"><br>
						<label for="Film">Film:</label><input type="text" name="Film" value = "ex. Kodak" size=7 class = "new-form-input"><br>
						<label for="F-Stop">F-Stop:</label><input type="text" name="F-Stop" value = "ex. 16.00" size=7 class = "new-form-input"><br>
						<label for="Color">Color/B&W:</label>
						<select name = "Color" class = "new-form-input">
							<option value = "Color">Color</option>
							<option value = "B&W">B&W </option>
						</select><br>
						<label for="Resolution">Resolution:</label><input type="text" name="Resolution" value = "ex. 1025x768" size=8 class = "new-form-input"><br>
						<label for="Price">Price:</label><input type="text" name="Price" value = "ex. 30.23" size=7 class = "new-form-input"><br>
						<label for="Date">Date:</label><input type="text" name="Date" value = "ex. 2020-03-13" size=9 class = "new-form-input"><br>
						<label for="Photographer">Photographer:</label>
						<select name = "PID" class = "new-form-input">
						<?php
							while($row = mysqli_fetch_row ($photographers)){
								echo "<option value=\"$row[1]\">$row[1], $row[0]</option>";
							}
						?>
						</select><br>
						
						<label for="Type">Type:</label>
						<select class = "new-form-input" id="typelist" name = "Type" onclick="F()">
							<option value="Portrait">Portrait</option>
							<option value="Landscape">Landscape</option>
							<option value="Abstract">Abstract</option>
						</select>
						
						<div id = "Portrait-Form">
							<label for="Headshot">Headshot:</label>
							<select name = "Head" class = "new-form-input">
								<option value = "Y">Yes</option>
								<option value = "N">No </option>
							</select>
						</div>
						
						<div id = "Abstract-Form">
							<label for="Comment">Comment:</label>
							<input type="text" name="Comment" value = "ex. An orange." size=20 class = "new-form-input">		
						</div>
						
						<div id = "Landscape-Form">
							<label for="LOCID">Location:</label>
							<select name = "LOCID" class = "new-form-input">
								<?php
									while($row = mysqli_fetch_row ($location)){
										echo "<option value=\"$row[0]\">$row[1], $row[2]</option>";
									}
								?>
							</select>
						</div>
					</div>
					<button type="submit" name="func" value="New" id = "submit-button">Add New Photo</button>
				</form>
			</div>
		</div>
	</body>
	<script>
		var p = document.getElementById("Portrait-Form");
		var a = document.getElementById("Abstract-Form");
		var l = document.getElementById("Landscape-Form");

		function F() {

			p.style.display = "none";
			a.style.display = "none";
			l.style.display = "none";

			if (document.getElementById("typelist").value=="Portrait"){
				p.style.display="block";
			}
			if (document.getElementById("typelist").value=="Abstract"){
				a.style.display="block";
			}
			if (document.getElementById("typelist").value=="Landscape"){
				l.style.display="block";
			}
		}  
	</script>
</html>