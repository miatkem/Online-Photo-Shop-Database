<?php
	# -------------------- HEADER / ERROR CATCH -------------------- #
	//Error Catcher
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	
	include("QueryFunctions.php");
	
	# ------------------------- BODY STARTS ------------------------- #?>
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
				<p class="sign" >Sales Portal</p><br>
				<form action='SalesPortal.php'>
					<div>
						<label for="LoginName">LoginName:</label>
						<select name = "LoginName">
							<?PHP
								$table = getLoginNames();
								while($row = mysqli_fetch_row ($table)){
									echo "<option value=\"$row[0]\">$row[0]</option>";
								} 
							?>
						</select><br>
					</div>
					<div id="photo-catalog">
						<?PHP displayPhotosForSale(''); ?>
					</div>
					<div>
						<label for="Date">Today's Date:</label><input type="text" name="Date" value = "<?PHP date_default_timezone_set("America/New_York"); echo "" . date("Y-m-d") . ""; ?>" size=7 readonly="readonly">
						<label for="CardType">CardType: </label>
						<select name="CardType">
							<option value = "American Express">American Express</option>
							<option value = "Visa">Visa</option>
							<option value = "Discover">Discover</option>
						</select>
						<label for="CardNo">CardNo:</label><input type="text" name="CardNo" value = "" size=9>
						<label for="CardExpDate">CardExpDate:</label><input type="text" name="CardExpDate" value = "" size=6>
					</div>
				<button type="submit" name="func" value="Sale" id = "submit-button">Process Sale</button>
				</form>
			</div>
		</div>
	</body>
</html>