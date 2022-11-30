<?php
#Adonis Linares-Velasqeuz 
include ("dbconfig.php");
echo "<HTML>\n";
#============================================================================================================================================================ 
if(!isset($_COOKIE['user']))
	die("Please login first.<br>
		<a href='index.html' target=_self>Return to Homepage</a>"); 
#Else if the cookie is set, allow user to update/delete Transaction
$uid=$_COOKIE['user']; #grab uid from cookie
$con = mysqli_connect($host,$username, $password, $dbname); #
#get element arrays
$cid = $_POST['cid'];
$sid = $_POST['sid'];
$mid = $_POST['mid'];
$code = $_POST['code'];
$notes = $_POST['note'];
#============================================================================================================================================================
#allows for Logout 
echo "<a href='logout.php' target=_self>User Logout</a><br>";
#============================================================================================================================================================
#Total length  
$length = count($cid);
#Counters
$i = 0; #General Counter
$rdel = 0; #Records Deleted
$rup = 0; #Records Updated
#============================================================================== 
#Implemented a While Loop to Check Records 
#Run loop as long as counter doesn't match total length of items in arrays
while($i < $length){
#=========================================================================================================================	
#Delete Checkbox if unmarked is False	
if(!isset($_POST['cdelete'.$i])){
	#Temp Files
	$codeA= mysqli_real_escape_string($con, $code[$i]);
	$midA= mysqli_real_escape_string($con, $mid[$i]);
	$noteA= mysqli_real_escape_string($con, $notes[$i]);
	#=========================================================================================================================
	#Check if note is different
	$sqlCheck = "SELECT note from CPS3740_2022F.Money_linarado WHERE cid = '$uid' AND mid = '$midA' AND note = '$noteA' ";
	$resultCheck = mysqli_query($con,$sqlCheck);
	#check if there are 0 rows, if so we update. else we do nothing.
	if(mysqli_num_rows($resultCheck) == 0){
		$sqlUpdate ="UPDATE CPS3740_2022F.Money_linarado set note = '$noteA' where cid = '$uid' AND mid = '$midA' ";
		#Execute Query
		if(mysqli_query($con,$sqlUpdate)){
			#indicate that code has been Updated
			echo("The note for code $codeA has been Updated in the database.<br> ");
			}
		else{
			#If error occurs, display error message
		echo "ERROR: Could not able to execute $sqlUpdate. " . mysqli_error($con);
		}
		#increment record update counter
		$rup = $rup+1;
	}}
#============================================================================================================================================================
#Delete Checkbox if marked is  True
elseif(isset($_POST['cdelete'.$i])){
	#Temp Files
	$codeA= mysqli_real_escape_string($con, $code[$i]);
	$sidA= mysqli_real_escape_string($con, $sid[$i]);
	$midA= mysqli_real_escape_string($con, $mid[$i]);
	#=========================================================================================================================
	$sqlDelete= "DELETE FROM CPS3740_2022F.Money_linarado WHERE cid='$uid' AND code='$codeA' AND sid='$sidA'   AND mid='$midA'";
	#=========================================================================================================================
	#Execute Query
	if(mysqli_query($con,$sqlDelete)){
		#indicate that code has been deleted
		echo("The code $codeA has been deleted from the database.<br> ");
	}
	else{
		#If error occurs, display error message
		echo "ERROR: Could not able to execute $sqlDelete. " . mysqli_error($con);
	}
	#Increase record deleted counter
	$rdel = $rdel+1;
}
#============================================================================================================================================================
#increment $i at the end of loop
$i=$i+1;
}
#============================================================================================================================================================
#============================================================================================================================================================
echo"<br>Number of Transactions Deleted: $rdel | Number of Transactions Updated $rup .";
echo "</HTML>\n";
mysqli_close($con);	
?>
