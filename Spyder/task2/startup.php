<?php
echo "hi startup";?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<form action="includes/login.php" method="post">
    <input type="text" name="lname" placeholder="username"/>
    <input type="password" name="lpwd" placeholder="password"/>
    <button type="submit" name="login">Login</button> 
</form>
<form action="includes/signup.php" method="post">
    <input type="text" name="sname" placeholder="username"/>
    <input type="password" name="spwd" placeholder="password"/>
    <input type="password" name="spwdc" placeholder="re-type your password"/>
    <button type="submit" name="signup">Signup</button> 
</form>
</body>
</html>