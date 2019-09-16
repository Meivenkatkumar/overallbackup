<?php
session_start();
$name=$_SESSION['NAME'];
$type=$_SESSION['TYPE'];
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Online School</title>
	 <script src="https://kit.fontawesome.com/99682bc45a.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
  <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body>
	<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
 <a class="navbar-brand" href="../profile.php"><img src="logo.png" height="50" width="250"></a>
 <div class="container-fluid">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
  <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar navbar-collapse" id="navbarResponsive" style="padding:0px 30px;">
    <ul class="navbar-nav ml-auto">
     <li class="nav-item active">
      <a class="nav-link" href="../profile.php">Home</a>
     </li>
     <li class="nav-item">
      <a class="nav-link" href="../details.php">Courses</a>
     </li>
     <?php
     if($type=="teacher")
     {
      echo ' <li class="nav-item">
      <a class="nav-link" href="new.php">New Courses</a>
     </li>';
     }
     ?>
     <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php 
       $sql="SELECT * FROM users WHERE name='".$name."'";
       $rows=$conn->query($sql);
       while($row=$rows->fetch_assoc())
       {
        $picture=$row['picture'];
        if($picture==null)
        {
          if($row['gender']=="male")
            $picture="includes/0.jpeg";
          else 
            $picture="includes/1.jpg";
        }
        echo "<img src='../".$picture."' class='rounded-circle' alt='you' height='30' width='30'>  ".$_SESSION['NAME'];
       }
      ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="javascript:{}" href="changepasswd.php">Change Password</a>
          <a class="dropdown-item" href="javascript:{}" onclick="document.getElementById('logout').click();">Logout</a>
    </ul>
</div>
 </div>
</nav>
<form action="logout.php" method="post" style="display:none;">
    <button type="submit" name="logout" id="logout">Logout</button> 
</form>
<div class="row">
	<div class="col-sm-4"></div>
<div class="container text-center col-sm-4">
	<?php
if(isset($_POST['oldpasswd']))
{
	$newpasswd=$_POST['newpasswd'];
	$oldpasswd=$_POST['oldpasswd'];
	if ($_POST['newpasswd']==$_POST['re_newpasswd']) 
	{
   	$sql="SELECT * FROM users WHERE name=?";
   	$stmt=mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql))
    {
     header("Location:../index.php?error=ldberror");
     exit();	
    }
    else
    {
    	mysqli_stmt_bind_param($stmt, "s", $name);
    	mysqli_stmt_execute($stmt);
    	$rows=mysqli_stmt_get_result($stmt);
        if($row=mysqli_fetch_assoc($rows))
        {
        $hashpwd=$row['pwd'];
        $result=password_verify($oldpasswd, $hashpwd);
         if($result)
           {
           	   $hashpasswd= password_hash($newpasswd, PASSWORD_DEFAULT);
               $sql="UPDATE users SET pwd='".$hashpasswd."' WHERE name='".$_SESSION['NAME']."'";
               $conn->query($sql);
               echo "<h4>Password has been changed successfully</h4>";
           }
         else
           {
            	echo "<h4>Wrong Password";
           }
          
        }
        else
        {
       	echo "Wrong Acess";
        }
    }
	}
	else{
		echo "Password did not match";
	}
}
?>
  <form action="changepasswd.php" method="post" style="margin:40px auto;float: none;">
  	<input type="text" name="oldpasswd" placeholder="Old Password" style="max-width:300px;margin:10px auto; float: none;" class="form-control" required>
  	<input type="password" name="newpasswd" placeholder="New Password" style="max-width: 300px;margin:10px auto;float: none;" class="form-control" required>
  	<input type="password" name="re_newpasswd" placeholder="Re-Type New Password" style="max-width: 300px;margin:10px auto;float: none;" class="form-control" required>
    <button type="submit" class="btn btn-primary form" style="max-width:200px;margin:10px auto;clear:both;">Change Password</button>
  </form>
</div>
<div class="col-sm-4"></div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>