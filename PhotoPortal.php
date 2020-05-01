<?php
	# -------------------- HEADER / ERROR CATCH -------------------- #
	//Error Catcher
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	
	include("QueryFunctions.php");
	
	# ------------------------- BODY STARTS ------------------------- #
	$func = "default";
	$PID=0;
	$Color='';
	$Type='';
	$MaxPrice='';
	$PhotoID='';
	$Date='';
	$TransID='';
	$Show='';
	if(isset($_GET['func'])) { $func=$_GET['func']; }
	if(isset($_GET['PID'])) { $PID=$_GET['PID']; }
	if(isset($_GET['Color'])) { $Color=$_GET['Color']; }
	if(isset($_GET['Type'])) { $Type=$_GET['Type']; }
	if(isset($_GET['MaxPrice'])) { $MaxPrice=$_GET['MaxPrice']; }
	if(isset($_GET['PhotoID'])) { $PhotoID=$_GET['PhotoID']; }
	if(isset($_GET['Date'])) { $Date=$_GET['Date']; }
	if(isset($_GET['TransID'])) { $TransID=$_GET['TransID']; }
	if(isset($_GET['Show'])) { $Show=$_GET['Show']; }
	
	echo "
<html>
	<head>
		<link rel=\"stylesheet\" href=\"css/loginstyle.css\">
		<link rel=\"stylesheet\" href=\"css/servicestyle.css\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />
	</head>

	<body>
		<br><br><br>
		<div class = \"main\" id=\"wrapper\" name=\"wrapper\">
			<div class = \"form1\">
				<p class=\"sign\" >Photo Portal</p><br>
				<a id=\"submit-button\" href=\"PhotoForm.php\">Add New Photo</a>
				<button id=\"backbtn\"  onclick=\"window.location.href ='index.html'\">Back To Main Menu</button>
				<form action=\"PhotoPortal.php\">
					<b>Search Photo Database:</b><br><br>
					<div class = \"search\">
						<div class=\"search-input\">
							PhotoID: <input type=\"text\" name=\"PhotoID\" value = \"$PhotoID\" size=6>
							Date Taken: <input type=\"text\" name=\"Date\" value = \"$Date\" size=7>
							TransID: <input type=\"text\" name=\"TransID\" value = \"$TransID\" size=6>
							Photographer: ";
							$table = getPhotographersWithPID(); echo "
							<select name = \"PID\">";
								echo "<option value = \"\""; if ($PID==0){ echo " selected=\"selected\"";} echo "></option>";
								while($row = mysqli_fetch_row ($table)){
									echo "<option value=\"$row[1]\""; if ($PID==$row[1]){ echo " selected=\"selected\"";} echo ">$row[1], $row[0]</option>";
								}
							echo "</select>
							<br>
							Color/B&W: 
							<select name = \"Color\">
								<option value = \"\""; if ($Color==''){ echo " selected=\"selected\"";} echo "></option>
								<option value = \"Color\""; if ($Color=='Color'){ echo " selected=\"selected\"";} echo ">Color</option>
								<option value = \"B&W\""; if ($Color=='B&W'){ echo " selected=\"selected\"";} echo ">B&W</option>
							</select>
							Type:
							<select name = \"Type\">
								<option value = \"\""; if ($Color==''){ echo " selected=\"selected\"";} echo "></option>
								<option value=\"Portrait\""; if ($Type=="Portrait"){ echo " selected=\"selected\"";} echo ">Portrait</option>
								<option value=\"Landscape\""; if ($Type=="Landscape"){ echo " selected=\"selected\"";} echo ">Landscape</option>
								<option value=\"Abstract\""; if ($Type=="Abstract"){ echo " selected=\"selected\"";} echo ">Abstract</option>
							</select>
							MaxPrice: <input type=\"text\" name=\"MaxPrice\" value = \"$MaxPrice\" size=6>
							Limit Show:
							<select name = \"Show\">
								<option value = \"\""; if ($Show==''){ echo " selected=\"selected\"";} echo "></option>
								<option value=\"5\""; if ($Show==5){ echo " selected=\"selected\"";} echo ">5</option>
								<option value=\"25\""; if ($Show==25){ echo " selected=\"selected\"";} echo ">25</option>
								<option value=\"50\""; if ($Show==50){ echo " selected=\"selected\"";} echo ">50</option>
								<option value=\"100\""; if ($Show==100){ echo " selected=\"selected\"";} echo ">100</option>
							</select>
						</div>
						<div class=\"search-submit\">
							<input type=\"submit\" name=\"func\" id=\"submit-button\" value=\"Search\">
						</div>
					</div>
				</form><br>";
	
				if(isset($_GET['delete'])) //27	0.10	Kodak	4.60	Color	600x800	45.00	2020-04-18		10
				{ 
					$dPhotoID  = $_GET['delete']; 
					$flag=deletePhoto();
				}
				
				if($func == "New") //27	0.10	Kodak	4.60	Color	600x800	45.00	2020-04-18		10
				{ 
					createNewPhoto();
				}
				
				if($func == "Update") //27	0.10	Kodak	4.60	Color	600x800	45.00	2020-04-18		10
				{ 
					updatePhoto();
				}

				$result = searchPhoto();
				displayTableWithButtons($result);
				
				echo "
			</div>
		</div>
	</body>
</html>";
?>