<?php
#Adonis Linares-Velasquez
include ("dbconfig.php");
echo "<HTML>\n";
#=========================================================================================================================
#if the cookie is not set, then tell the user to login.
if(!isset($_COOKIE['user']))
	die("Please login first.<br>
		<a href='index.html' target=_self>Return to Login Homepage</a>");

#Once IF the cookie is set, Display transaction Page. 
$uid=$_COOKIE['user'];
$con = mysqli_connect($host,$username, $password, $dbname);
$name = $_POST['customer_name'];
$balance = $_POST['customer_balance'];
#=========================================================================================================================
#Allows Logout 
echo "<a href='logout.php' target=_self>User Logout</a><br>";
#=========================================================================================================================
#Transaction Page
#Dispplays name and balance amount
echo"<br><b>Add Transaction</b><br><b>$name:  </b>Current balance is <b>$balance</b>.<br>";
#=========================================================================================================================
#Following is form created to that customer is able to add transaction to database. 
echo"<form name='input' action='#' method='post' required='required'>  "  ;
echo"<input type='hidden' name='customer_name' value='$name'>";
echo"Transaction Code:
		<input type='text' name='code' required = 'required'>
		<br>
		<input type='radio' name='type' value='D'>
		Deposit
		<input type='radio' name='type' value='W'>
		Withdraw
		<br>
		Amount:
		<input type='text' name='amount' required = 'required'>
		<input type='hidden' name='balance' value='$balance'><br>
		Select a Source:
		";
#=========================================================================================================================
#SQL Query to gather id and name to fill Sources. 
	$sql_sources = " SELECT id, name FROM CPS3740.Sources";
	$xResult = mysqli_query($con, $sql_sources);
	if($xResult) {
							echo "<select name = 'source_id'>"; # Drop down should be filled with sources names
							echo "<option value = ''></option>"; # First Value is empty till user fills
							while($row = mysqli_fetch_array($xResult)){
								$sid = $row['id']; 
								$name = $row['name'];	
							echo "<option value = '$sid'> $name </option>"; #Display Sources from Sources Table
							}
							echo"</select><br>";
							}
echo "Note: <input type='text' name='note'><br>
	<input type = 'submit' value = 'Submit'></form>";

echo "</HTML>";	
#=========================================================================================================================
#=========================================================================================================================
mysqli_free_result($xResult);
mysqli_close($con);	
?>