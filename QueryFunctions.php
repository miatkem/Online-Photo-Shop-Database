<?php
	//error catcher
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	
	//create sql connection
	include("sqlaccount.php");
	$db = mysqli_connect($hostname, $username, $password, $project);
	if (mysqli_connect_errno()){
		echo "Failed to connect: " . mysqli_connect_error();
		exit();
	}
	mysqli_select_db( $db, $project );
	
	//Customer functions
	function getLoginNames()
	{
		global $db;
		
		$sql = "SELECT LoginName
					FROM Customer";
					
					( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	//Photo functions
	function getPhotosForSale($type)
	{
		global $db;
		
		if($type != "") {
			$inType = " AND PhotoID IN (SELECT PhotoID FROM $type);";
		} else {$inType = "";}
		
		$sql = "SELECT *
					FROM Photo
					WHERE TransID IS NULL" . $inType;
					
					( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function getPrice($PhotoID)
	{
		global $db;
		
		$sql = "SELECT Price
					FROM Photo
					WHERE PhotoID = $PhotoID";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		$row = mysqli_fetch_row ($result);
		
		return $row[0];
	}
	
	function searchPhoto()
	{
		global $db;
		if(isset($_GET['PhotoID']) and $_GET['PhotoID']!=''){$PhotoID="PhotoID = " . $_GET['PhotoID'] . " AND";}
		else{$PhotoID='';}
		
		if(isset($_GET['Date']) and $_GET['Date']!=''){$Date="Date = '" . $_GET['Date'] . "' AND";}
		else{$Date='';}
		
		if(isset($_GET['TransID']) and $_GET['TransID']!=''){$TransID="TransID = " . $_GET['TransID'] . " AND";}
		else{$TransID='';}
		
		if(isset($_GET['PID']) and $_GET['PID']!=''){$PID="PID = " . $_GET['PID'] . " AND";}
		else{$PID='';}
		
		if(isset($_GET['Color']) and $_GET['Color']!=''){$Color="`Color/B&W` = '" . $_GET['Color'] . "' AND";}
		else{$Color='';}
		
		if(isset($_GET['Type']) and $_GET['Type']!=''){$Type="PhotoID IN (SELECT PhotoID FROM `" . $_GET['Type'] ."`)" . " AND";}
		else{$Type='';}
		
		if(isset($_GET['MaxPrice']) and $_GET['MaxPrice']!=''){$MaxPrice="Price <= " . $_GET['MaxPrice'] . " AND";}
		else{$MaxPrice='';}
		
		if(isset($_GET['Show']) and $_GET['Show']!=''){$Show="LIMIT " .  $_GET['Show'];}
		else{$Show='';}
		
		$sql = "SELECT *
					FROM Photo
					WHERE $PhotoID $Date $TransID $Color $MaxPrice $PID $Type";
		$sql = rtrim($sql);
		$sql = preg_replace('/AND$/', '', $sql);
		$sql = preg_replace('/WHERE$/', '', $sql);
		$sql = $sql . $Show;
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function createNewPhoto()
	{
		global $db;
		$PhotoID = getMaxPhotoID()+1;
		$Speed=$_GET["Speed"];
		$Film=$_GET["Film"];
		$FStop=$_GET["F-Stop"];
		$Color=$_GET["Color"];
		$Resolution=$_GET["Resolution"];
		$Price=$_GET["Price"];
		$Date=$_GET["Date"];
		$PID=$_GET["PID"];
		$Type = $_GET["Type"];
		
		$sql = "INSERT INTO Photo
						(`PhotoID`, `Speed`, `Film`, `F-Stop`, `Color/B&W`, `Resolution`, `Price`, `Date`, `TransID`, `PID`)
					VALUES 
						($PhotoID,$Speed,'$Film',$FStop,'$Color','$Resolution',$Price,'$Date',NULL,$PID)";
						
		if($Type == "Abstract") { 
			$Comment = $_GET["Comment"]; 
			$sql2= "INSERT INTO Abstract
							(`PhotoID`, `Comment`)
						VALUES
							($PhotoID,'$Comment')";
		} 
		else if($Type == "Landscape") { 
			$LOCID = $_GET["LOCID"]; 
			$sql2= "INSERT INTO Landscape
							(`PhotoID`, `LOCID`)
						VALUES
							($PhotoID,$LOCID)";
		}
		else if($Type == "Portrait") { 
			$Head = $_GET["Head"]; 
			$sql2= "INSERT INTO Portrait
							(`PhotoID`, `Head`)
						VALUES
							($PhotoID,'$Head')";
		}
		
		( $result1 = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		( $result2 = mysqli_query($db, $sql2) ) or die( mysqli_error($db) );

		return array($result1,$result2);
	}
	
	function updatePhoto()
	{
		global $db;
		
		$PhotoID=$_GET["PhotoID"];
		$Photo=getPhoto($PhotoID);
		
		if($_GET['Speed']!=$Photo[1]){$Speed="Speed = " . $_GET['Speed'] . ",";}
		else{$Speed='';}
		if($_GET['Film']!=$Photo[2]){$Film="Film = '" . $_GET['Film'] . "',";}
		else{$Film='';}
		if($_GET['F-Stop']!=$Photo[3]){$FStop="F-Stop = " . $_GET['F-Stop'] . ",";}
		else{$FStop='';}
		if($_GET['Color']!=$Photo[4]){$Color="Color/B&W = '" . $_GET['Color'] . "',";}
		else{$Color='';}
		if($_GET['Resolution']!=$Photo[5]){$Resolution="Resolution = '" . $_GET['Resolution'] . "',";}
		else{$Resolution='';}
		if($_GET['Price']!=$Photo[6]){$Price="Price = " . $_GET['Price'] . ",";}
		else{$Price='';}
		if($_GET['Date']!=$Photo[7]){$Date="Date = '" . $_GET['Date'] . "',";}
		else{$Date='';}
		if($_GET['TransID']!=$Photo[8]){$TransID="TransID = " . $_GET['TransID'] . ",";}
		else{$TransID='';}
		if($_GET['PID']!=$Photo[9]){$PID="PID = " . $_GET['PID'] . ",";}
		else{$PID='';}
		
		$sql="UPDATE Photo SET $Speed $Film $FStop $Color $Resolution $Price $Date $TransID $PID";
		$sql = rtrim($sql);
		$sql = preg_replace('/,$/', '', $sql);
		$sql = preg_replace('/UPDATE Photo SET$/', '', $sql);
		
		if(strlen($sql)>3)
		{
			$sql = $sql . " WHERE PhotoID = $PhotoID";
		
			( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
			return "success";
		}
		
		return "nothing changed";
	}
	
	function getPhoto($PhotoID)
	{
		global $db;
		
		$sql = "SELECT *
					FROM Photo
					WHERE PhotoID=$PhotoID";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		$row = mysqli_fetch_row ($result);
		
		return $row;
	}
	
	function deletePhoto()
	{
		global $db;
		
		if(isset($_GET['delete']) and $_GET['delete']!='')
		{
			$PhotoID=$_GET['delete'];
		}
		else { $PhotoID=''; }
		
		$sql = "DELETE FROM Photo WHERE PhotoID=$PhotoID";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function getMaxPhotoID()
	{
		global $db;
		
		$sql = "SELECT MAX(PhotoID)
					FROM Photo";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		$row = mysqli_fetch_row ($result);
		
		return $row[0];
	}
	
	//transaction functions
	function searchTransaction()
	{
		global $db;
		if(isset($_GET['TransID']) and $_GET['TransID']!=''){$TransID="TransID = " . $_GET['TransID'] . " AND";}
		else{$TransID='';}
		
		if(isset($_GET['Date']) and $_GET['Date']!=''){$TDate="TDate = '" . $_GET['Date'] . "' AND";}
		else{$TDate='';}
		
		if(isset($_GET['CardType']) and $_GET['CardType']!=''){$CardType="CardType = '" . $_GET['CardType'] . "' AND";}
		else{$CardType='';}
		
		if(isset($_GET['LoginName']) and $_GET['LoginName']!=''){$LoginName="LoginName = '" . $_GET['LoginName'] . "' AND";}
		else{$LoginName='';}
		
		if(isset($_GET['MaxPrice']) and $_GET['MaxPrice']!=''){$MaxPrice="TotalAmount <= " . $_GET['MaxPrice'] . " AND";}
		else{$MaxPrice='';}
		
		if(isset($_GET['Show']) and $_GET['Show']!=''){$Show="LIMIT " .  $_GET['Show'];}
		else{$Show='';}
		
		$sql = "SELECT *
					FROM Transaction
					WHERE $TransID $TDate $CardType $LoginName $MaxPrice";
		$sql = rtrim($sql);
		$sql = preg_replace('/AND$/', '', $sql);
		$sql = preg_replace('/WHERE$/', '', $sql);
		$sql = $sql . $Show;
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function createTransaction($photos)
	{
		global $db;
		$TransID = getMaxTransID()+1;
		$TDate=$_GET["Date"];
		$CardType=$_GET["CardType"];
		$CardNo=$_GET["CardNo"];
		$CardExpDate=$_GET["CardExpDate"];
		$LoginName=$_GET["LoginName"];
		$TotalAmount = 0;
		$maxPhotoID = getMaxPhotoID();
		
		foreach ($photos as $p) {
			$TotalAmount = $TotalAmount + getPrice($p);
		}
		
		$sql = "INSERT INTO Transaction
						(`TransID`, `TDate`, `CardNo`,`CardType`, `CardExpDate`, `TotalAmount`, `LoginName`)
					VALUES 
						($TransID,'$TDate','$CardNo','$CardType','$CardExpDate',$TotalAmount,'$LoginName')";
						
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		
		foreach ($photos as $p) {
			$sql="UPDATE Photo 
					SET TransID=$TransID
					WHERE PhotoID = $p";
			( $tempRes = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		}
		
		return array($result);
	}

	function getMaxTransID()
	{
		global $db;
		$sql = "SELECT MAX(TransID)
					FROM Transaction";
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		$row = mysqli_fetch_row ($result);
		return $row[0];
	}
	
	//location functions
	function getLocations()
	{
		global $db;
		$sql = "SELECT LOCID, Place, Country
					FROM Location";
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		return $result;
	}
	
	//model functions
	function getModels()
	{
		global $db;
		
		$sql = "SELECT MName
					FROM Model";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	//photographer functions
	function getPhotographers()
	{
		global $db;
		
		$sql = "SELECT PName
					FROM Photographer";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function getPhotographer($pid)
	{
		global $db;
		
		$sql = "SELECT PName
					FROM Photographer
					WHERE PID = $pid";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		$row = mysqli_fetch_row ($result);
		return $row[0];
	}
	
	function getPhotographersWithPID()
	{
		global $db;
		
		$sql = "SELECT PName, PID
					FROM Photographer";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	//specific requested query functions
	function query1($val)
	{
		global $db;
		
		$sql = "SELECT CName, LoginName
					FROM Customer
					WHERE LoginName IN (
						SELECT LoginName 
						FROM Transaction
						WHERE TotalAmount > $val)";
					
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function query2($val)
	{
		global $db;
		
		if ($val=="were_not")
		{
			$sql = "SELECT *
						FROM Photo
						WHERE TransID IS NULL";
		}
		else if ($val=="were")
		{
			$sql = "SELECT *
						FROM Photo
						WHERE TransID > 0";
		}
					
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function query3($val)
	{
		global $db;
		
		if($val=="Deacon O'Sullivan")
		{
			$val = "Deacon O\\' Sullivan";
		}
		
		$sql = "SELECT g.LoginName, c.CName
					FROM
						(SELECT t.LoginName, count(p.PhotoID) as num, ttt.num as num2
						FROM Transaction t
						LEFT JOIN Photo p on t.TransID=p.TransID
						LEFT JOIN (
							SELECT  tt.LoginName, count(pp.PhotoID) as num
							FROM Transaction tt
							LEFT JOIN Photo pp on tt.TransID=pp.TransID
							WHERE pp.PhotoID IN 
								(SELECT PhotoID
								FROM Models
								WHERE MID = 
									(SELECT MID 
									FROM Model 
									WHERE MName = '$val'))
							GROUP BY tt.LoginName
						) ttt ON t.LoginName = ttt.LoginName
						GROUP BY t.LoginName) g
					LEFT JOIN Customer c on g.LoginName=c.LoginName
					WHERE g.num=g.num2;";

		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function query4()
	{
		global $db;
		
		$sql = "SELECT PID, PName
					FROM Photographer
					LEFT JOIN
						(SELECT a.EPID, COUNT(a.RPID) as numAmer, b.numInf
						FROM Influences a
						LEFT JOIN (
							SELECT EPID, COUNT(RPID) as numInf
							FROM Influences
							GROUP BY EPID
							) b ON b.EPID = a.EPID
						WHERE a.RPID IN (
							SELECT PID
							FROM Photographer
							WHERE PNationality='American')
						GROUP BY a.EPID) f ON f.EPID = PID
					WHERE f.numAmer = f.numInf;";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function query5($val)
	{
		global $db;
		
		$sql = "SELECT a.PID, a.PName
					FROM Photographer a
					LEFT JOIN
						(SELECT PID, COUNT(*) as numPhotos
						FROM Photo
						GROUP BY PID) b ON b.PID = a.PID
					LEFT JOIN
						(SELECT bb.PID, COUNT(aa.PhotoID) as numTypePhotos
						FROM $val aa
						LEFT JOIN Photo bb ON aa.PhotoID = bb.PhotoID
						GROUP BY bb.PID) c ON c.PID = a.PID
					WHERE b.numPhotos =c.numTypePhotos;";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function query6($val)
	{
		global $db;
		
		$sql = "SELECT t.TransID, t.TDate, t.LoginName, a.PhotoCount
					FROM Transaction t
					LEFT JOIN
						(SELECT TransID, count(*) as PhotoCount
						FROM Photo
						WHERE TransID IS NOT NULL 
						GROUP BY TransID) a ON a.transID = t.transID
					WHERE a.PhotoCount>$val;";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function query7($val)
	{
		global $db;
		//every picutres the model was in was taken by the photographer
		$sql = "SELECT a.MID, a.MName
					FROM Model a
					LEFT JOIN(
						SELECT MID, count(PhotoID) as numPhotosByPhotographer
						FROM Models
						WHERE PhotoID IN
							(SELECT PhotoID
							FROM Photo
							WHERE PID = (
								SELECT PID
								FROM Photographer
								WHERE PName = '$val'))
						GROUP BY MID) b on b.MID=a.MID
					LEFT JOIN(
						SELECT MID, count(PhotoID) as numPhotos
						FROM Models m
						GROUP BY m.MID) c on c.MID=a.MID
					WHERE b.numPhotosByPhotographer=c.numPhotos;";
		//the model is every picture the photographer took				
		$sql = "SELECT a.MID, a.MName
					FROM Model a
					LEFT JOIN(
						SELECT MID, count(PhotoID) AS photosWithPhotographer
						FROM Models 
						WHERE PhotoID in (
							SELECT PhotoID
							FROM Photo
							WHERE PID = (
								SELECT PID
								FROM Photographer
								WHERE PName = '$val'))
						GROUP BY MID) b ON b.MID=a.MID 
					WHERE b.photosWithPhotographer = (
							SELECT count(PhotoID)
							FROM Photo
							WHERE PID = (
								SELECT PID
								FROM Photographer
								WHERE PName = '$val'));";
					
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function query8()
	{
		global $db;
		
		$sql = "SELECT b.PName, a.PID, sum(a.price)
					FROM Photo a
					LEFT JOIN ( 
						SELECT PName, PID
						FROM Photographer) b ON b.PID=a.PID
					GROUP BY a.PID
					ORDER BY sum(a.price) DESC";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	function query9($val)
	{
		global $db;
		
		$sql = "DELETE FROM Photo WHERE PhotoID=$val";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
	}
	
	function query10($val, $val2)
	{
		global $db;

		$sql = "UPDATE Photographer
					SET PName = '$val2'
					WHERE PID = (
						SELECT PID
						FROM Photo
						WHERE PhotoID = $val);";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
	}
	
	function query11()
	{
		global $db;
		
		$sql = "SELECT b.CName, a.LoginName, count(*) as '# of Transactions', sum(a.TotalAmount) as 'Total Sales $'
					FROM Transaction a
					LEFT JOIN ( 
						SELECT CName, LoginName
						FROM Customer) b ON b.LoginName=a.LoginName
					GROUP BY a.LoginName
					ORDER BY sum(a.TotalAmount) DESC";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		
		return $result;
	}
	
	function query12()
	{
		global $db;
		
		$sql = "SELECT a.PID, b.PName, count(TransID) as '# of Photos Sold', sum(Price) as 'Total Sales $'
					FROM Photo a
					LEFT JOIN ( 
						SELECT PID, PNAME
						FROM Photographer) b on a.PID=b.PID
					WHERE TransID IS NOT NULL
					GROUP BY a.PID
					ORDER BY sum(Price) DESC;";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		
		return $result;
	}
	
	function query13()
	{
		global $db;
		
		$sql = "(SELECT 'Landscape' as 'Photo Type', count(TransID) as '# of Photos Sold', sum(Price) as 'Total Sales $'
					FROM Photo a
					WHERE TransID IS NOT NULL AND PhotoID IN (
						SELECT PhotoID
						FROM Landscape)
					GROUP BY 'Photo Type'
					ORDER BY sum(Price) DESC)
					
					UNION
					
					(SELECT 'Portrait' as 'Photo Type', count(TransID) as '# of Photos Sold', sum(Price) as 'Total Sales $'
					FROM Photo a
					WHERE TransID IS NOT NULL AND PhotoID IN (
						SELECT PhotoID
						FROM Portrait)
					GROUP BY 'Photo Type'
					ORDER BY sum(Price) DESC)
					
					UNION
					
					(SELECT 'Abstract' as 'Photo Type', count(TransID) as '# of Photos Sold', sum(Price) as 'Total Sales $'
					FROM Photo a
					WHERE TransID IS NOT NULL AND PhotoID IN (
						SELECT PhotoID
						FROM Abstract)
					GROUP BY 'Photo Type'
					ORDER BY sum(Price) DESC);";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		
		return $result;
	}
	
	function query14($val)
	{
		global $db;
		
		$sql = "SELECT TDate, count(TransID) as '# of Transactions', sum(TotalAmount) as 'Total Sales $'
					FROM Transaction
					GROUP BY TDate
					ORDER BY TDate DESC
					LIMIT $val;";
		
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		
		return $result;
	}
	
	//reporting portal functions -- many are similiar if not the same as previous queries
	function report1()
	{
		global $db;
		
		$sql = "SELECT TDate, count(TransID) as '# of Transactions', sum(TotalAmount) as 'Total Sales $'
					FROM Transaction
					GROUP BY TDate
					ORDER BY TDate DESC";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		
		return $result;
	}
	
	function report2()
	{
		return query11();
	}
	
	function report3()
	{
		return query12();
	}
	
	function report4()
	{
		global $db;
		
		$sql = "SELECT a.MID, a.MName, count(c.TransID) as '# of Transactions', sum(c.Price) as 'Total Sales $'
					FROM Model a
					LEFT JOIN(
						SELECT MID, PhotoID
						FROM Models) b on b.MID=a.MID
					LEFT JOIN(
						SELECT PhotoID, TransID, Price
						FROM Photo
					) c on c.PhotoID = b.PhotoID
					WHERE c.TransID IS NOT NULL
					GROUP BY a.MID";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		
		return $result;
	}
	
	function report5()
	{
		return query13();
	}
	
	function report6()
	{
		global $db;
		
		$sql = "SELECT b.LOCID, c.Place, count(a.TransID) as '# of Transactions' , sum(a.price) as 'Total Sales $'
				  FROM Photo a
				  LEFT JOIN(
					SELECT PhotoID,LOCID
				    FROM Landscape) b ON a.PhotoID=b.PhotoID
				  LEFT JOIN(
					SELECT LOCID, Place
					FROM Location) c ON b.LOCID=c.LOCID
				   WHERE b.LOCID IS NOT NULL AND a.TransID IS NOT NULL
				   GROUP BY LOCID";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		
		return $result;
	}
	
	function report7()
	{
		global $db;
		
		$sql = "SELECT Film, count(TransID) as '# of Transactions', sum(price) as 'Total Sales $'
				  FROM Photo
				  WHERE TransID IS NOT NULL
				  GROUP BY Film";
					
		( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );
		
		return $result;
	}
	
	//get the contents of an entire table
	function selectTable($tableName)
	{
		global $db;
		
		$sql = "SELECT *
					FROM $tableName";
					
					( $result = mysqli_query($db, $sql) ) or die( mysqli_error($db) );

		return $result;
	}
	
	//display contents of a table in HTML Table
	function displayTable ($title,$table) {
		global $db;
		
		//Loop thru accounts
		echo "<h3>$title</h3>";
		echo "<br><table>";
		while ($field = mysqli_fetch_field($table))
		{
			$name = $field->name;
			echo "<th>$name</th>";
		}
		while (   $row = mysqli_fetch_row ($table)) {
			echo "<tr>";
			foreach($row as $col)
			{
				echo "<td><i>$col</i></td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
	
	//display contents of a table in HTML Table with buttons that can update and delete photos from table
	function displayTableWithButtons ($table) {
		global $db;
		
		echo "<br><table>";
		while ($field = mysqli_fetch_field($table))
		{
			$name = $field->name;
			echo "<th>$name</th>";
		}
		echo "<th>Functions</th>";
		while (   $row = mysqli_fetch_row ($table)) {
			echo "<tr>";
			$PhotoID = $row[0];
			foreach($row as $col)
			{
				echo "<td><i>$col</i></td>";
			}
			echo "<td>
				<form action=\"PhotoPortal.php\" id=\"delete-form\">
					<button type=\"submit\" name=\"delete\" value=\"$PhotoID\" id=\"delete\">Delete</button> </form>
				<form action=\"PhotoUpdateForm.php\"  id=\"update-form\">
					<button type=\"submit\" name=\"update\" value=\"$PhotoID\" id=\"update\">Update</button></form></td></tr>";
		}
		echo "</table>";
	}
	
	//display a table with pictures and check boxes to simulate purchasing environment
	function displayPhotosForSale($type)
	{
		$table = getPhotosForSale($type);
		
		echo "<br><table>";
		$count=0;
		while (   $row = mysqli_fetch_row ($table)) {
			$photographer = getPhotographer($row[9]);
			if($count%3==0) { echo "</tr><tr>"; }
			echo "<td>
				<div>
					<input type = \"checkbox\" name=\"photo$row[0]\" value=\"$row[0]\">Photo: $row[0]<br> 
					<img src= 'placeholder.png' width=200px height =200px><br>
					By: $photographer<br>
					Price: $$row[6]
				</div>
			</td>";
			$count=$count+1;
		}
		echo "</table>";
	}
?>