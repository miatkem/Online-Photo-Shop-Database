<?php
	# -------------------- HEADER / ERROR CATCH -------------------- #
	//Error Catcher
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	
	include("QueryFunctions.php");
	# ------------------------- BODY STARTS ------------------------- #
	$func="view";
	if (isset($_GET['func'])){
		$func = $_GET['func'];
	}
	echo "
	<head>
		<link rel=\"stylesheet\" href=\"css/loginstyle.css\">
		<link rel=\"stylesheet\" href=\"css/servicestyle.css\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />
	</head>

	<body>
		<br><br><br>
		<div class = \"main\" id=\"wrapper\" name=\"wrapper\">
			<div class = \"form1\">
				<form class=\"actionform\" action=\"QueryPortal.php\">
					<p class=\"sign\" >Deliverable 3: CS331</p><br>
					
					<label for=\"func\">View Full Tables or Perform Query:</label>
					<select id=\"fdropdown\" name = \"func\" selected = \"$func\" onchange = \"this.form.submit()\">
						<option value=\"view\""; if ($func=="view"){ echo " selected=\"selected\"";}  echo ">View Tables</option>
						<option value=\"query\""; if ($func=="query"){ echo " selected=\"selected\"";}  echo ">Perform Query</option>
					</select><br>";
					
					if($func == 'query')
					{
						$q=0;
						if (isset($_GET['qnum'])){
							$q = $_GET['qnum'];
						}
	
						echo "<label for=\"qnum\">Choose a Query/Command:</label>
						<select id=\"qdropdown\" name = \"qnum\" selected = \"$q\">
							<option value=\"1\""; if ($q==1){ echo " selected=\"selected\"";} echo ">Query 1</option>
							<option value=\"2\""; if ($q==2){ echo " selected=\"selected\"";} echo ">Query 2</option>
							<option value=\"3\""; if ($q==3){ echo " selected=\"selected\"";} echo ">Query 3</option>
							<option value=\"4\""; if ($q==4){ echo " selected=\"selected\"";} echo ">Query 4</option>
							<option value=\"5\""; if ($q==5){ echo " selected=\"selected\"";} echo ">Query 5</option>
							<option value=\"6\""; if ($q==6){ echo " selected=\"selected\"";} echo ">Query 6</option>
							<option value=\"7\""; if ($q==7){ echo " selected=\"selected\"";} echo ">Query 7</option>
							<option value=\"8\""; if ($q==8){ echo " selected=\"selected\"";} echo ">Query 8</option>
							<option value=\"9\""; if ($q==9){ echo " selected=\"selected\"";} echo ">Command 9</option>
							<option value=\"10\""; if ($q==10){ echo " selected=\"selected\"";} echo ">Command 10</option>
							<option value=\"11\""; if ($q==11){ echo " selected=\"selected\"";} echo ">Query 11</option>
							<option value=\"12\""; if ($q==12){ echo " selected=\"selected\"";} echo ">Query 12</option>
							<option value=\"13\""; if ($q==13){ echo " selected=\"selected\"";} echo ">Query 13</option>
							<option value=\"14\""; if ($q==14){ echo " selected=\"selected\"";} echo ">Query 14</option>
						</select><br>
						<input type=\"submit\" id=\"submit-button\" value=\"Create Query\">";
					}
					
					if($func == 'view')
					{
						$table="";
						if (isset($_GET['table'])){
							$table = $_GET['table'];
						}
	
						echo "<label for=\"table\">Choose a Table:</label>
						<select id=\"tdropdown\" name = \"table\" selected = \"$table\">
							<option value=\"Abstract\""; if ($table=="Abstract"){ echo " selected=\"selected\"";} echo ">Abstract</option>
							<option value=\"Customer\""; if ($table=="Customer"){ echo " selected=\"selected\"";} echo ">Customer</option>
							<option value=\"Influences\""; if ($table=="Influences"){ echo " selected=\"selected\"";} echo ">Influences</option>
							<option value=\"Landscape\""; if ($table=="Landscape"){ echo " selected=\"selected\"";} echo ">Landscape</option>
							<option value=\"Location\""; if ($table=="Location"){ echo " selected=\"selected\"";} echo ">Location</option>
							<option value=\"Model\""; if ($table=="Model"){ echo " selected=\"selected\"";} echo ">Model</option>
							<option value=\"Models\""; if ($table=="Models"){ echo " selected=\"selected\"";} echo ">Models</option>
							<option value=\"Photo\""; if ($table=="Photo"){ echo " selected=\"selected\"";} echo ">Photo</option>
							<option value=\"Photographer\""; if ($table=="Photographer"){ echo " selected=\"selected\"";} echo ">Photographer</option>
							<option value=\"Portrait\""; if ($table=="Portrait"){ echo " selected=\"selected\"";} echo ">Portrait</option>
							<option value=\"Transaction\""; if ($table=="Transaction"){ echo " selected=\"selected\"";} echo ">Transaction</option>
						</select><br>
						<input type=\"submit\" id=\"submit-button\" value=\"View Table\">";
						
						if (isset($_GET['table'])){
							$title = $table;
							$result = selectTable($table);
							
							displayTable ($title, $result);
						}
					}
					
			echo "</div>";
		

	if (isset($_GET['qnum']) and $func == "query")
	{
		$val ="";
		$val2 ="";
		if (isset($_GET['val'])) {$val = $_GET['val'];}
		if (isset($_GET['val2'])) {$val2 = $_GET['val2'];}
		echo "<p class=\"sign\" style = \"font-size:18; padding-top:0px;\"> Query $q";
		
		//QUERY 1
		if($q==1)
		{
			echo "
				<p>List the customers who spend more than <input type=\"text\" name=\"val\" value = \"$val\" size=6>$ for the photos.</p>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 1\">";
				
			if(isset($_GET['val']) and isset($_GET['query']) and $_GET['query'] == "Execute Query 1")
			{
				$title = "Customers";
				$result = query1($val);
				
				displayTable ($title,$result);
			}
		}
		
		//QUERY 2
		if($q==2)
		{
			echo "
				<p>List photos which 
					<select id=\"vdropdown\" name = \"val\" selected = \"$val\">
						<option value=\"were\""; if ($val=="were"){ echo " selected=\"selected\"";} echo ">were</option>
						<option value=\"were_not\""; if ($val=="were_not"){ echo " selected=\"selected\"";} echo ">were not</option> 
					</select>
				bought.</p><br>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 2\">";
				
			if(isset($_GET['val']) and isset($_GET['query']) and $_GET['query'] == "Execute Query 2")
			{
				$title = "Photos";
				$result = query2($val);
				
				displayTable ($title,$result);
			}
		}
		
		//QUERY 3
		if($q==3)
		{
			$table = getModels();
			echo "
				<p>List customers who bought all photos (portraits) in which the model 
					<select id=\"vdropdown\" name = \"val\" selected = \"$val\">";
					while($row = mysqli_fetch_row ($table)){
						echo "<option value=\"$row[0]\""; if ($val==$row[0]){ echo " selected=\"selected\"";} echo ">$row[0]</option>";
					}
					echo "</select> modeled.</p>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 3\">";
				
			if(isset($_GET['val']) and isset($_GET['query']) and $_GET['query'] == "Execute Query 3")
			{
				$title = "Customers";
				$result = query3($val);
				
				displayTable ($title,$result);
			}
		}
		
		
		//QUERY 4 
		if($q==4)
		{
			echo "<p>List photographers who influenced exclusively photographers who are US citizens.</p>
			<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 4\">";
			
			if(isset($_GET['query']) and $_GET['query'] == "Execute Query 4")
			{
				$title = "Photographers";
				$result = query4();
				displayTable ($title,$result);
			}
		}
		
		//QUERY 5
		if($q==5)
		{
			echo "
				<p>List photographers which took only
				<select id=\"qdropdown\" name = \"val\" selected = \"$q\">
					<option value=\"Portrait\""; if ($val=="Portrait"){ echo " selected=\"selected\"";} echo ">Portrait</option>
					<option value=\"Landscape\""; if ($val=="Landscape"){ echo " selected=\"selected\"";} echo ">Landscape</option>
					<option value=\"Abstract\""; if ($val=="Abstract"){ echo " selected=\"selected\"";} echo ">Abstract</option>
				</select>
				photos.</p>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 5\">";
				
			if(isset($_GET['val']) and isset($_GET['query']) and $_GET['query'] == "Execute Query 5")
			{
				$title = "Photographers";
				$result = query5($val);
				displayTable ($title,$result);
			}
		}
		
		//QUERY 6
		if($q==6)
		{
			echo "
				<p>List transactions (transID) which contain more than <input type=\"text\" name=\"val\" value = \"$val\" size=2> photos.</p>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 6\">";
			
			if(isset($_GET['val']) and isset($_GET['query']) and $_GET['query'] == "Execute Query 6")
			{
				$title = "Transactions";
				$result = query6($val);
				displayTable ($title,$result);
			}
		}
		
		//QUERY 7
		if($q==7)
		{
			$table = getPhotographers();
			echo "
				<p>List models who modeled in all photos taken by photographer 
				<select id=\"vdropdown\" name = \"val\" selected = \"$val\">";
					while($row = mysqli_fetch_row ($table)){
						echo "<option value=\"$row[0]\""; if ($val==$row[0]){ echo " selected=\"selected\"";} echo ">$row[0]</option>";
					}
					echo "</select>.</p>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 7\">";
				
			if(isset($_GET['val']) and isset($_GET['query']) and $_GET['query'] == "Execute Query 7")
			{
				$title = "Models";
				$result = query7($val);
				displayTable ($title,$result);
			}
		}
		
		//QUERY 8
		if($q==8)
		{
			echo "
				<p>Rank the photographers by the total cost (sum of prices) of the photos they took.</p>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 8\">";
				
				if(isset($_GET['query']) and isset($_GET['query']) and $_GET['query'] == "Execute Query 8")
				{
					$title = "Photographers";
					$result = query8();
					displayTable ($title,$result);
				}
		}
		
		//QUERY 9
		if($q==9)
		{
			echo "
				<p>Delete from relation Photo the photo with photoID=<input type=\"text\" name=\"val\" value = \"$val\" size=6>.</p>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 9\">";
			
			if(isset($_GET['val'])  and isset($_GET['query']) and $_GET['query'] == "Execute Query 9")
			{
				$result = query9($val);
			}
		}
		
		//QUERY 10
		if($q==10)
		{
			echo "
				<p>Update the photographer name of the photo with photoID=<input type=\"text\" name=\"val\" value = \"$val\" size=6> to <input type=\"text\" name=\"val2\" value = \"$val2\" size=10>.</p>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 10\">";
			
			if(isset($_GET['val']) and isset($_GET['val2']) and isset($_GET['query']) and $_GET['query'] == "Execute Query 10")
			{
				$result = query10($val,$val2);
			}
		}
		
		//QUERY 11 
		if($q==11)
		{
			echo "
				<p>Compute total sales per customer.</p>
				<input type=\"submit\" id=\"submit-button\" name= \"query\" value=\"Execute Query 11\">";
			
			if(isset($_GET['query']) and $_GET['query'] == "Execute Query 11")
			{
				$title = "Customers";	
				$result = query11();
				displayTable ($title,$result);
			}
		}
		
		//QUERY 12 
		if($q==12)
		{
			echo "
				<p>Compute total sales per photographer sorted by photographer.</p>
				<input type=\"submit\" id=\"submit-button\" name=\"query\" value=\"Execute Query 12\">";
			
			if(isset($_GET['query']) and $_GET['query'] == "Execute Query 12")
			{
				$title = "Photographer";	
				$result = query12();
				displayTable ($title,$result);
			}
		}
		
		//QUERY 13 
		if($q==13)
		{
			echo "
				<p>Compute total sales by photo type (portrait, landscape etc.).</p>
				<input type=\"submit\" id=\"submit-button\" name=\"query\" value=\"Execute Query 13\">";
			
			if(isset($_GET['query']) and $_GET['query'] == "Execute Query 13")
			{
				$title = "Photo Type";	
				$result = query13();
				displayTable ($title,$result);
			}
		}
		
		//QUERY 14
		if($q==14)
		{
			echo "
				<p>Compute top <input type=\"text\" name=\"val\" value = \"$val\" size=2> dates (in a total sales per date list).</p>
				<input type=\"submit\" id=\"submit-button\" name=\"query\" value=\"Execute Query 14\">";
			
			if(isset($_GET['val']) and isset($_GET['query']) and $_GET['query'] == "Execute Query 14")
			{
				$title = "Top Dates";	
				$result = query14($val);
				displayTable ($title,$result);
			}
		}
	} 
	echo "</form><br><button class=\"logout\" id=\"logout\"  onclick=\"window.location.href ='index.html'\">Back To Main Menu</button>";
	#----------------------------  FOOTER  ---------------------------- #
?>