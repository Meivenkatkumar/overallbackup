<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>
		<h1>Wallet Manager</h1>
	</title>
</head>
<body>
<?php
  if(isset($_SESSION['NAME']))
  {
  header("Location:includes/profile.php?login=success");
  exit();
  }
  else
  {
  	if(isset($_GET['error']))
  	{
  		if($_GET['error'] == "sempty")
  		{
  			echo "<p>Fill all the fields</p>";
  		}
  		if($_GET['error'] == "smismatch")
  		{
  			echo "<p>Password Mismatch</p>";
  		}
  		if($_GET['error'] == "sdberror")
  		{
  			echo "<p>Retry error:404</p>";
  		}
  		if($_GET['error'] == "susralrdy")
  		{
  			echo "<p>Try different username</p>";
  		}
  		if($_GET['error'] == "lempty")
  		{
  			echo "<p>Fill all the fields</p>";
  		}
  		if($_GET['error'] == "lmismatch")
  		{
  			echo "<p>Password Mismatch</p>";
  		}
  		if($_GET['error'] == "ldberror")
  		{
  			echo "<p>Retry error:404</p>";
  		}
  		if($_GET['error'] == "lnousr")
  		{
  			echo "<p>Username does not exist</p>";
  		}

  	}
    echo "<form action="includes/login.php" method="post">
    <input type="text" name="lname" placeholder="username"/>
    <input type="password" name="lpwd" placeholder="password"/>
    <button type="submit" name="login">Login</button> 
</form>
<form action="includes/signup.php" method="post">
    <input type="text" name="sname" placeholder="username"/>
    <input type="password" name="spwd" placeholder="password"/>
    <input type="password" name="spwdc" placeholder="re-type your password"/>
    <button type="submit" name="signup">Signup</button> 
</form>";
  }
?>
</body>
</html>
/*
CREATE DATABASE loginsys;
USE loginsys;
CREATE TABLE users (
ID INT(11) AUTO_INCREMENT PRIMARY KEY not null,
name varchar(20) not null,
pwd LONGTEXT not null);
*/