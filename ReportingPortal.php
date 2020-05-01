<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);

	include("sqlaccount.php");
	include("QueryFunctions.php");
	
	$db = mysqli_connect($hostname, $username, $password, $project);
	
	if (mysqli_connect_errno()){
		echo "Failed to connect: " . mysqli_connect_error();
		exit();
	}
	
	mysqli_select_db( $db, $project );
	
	$rep = "";
	if(isset($_GET['report'])) {
		$rep = $_GET['report'];
	}
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
				<form class="actionform" action="ReportingPortal.php">
					<p class="sign" >Reporting Portal</p><br>
					<select name="report">
						<option value = "rep1" <?PHP if($rep == "rep1"){echo "selected";}?>>Sales Per Date</option>
						<option value = "rep2" <?PHP if($rep == "rep2"){echo "selected";}?>>Sales Per Customer</option> 
						<option value = "rep3" <?PHP if($rep == "rep3"){echo "selected";}?>>Sales Per Photographer</option>
						<option value = "rep4" <?PHP if($rep == "rep4"){echo "selected";}?>>Sales Per Model</option>
						<option value = "rep5" <?PHP if($rep == "rep5"){echo "selected";}?>>Sales Per Photo Type</option>
						<option value = "rep6" <?PHP if($rep == "rep6"){echo "selected";}?>>Sales Per Location</option>
						<option value = "rep7" <?PHP if($rep == "rep7"){echo "selected";}?>>Sales Per Film Type</option>
					</select>
					<br>
					<input type="submit" id="submit-button" value="Generate Report"><br>
				</form>
				<button id="backbtn"  onclick="window.location.href ='index.html'">Back To Main Menu</button>
				<?PHP
				if($rep!="") {
					if($rep=='rep1'){
						$title = "Sales Per Date";
						$result = report1();
						displayTable ($title,$result);
					}
					
					elseif($rep=='rep2'){
						$title = "Sales Per Customer";
						$result = report2();
						displayTable ($title,$result);
					}
					
					elseif($rep=='rep3'){
						$title = "Sales Per Photographer";
						$result = report3();
						displayTable ($title,$result);
					}
					
					elseif($rep=='rep4'){
						$title = "Sales Per Model";
						$result = report4();
						displayTable ($title,$result);
					}
					
					elseif($rep=='rep5'){
						$title = "Sales Per Photo Type";
						$result = report5();
						displayTable ($title,$result);
					}
					
					elseif($rep=='rep6'){
						$title = "Sales Per Location";
						$result = report6();
						displayTable ($title,$result);
					}
					
					elseif($rep=='rep7'){
						$title = "Sales Per Film Type";
						$result = report7();
						displayTable ($title,$result);
					}
				}
				?>
			</div>
		</div>
	</body>
</html>