<?php
#Adonis Linares-Velasquez
#logout file
#if the cookie is set, then 'user' cookie is set to expire in one sec.
if(isset($_COOKIE['user'])){
	setcookie("user", ' ', 1); # One Second Interval till cookie expires
	echo "You have been successfully logged out. <br>";
}
else
#Else tell the user, they are not logged in
echo "You are not logged in.<br>";
#============================================================================================================================================
#Return to Login index Page. 
echo "<a href='index.html' target=_self>Return to Login Homepage</a>";
#============================================================================================================================================================
?>