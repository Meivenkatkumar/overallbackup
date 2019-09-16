<?php
session_start();
$name=$_SESSION['NAME'];
$type=$_SESSION['TYPE'];
include 'includes/db.php';
if(isset($_POST['request']))
{
	$course=$_POST['coursename'];
	$desc=$_POST['desc'];
	$class=$_POST['class'];
	$duration=$_POST['duration'];
	$state="request";
	$sql="INSERT INTO courses (teacher,name,description,class,duration) VALUES (?,?,?,?,?)";
	$stmt=mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt,$sql))
	{
     echo "problem";
	}
	mysqli_stmt_bind_param($stmt, "sssii", $name, $course, $desc, $class, $duration);
	mysqli_stmt_execute($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Online School</title>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/99682bc45a.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
	<!-- <link rel="stylesheet" type="text/css" href="style1.css"> -->
</head>
<body class="profile">
	<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
 <a class="navbar-brand" href="profile.php"><img src="includes/logo.png" height="50" width="250"></a>
 <div class="container-fluid">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
  <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar navbar-collapse" id="navbarResponsive" style="padding:0px 30px;">
    <ul class="navbar-nav ml-auto">
     <li class="nav-item">
      <a class="nav-link" href="profile.php">Home</a>
     </li>
     <li class="nav-item">
      <a class="nav-link" href="details.php">Courses</a>
     </li>
     <?php
     if($type=="teacher")
     {
     	echo '<li class="nav-item active">
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
        echo "<img src='".$picture."' class='rounded-circle' alt='you' height='30' width='30'>".$_SESSION['NAME'];
       }
      ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Change Password</a>
          <a class="dropdown-item" href="javascript:{}" onclick="document.getElementById('logout').click();">Logout</a>
    </ul>
</div>
 </div>
</nav>
<form action="includes/logout.php" method="post" style="display:none;">
    <button type="submit" name="logout" id="logout">Logout</button> 
</form>
<div class="jumbotron text-center" id="proceed">
  <h1 class="display-4">Begin new courses</h1>
  <p class="lead">Its very simple and easy to begin your own class,take two minutes fill few details</p>
  <hr class="my-4">
  <p>Ready to begin?</p>
  <p class="lead">
    <a class="btn btn-primary btn-lg" href="#" id="proceedbtn" role="button">Click here</a>
  </p>
</div>
<div class="jumbotron text-center" id="wait">
  <h1 class="display-4">Great!</h1>
  <hr class="my-4">
  <p>Wait for the Admin to look into your course registration</p>
  <p class="lead">You could
    <a class="btn btn-primary btn-lg" href="#" role="button" onclick="again()">Begin another course</a>
    or
    <a class="btn btn-primary btn-lg" href="profile.php" role="button">Go back</a>
  </p>
</div>
<div class="container" id="buildcourse">
	<div class="newcoursediv">
<div class="form-group">
<form action="new.php" method="post">
  <div class="form-group">
	<label for="coursename">Name of the Course</label>
	<input type="text" name="coursename" class="form-control" required>
  </div>
  <div class="form-group">
	<label for="desc">Class Description:</label>
	<textarea type="text" id="desc" name="desc" rows="5" class="form-control" cols="50" placeholder="Brief description of the course" required></textarea>
	</div>
  <div class="form-group"><br>
  <label for="duration">Class total Duration</label>
	<input type="number" name="duration" max="120" class="form-control" required>
	</div>
  <div class="form-group"><br>
  <label for="class">For Class</label>
	<input type="number" name="class" min="5" max="12" placeholder="8-12" class="form-control" required><br>
	</div>
  <button type="submit" class="btn btn-primary" name="request" onclick="wait()">Request</button>
</div>
<script type="text/javascript">
	document.getElementById("buildcourse").style.display="none";
	document.getElementById("wait").style.display="none";
	document.getElementById("proceedbtn").addEventListener("click",function(){
		document.getElementById("proceed").style.display="none";
		document.getElementById("buildcourse").style.display="block";
	});
	function wait(){
		document.getElementById("buildcourse").style.display="none";
		document.getElementById("wait").style.display="block";
	}
	function again(){
		document.getElementById("buildcourse").style.display="block";
		document.getElementById("wait").style.display="none";
	}

</script>
</form>
</div>
</div>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
</body>
</html>