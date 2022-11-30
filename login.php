<?php
#Adonis Linares-Velasquez 
#login.php
#Use the dbconfig.php file to set a connection to the database, run the query, and store it into $result
include ("dbconfig.php");
echo "<HTML>\n";
$con = mysqli_connect($host,$username, $password, $dbname);
#---------------------------------------------------------------
#obtains the user input from the post form within the index.html
$username= mysqli_real_escape_string($con, $_POST['username']);
$password= mysqli_real_escape_string($con, $_POST['password']);

#----------------------------------------------------------------------------------------------------------------------------------
$sql = "SELECT id, login, password FROM CPS3740.Customers WHERE login='$username' ";
$result = mysqli_query($con, $sql);

#--------------------------------------------------------------------------------------------------------------------------------
if($result) {
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)){
			$clogin = $row["login"];
			$cpassword = $row['password'];
			$cid =$row['id'];
			#Checks if entered password exists within the query. If so tell the user login is successful and set a cookie
			if ($cpassword == $password){ 
				setcookie("user", $cid, time()+86400); #86400 is the number of seconds in a Day, Cookie Duration is 24 hours. 

#========================================================================================================================================
#User HomePage
				#allows for Logout 
				echo "<a href='logout.php' target=_self>User Logout</a><br>";
				#Var to hold IP Address string
				$ip = " ".$_SERVER['REMOTE_ADDR'];
				#Sql Statment & query to get the (logged in)User's information; [0]=name,[1]=Age,[2]=Address,[3]=img
				$sql2 = "SELECT name, TIMESTAMPDIFF(year,dob,curdate()) as Age, concat( street, ', ', city, ', ', state,', ',zipcode) 'Address',img FROM CPS3740.Customers WHERE login='$username' AND password='$password' ";
				$resultx = mysqli_query($con, $sql2);
				#retrieves the row, and turns into usable vars
				$row2 = mysqli_fetch_row($resultx);
						$name = $row2[0];
						$age = $row2[1];
						$address =$row2[2];
						$img = $row2[3];

				#Return IP Address
				echo "Your Current IP Address:".$ip.'<br>';
				#Return the the browser being used
				echo "Your Current Browser and OS Being Used : ".$_SERVER['HTTP_USER_AGENT'].'<br>';
				#Check if User is(or is NOT) from Kean
				if(strpos( $ip," 10.") !== false || strpos( $ip," 131.125.") !== false )
					echo("You are from Kean University. <br>");
				else echo "You are NOT from Kean University. <br>";
				#Customer greeting, uses name retrived throught resultx
				echo "Welcome Customer: <b>".$name."</b> <br>";
				#display Customer age
				echo "Age: ".$age." <br>";
				#display Customer address
				echo "Customer Address: ".$address." <br>";
				#display Customer image
				echo"<img src='data:image/jpeg;base64,".base64_encode($img)."'/>";

#-------------------------------------------------------------------------------------------------------------------------------------------------------------
                #Displays Customers Transactions
				#SQL query to retrieve customer's transaction history from the database
					$sqlMoney = " SELECT mid, code, type, amount, s.name as source, mydatetime, note FROM CPS3740_2022F.Money_linarado l, CPS3740.Sources s WHERE l.sid = s.id AND l.cid = '$cid' ";
					$resultA = mysqli_query($con, $sqlMoney);

					#Shows how many transactions there are for the current logged in user
					echo "<hr> There are <b>". mysqli_num_rows($resultA) ."</b> Transactions for customer <b>".$name."</b>\n";
					$balance = 0;

						#Creates Transaction Table
						if($resultA) {
							echo "<TABLE border = 1> ";
							echo "<TR><TD>ID<TD>Code<TD>Type<TD>Amount<TD>Source<TD>Date Time<TD>Note";
							while($row3 = mysqli_fetch_array($resultA)){
								$lid = $row3['mid'];
								$lcode = $row3['code'];
								$lType = $row3['type'];
								$lAmount = $row3["amount"];
								$lSource = $row3['source'];
								$lDatetime = $row3['mydatetime'];
								$lNote = $row3["note"];

								#Checks mid is not empty/NULL
								if ($lid <>"") {
									echo "<br><TR><TH>". $lid . "<TD>$lcode";
								
								if($lType == 'D'){
									echo "<TD>Deposit <TD STYLE = 'color:blue'>$lAmount";
									$balance = $balance + $lAmount;}
								else{
									echo"<TD>Withdraw <TD STYLE = 'color:red'>$lAmount";
									$balance = $balance - $lAmount;}

								echo"<TD>$lSource<TD>$lDatetime";
								echo"<TD>$lNote \n";

								}
																}
								echo "</TABLE>";  #End of Transaction Table
									}
							#Show Balance, display in blue if it is greater or equal to zero else show red for negative.		
								if ($balance >= 0 )
								echo "Total Balance: <font STYLE = 'color:blue'>". $balance ."</font>\n";
								else
								echo "Total Balance: <font STYLE = 'color:red'>". abs($balance) ."</font>\n";
							#---------------------------------------------------------------------------------
							# Transaction Table
							echo"<table border = 0> <tbody><tr>"; 
							#Implement Add Transaction Button
							echo"<td><form action='add_transaction.php' method='POST' > <input type='hidden' name='customer_name' value ='$name'><input type='hidden' name='customer_balance' value ='$balance'> <input type='submit' value ='Add Transaction'></form></td>";
							# Implement Links for Display and Update Transactions & Display Store
							echo"<td><a href='display_transaction.php'>Display and Update Transactions</a> &nbsp <a href='#'>Display Stores</a> </td></tr>";
							# Implement  Search text box to search through transactions using keywords in notes
							echo "<tr> <td colspan='2'> ";
							echo "<form action='search_transaction.php' method = 'get'> Keyword:";
							echo "<input type='text' name='keyword' required='required'> &nbsp<input type='hidden' name='customer_name' value ='$name'>";
							echo "<input type='submit' value='Search Transaction'></form></td></tr>";
							echo "</tbody></table>";  #End of Front End Customer Page.


#============================================================================================================================================
#============================================================================================================================================

mysqli_free_result($resultx);
mysqli_free_result($resultA);
			}
			else{
				echo"<a href='index.html' target=_self>Return to Homepage</a>";# A link to Return to index for Log in. 
				echo "<br> User: <b>$username</b> exists but Password is invalid"; # This Runs if the username is within data base but password is incorrect
				}
			}
	}
	else{
		echo"<a href='index.html' target=_self>Return to Homepage</a>";#A link to Return to index for Log in.
		echo "<br> No such user: <b>$username</b>  exists in the database";   #If Username does not exist within database.
		}
}
	else {
		echo"<a href='index.html' target=_self>Return to Homepage</a>";# A link to Return to index for Log in.
		echo "<br> something went wrong!";    # If there is something wrong with esatblishing connection, prompts user something is wrong.
}
#============================================================================================================================================================
#============================================================================================================================================================
echo "</HTML>";
mysqli_close($con);
?>