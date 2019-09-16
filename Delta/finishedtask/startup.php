<?php
require 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>My Online School</title>
  <script src="https://kit.fontawesome.com/99682bc45a.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
  <link rel="stylesheet" href="style1.css">
</head>
<body id="start">
<nav class="navbar navbar-expand-md navbar-dark bg-light sticky-top">
 <a class="navbar-brand" href="profile.php"><img src="includes/logo.png" height="50" width="250"></a>
 <div class="container-fluid">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
  <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar navbar-collapse" id="navbarResponsive" style="padding:0px 30px;">
    <ul class="navbar-nav ml-auto">
      <form action="includes/login.php" method="post" class="form-inline">
     <li class="nav-item active">
      <input type="text" name="lname" class="form-control" style="max-width:300px;" placeholder="username"/>
     </li>
     <li class="nav-item">
       <input type="password" name="lpwd" class="form-control" style="max-width:300px;" placeholder="password"/>
     </li>
     <li class="nav-item">
      <button type="submit" name="login" class="btn btn-primary btn-sm">Login</button> 
     </li>
    </ul>
    </form>
</div>
 </div>
</nav>
<?php
if(isset($_GET['error']))
  	{
  		if($_GET['error'] == "sempty")
  		{
  			echo "<h2>Fill all the fields</h2>";
  		}
  		if($_GET['error'] == "smismatch")
  		{
  			echo "<h2>Password Mismatch</h2>";
  		}
  		if($_GET['error'] == "sdberror")
  		{
  			echo "<h2>Retry error:404</h2>";
  		}
  		if($_GET['error'] == "susralrdy")
  		{
  			echo "<h2>Try different username</h2>";
  		}
  		if($_GET['error'] == "lempty")
  		{
  			echo "<h2>Fill all the fields</h2>";
  		}
  		if($_GET['error'] == "lmismatch")
  		{
  			echo "<h2>Password Mismatch</h2>";
  		}
  		if($_GET['error'] == "ldberror")
  		{
  			echo "<h2>Retry error:404</h2>";
  		}
  		if($_GET['error'] == "lnousr")
  		{
  			echo "<h2>Username does not exist</h2>";
  		}
      if($_GET['error']=="wrngaccess")
      {
        echo "<h2>Wrong Access</h2>";
      }

  	}
?>
<div class="container signup">
<div id="signupdiv">
   <h2>First Time?</h2>
   <form action="includes/signup.php" method="post">
   <div class="form-group">
     <label>Username</label><br>
     <!-- <i class="fas fa-user"></i> -->
     <input type="text" name="sname" placeholder="username"/ class="form-control"><br>
   </div>  
   <div class="form-group">
     <label>Password</label>
     <!-- <i class="fas fa-lock"></i> -->
     <input type="password" name="spwd" placeholder="password" class="form-control" /><br>
   </div>
   <div class="form-group"> 
     <label>Re-type Password</label><br>
   <!--   <i class="fas fa-lock"></i> -->
     <input type="password" name="spwdc" placeholder="re-type password" class="form-control" /><br>
   </div>  
   <br>
   <br>
   <div id="radios"> 
     <label class="radio-inline"><input type="radio" name="gender" value="male" required>Male</label>
     <label ><input type="radio" name="gender" value="female">Female</label><br>
     <label class="radio-inline"><input type="radio" name="acc-type" value="teacher" required>Teacher</label> 
     <label class="radio-inline"><input type="radio" name="acc-type" value="student">Student</label>
   </div>  
   <div style="clear:both;margin:0px 0px;"></div>
   <div class="inputtext">
     <label>Class</label><br>
     <input type="number" min="8" max="12" name="class" placeholder="8-12"><br>
   </div>
     <button type="submit" name="signup"><h5>Signup</h5></button> 
   </form>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>