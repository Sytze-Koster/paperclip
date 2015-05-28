<?php
	session_start();

	// connect naar de database
	include('connectcheck.php');

	// Define $myusername and $mypassword
	$myusername = $_POST['myusername'];
	$mypassword = md5($_POST['mypassword']);

	// To protect MySQL injection (more detail about MySQL injection)
	$myusername = stripslashes($myusername);
	$mypassword = stripslashes($mypassword);
	$myusername = mysql_real_escape_string($myusername);
	$mypassword = mysql_real_escape_string($mypassword);
	$sql="SELECT * FROM members WHERE username='$myusername' and password='$mypassword'";
	$result=mysql_query($sql);



	// Mysql_num_row is counting table row
	$count=mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	// If result matched $myusername and $mypassword, table row must be 1 row

	if($count==1) {

	// Register $myusername, $mypassword and redirect to file "login_success.php"
		$_SESSION['myusername'] = $myusername;
		$_SESSION['mypassword'] = $mypassword;
		$_SESSION['profileid'] = $row['id'];
			header("location:view.php");
	}
	else {
		echo "Uw gebruikersnaam of wachtwoord is onjuist";
	}
?>