<?php
#Adonis Linares-Velasquez
#Displays a table with all the customer data as Long as User Cookie is found.
#=========================================================================================================================
#if the cookie is not set, then tell the user to login.
if(!isset($_COOKIE['user']))
	die("Please login first.");
#Else if the cookie is set, allow user to view the table.
$uid=$_COOKIE['user'];
echo "The Following Customers are in the Bank System: <br>";
#====================================================================================================================================
# including dbconfig.php allows  neccessary information to login to the db without hardcoding it into each php file.
include "dbconfig.php";
$con = mysqli_connect($host,$username, $password, $dbname);
#============================================================================================================================================================
# The Follwowing SQL query will retrieve the data we want to display on display_customers page
$sql = " SELECT id,login,password,name,gender,dob,street,city,state,zipcode FROM CPS3740.Customers ";
$result = mysqli_query($con, $sql);
# Arranges Table for Front End View
if($result) {
	echo "<TABLE border = 1> ";
	echo "<TR><TH>ID<TH>login<TH>password<TH>Name<TH>gender<TH>DOB<TH>street<TH>city<TH>state<TH>zipcode";
	while($row = mysqli_fetch_array($result)){
		$id = $row['id'];
		$login = $row['login'];
		$password = $row['password'];
		$name = $row["name"];
		$gender = $row["gender"];
		$dob = $row["dob"];
		$street = $row["street"];
		$city = $row["city"];
		$state = $row["state"];
		$zipcode= $row["zipcode"];
		        
			echo "<br><TR><TH>$id<TD>$login<TD>$password<TD>$name<TD>$gender<TD>$dob<TD>$street<TD>$city<TD>$state<TD>$zipcode \n";
}
	echo "</TABLE>";
}
#============================================================================================================================================================
#============================================================================================================================================================
mysqli_free_result($result);
mysqli_close($con);
?>