<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	
	include("QueryFunctions.php");
	
	$func = "default";
	$TransID='';
	$Date='';
	$CardType='';
	$MaxPrice='';
	$LoginName='';
	$Show='';
	if(isset($_GET['func'])) { $func=$_GET['func']; }
	if(isset($_GET['TransID'])) { $TransID=$_GET['TransID']; }
	if(isset($_GET['Date'])) { $Date=$_GET['Date']; }
	if(isset($_GET['CardType'])) { $CardType=$_GET['CardType']; }
	if(isset($_GET['MaxPrice'])) { $MaxPrice=$_GET['MaxPrice']; }
	if(isset($_GET['LoginName'])) { $LoginName=$_GET['LoginName']; }
	if(isset($_GET['Show'])) { $Show=$_GET['Show']; }
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
				<p class="sign" >Sales Portal</p><br>
				<a id="submit-button" href="SaleForm.php">Process New Sale</a>
				<button id="backbtn"  onclick="window.location.href ='index.html'">Back To Main Menu</button>
				<?php
				if ($func == "Sale"){
					$photos = getPhotosForSale("");
					$selectedPhotos= array();
					while ($row = mysqli_fetch_row ($photos)){
						$p = "photo" . $row[0];
						if( isset($_GET[$p]) ) { array_push($selectedPhotos, $row[0]); }
					}
					createTransaction($selectedPhotos);
				}
				?>
				<form action="SalesPortal.php">
					<b>Search Transaction Database:</b><br><br>
					<div class = "search">
						<div class="search-input">
							<label name="TransID">TransID: </label><input type="text" name="TransID" size=6 value = "<?PHP echo "$TransID"; ?>" >
							<label name="Date">Date: </label><input type="text" name="Date" size=7 value = "<?PHP echo "$Date"; ?>" >
							<label name="CardType">CardType: </label><select name="CardType">
								<option value = "" <?PHP if ($CardType==''){ echo " selected=\"selected\"";} ?>></option>
								<option value = "American Express" <?PHP if ($CardType=='American Express'){ echo " selected=\"selected\"";} ?>>American Express</option>
								<option value = "Visa" <?PHP if ($CardType=='Visa'){ echo " selected=\"selected\"";} ?>>Visa</option>
								<option value = "Discover"<?PHP if ($CardType=='Discover'){ echo " selected=\"selected\"";} ?>>Discover</option>
							</select>
							<br>
							<label name="MaxPrice">MaxPrice: </label><input type="text" name="MaxPrice" size=6 value = <?PHP echo "$MaxPrice" ?> >
							<label name="LoginName">LoginName: </label>
							<select name = "LoginName">
								<?PHP
									$table = getLoginNames();
									echo "<option value = \"\""; if ($LoginName==""){ echo " selected=\"selected\"";} echo "></option>";
									while($row = mysqli_fetch_row ($table)){
										echo "<option value=\"$row[0]\""; if ($LoginName==$row[0]){ echo " selected=\"selected\"";} echo ">$row[0]</option>";
									} 
								?>
							</select>
							<label name="Show">Limit Show: </label>
							<select name = "Show">
								<option value = ""<?PHP if ($Show==''){ echo " selected=\"selected\"";}?>></option>
								<option value=5 <?PHP if ($Show==5){ echo " selected=\"selected\"";}?>>5</option>
								<option value=25 <?PHP if ($Show==25){ echo " selected=\"selected\"";}?>>25</option>
								<option value=50 <?PHP if ($Show==50){ echo " selected=\"selected\"";}?>>50</option>
								<option value=100 <?PHP if ($Show==100){ echo " selected=\"selected\"";}?>>100</option>
							</select>
						</div>
						<div class="search-submit">
							<input type="submit" name="func" id="submit-button" value="Search">
						</div>
					</div>
				</form><br>
				<?PHP 
					$result = searchTransaction();
					displayTable("Transactions", $result);
				?>
			</div>
		</div>
	</body>
</html>