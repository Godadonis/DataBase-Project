<?php
#Adonis Linares-Velasquez
include ("dbconfig.php");
echo "<HTML>\n";
#============================================================================================================================================
#if the cookie is not set, then tell the user to login.
if(!isset($_COOKIE['user']))
	die("Please login first.<br>
		<a href='index.html' target=_self>Return to Login Homepage</a>");

#Else if the cookie is set, allow user to search transactions
$uid=$_COOKIE['user'];
$con = mysqli_connect($host,$username, $password, $dbname);
$keyword= mysqli_real_escape_string($con, $_GET['keyword']);
$name = $_GET['customer_name'];
#============================================================================================================================================
#Display Customer's Transactions 
		#SQL query to retrieve customer's transaction based on keyword
			#If statment for when the keyword is * database disaplys all records
				if ($keyword == "*")
					$sqlSearch = " SELECT mid, code, type, amount, s.name as source, mydatetime, note FROM CPS3740_2022F.Money_linarado m, CPS3740.Sources s WHERE m.sid = s.id AND m.cid = '$uid'";
				else #else we use the '%' LIKE operator at the current user's id
					$sqlSearch = " SELECT mid, code, type, amount, s.name as source, mydatetime, note FROM CPS3740_2022F.Money_linarado m, CPS3740.Sources s WHERE m.sid = s.id AND m.cid = '$uid' AND note like '%$keyword%' ";

					$resultSearch = mysqli_query($con, $sqlSearch);
					#We check the number of rows before creating table, if rows equal zero no tables will be created and message prompt will appear
					if (mysqli_num_rows($resultSearch) == 0)
						$resultSearchM = False;

					#Balance, used to keep track of the total for the transactions currently displayed
					$balance = 0;


						#Creates Transaction Table
						if($resultSearch) {

							if ($keyword == "*")
								echo "The Transactions in customer <b>".$name."</b> records\n";
							else
								echo "The Transactions in customer <b>".$name."</b> records matching keyword <b>".$keyword."</b> are:\n";


							echo "<TABLE border = 1> ";
							echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Date Time<TH>Note<TH>Source";
							while($row = mysqli_fetch_array($resultSearch)){
								$lid = $row['mid'];
								$lcode = $row['code'];
								$lType = $row['type'];
								$lAmount = $row["amount"];
								$lSource = $row['source'];
								$lDatetime = $row['mydatetime'];
								$lNote = $row["note"];

								
								if ($lid <>"") {
									echo "<br><TR><TH>". $lid . "<TD>$lcode";
								
								if($lType == 'D'){
									echo "<TD>Deposit <TD STYLE = 'color:blue'>$lAmount";
									$balance = $balance + $lAmount;}
								else{
									echo"<TD>Withdraw <TD STYLE = 'color:red'>$lAmount";
									$balance = $balance - $lAmount;}

								echo"<TD>$lDatetime";
								echo"<TD>$lNote<TD>$lSource \n";

								}}
								echo "</TABLE>";  #End of Search Transaction Table
								
								#Color Coded Show Balance within Table
								if ($balance >= 0 )
								echo "Total Balance: <font STYLE = 'color:blue'>". $balance ."</font>\n";
								else
								echo "Total Balance: <font STYLE = 'color:red'>". abs($balance) ."</font>\n";

								mysqli_free_result($resultSearch);
									}
							#This will display when no records are found.		
							else echo "No records found with the search keyword: ".$keyword;
#============================================================================================================================================
#============================================================================================================================================================
echo "</HTML>";
mysqli_close($con);

?>