<?php
#Adonis Linares-Velasquez 
include ("dbconfig.php");
echo "<HTML>\n";
#=========================================================================================================================
#if the cookie is not set, User will be prompt to log in. 
if(!isset($_COOKIE['user']))
	die("Please login first.<br>
		<a href='index.html' target=_self>Return to Login Homepage</a>");
#=========================================================================================================================
#Allowing Customer to Logout. 
echo "<a href='logout.php' target=_self>User Logout</a><br>";
#=========================================================================================================================
# $uid is grabbed from set user cookie.
$uid=$_COOKIE['user'];
$con = mysqli_connect($host,$username, $password, $dbname);
echo "You can only update the <b>Note</b> Column.<br>";
#=========================================================================================================================
# Dispalys Customer's Transactions 
				#SQL query to retrieve customer's transaction  from Money_linarado
						$sql= " SELECT mid, code, type, amount, sid,s.name as source, mydatetime, note FROM CPS3740_2022F.Money_linarado m, CPS3740.Sources s WHERE m.sid = s.id AND m.cid = '$uid'";

					$result= mysqli_query($con, $sql);
					#This Checks if there are any records that can be updated, else the operation will end
					if (mysqli_num_rows($result) == 0)
						die("Can not update, No records found.");
					#Balance, used to keep track of the total transactions currently displayed
					$balance = 0;
					#Counter
					$i= 0;

					echo "<form action='update_transaction.php' method='post'>";
#=============================================================================================================================================
						# Following Creates Transaction Table
						if($result) {
							echo "<TABLE border = 1> ";
							echo "<TR><TH>ID<TH>Code<TH>Amount<TH>Type<TH>Source<TH>Date Time<TH>Note";
							echo"<TH>Delete";
							while($row = mysqli_fetch_array($result)){
								$id = $row['mid'];
								$code = $row['code'];
								$amount = $row["amount"];
								$type = $row['type'];
								$source = $row['source'];
								$sid = $row['sid'];
								$datetime = $row['mydatetime'];
								$note = $row["note"];
								echo "<br><TR><TH>". $id . "<TD>$code";
								
								if($type == 'D'){
									echo "<TD STYLE = 'color:blue'>$amount<TD>Deposit ";
									$balance = $balance + $amount;}
								else{
									echo"<TD STYLE = 'color:red'>$amount<TD>Withdraw ";
									$balance = $balance - $amount;}

								echo"<TD>$source<TD>$datetime";
								echo"<TD bgcolor='yellow'><input type='text' value='$note' name='note[$i]' style='background-color:yellow;'></TD> ";
								echo "<TD><input type='checkbox' name='cdelete$i'>
										<input type='hidden' name='cid[$i]' value='$uid'>
										<input type='hidden' name='sid[$i]' value='$sid'>
										<input type='hidden' name='mid[$i]' value='$id'>
										<input type='hidden' name='code[$i]' value='$code'>	";
								echo"\n";
								#increment counter
								if($i < mysqli_num_rows($result))
								$i=$i+1;
								}
																}
								echo "</TABLE>";  #End of Transaction Table
								
								#Show Balance, displays in blue if balance  is greater or equal to zero otherwise if less will show red for negative.		
								if ($balance >= 0 )
								echo "Total Balance: <font STYLE = 'color:blue'>". $balance ."</font>\n";
								else
								echo "Total Balance: <font STYLE = 'color:red'>". abs($balance) ."</font>\n";

								echo"<br><input type='submit' value='Update Transaction'> </form>";

#==================================================================================================================================================================================================================================================
mysqli_free_result($result);									
echo "</HTML>";
mysqli_close($con);
#==================================================================================================================================================================================================================================================
#==================================================================================================================================================================================================================================================
?>