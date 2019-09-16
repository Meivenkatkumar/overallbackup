<?php
session_start();
$name=$_SESSION['NAME'];
$type=$_SESSION['TYPE'];
if(!isset($_SESSION['NAME']))
{
  header("Location:startup.php?error=wrngaccess");
  exit();
}
include 'includes/db.php';
if(isset($_POST['videobtn'])){
$id=$_POST['videobtn'];
header("Location:videoconference.php?videoclass=".$id);
exit();
}
if(isset($_POST['entertestbtn']))
{
  $id=$_POST['entertestbtn'];
  $testname=$_POST['testname'];
  $totalmark=$_POST['testtotalmark'];
  $testname=mysqli_real_escape_string($conn,$testname);
  $sql="SELECT * FROM marks WHERE testname='".$testname."'";
  $rows=$conn->query($sql);
  if($rows->num_rows==0)
  {
  $sql="INSERT INTO marks (studentname,marks,id,testname,totalmarks) VALUES ";
  $i=0;
  $testmarks=$_POST['testmarks'];
  foreach ($_POST['teststudentname'] as $index=> $studentname) {
    if($i==0)
          $sql.="('".$studentname."','".$testmarks[$index]."','".$id."','".$testname."','".$totalmark."')";
        else
          $sql.=",('".$studentname."','".$testmarks[$index]."','".$id."','".$testname."','".$totalmark."')";
      $i+=1;
  }
  if(!$conn->query($sql))
  {
     echo "pbpbpbp";
  }
 }
 else
 {
  echo " Try again";
 }
}
 if(isset($_POST['assignmentbtn']))
 {
      $id=$_POST['assignmentbtn'];
      $assignment=$_POST['assignment_'.$id];
      $sql="INSERT INTO messages (id,type,data) VALUES ('".$id."','assignment','".$assignment."')";
      if(!$conn->query($sql))
      {
        echo "www1";
      }
}
if(isset($_POST['attendedbtn']))
{
	$id=$_POST['attendedbtn'];
	$sql="UPDATE attendance SET attendance=attendance+1 WHERE state='active' AND id='".$id."' AND (";
	$i=0;
	foreach($_POST['attended'] as $studentname)
	{
		if($i==0)
          $sql.=" studentname='".$studentname."'";
        else
          $sql.=" OR studentname='".$studentname."'";
      $i+=1;
	}
	$sql.=")";
	if(!$conn->query($sql))
	{
	   echo "pbpbpbp";
     echo $sql;
	}
	$sql="UPDATE courses SET noclass=noclass+1 WHERE id='".$id."'";
	if(!$conn->query($sql))
	{
		echo "pbbpbpbbbb";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Online School</title>
<link rel="stylesheet" type="text/css" href="style1.css">
<script src="https://kit.fontawesome.com/99682bc45a.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
</head>
<body>
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
     <li class="nav-item active">
      <a class="nav-link" href="details.php">Courses</a>
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
        echo "<img src='".$picture."' class='rounded-circle' alt='you' height='30' width='30'>  ".$_SESSION['NAME'];
       }
      ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="includes/changepasswd.php">Change Password</a>
          <a class="dropdown-item" href="javascript:{}" onclick="document.getElementById('logout').click();">Logout</a>
    </ul>
</div>
 </div>
</nav>
<form action="includes/logout.php" method="post" style="display:none;">
    <button type="submit" name="logout" id="logout">Logout</button> 
</form>

<div class="row">
  <div class="col-4" id="leftcolumn" style="margin-top: 10px;">
    <?php
    $boardstate=1;
    $sql="SELECT * FROM courses WHERE teacher='".$name."' AND state='active'";
    $rows=$conn->query($sql);
     if($rows->num_rows==0 && $type=="teacher")
      {
        echo "<h2 style='margin:10px 30px;'>No class details to show</h2>";
         $boardstate=0;
      }
    while($row=$rows->fetch_assoc())
    {
      echo "<button type='submit' value='".$row['name']."' onclick='setboard(this.value)' class='btn btn-primary btn-block' style='padding:auto;'><p class='chatdesc'><bold>".$row['name']." ".$row['class']."</bold></p></button>";
    }
    $sql="SELECT * FROM attendance WHERE studentname='".$name."' AND state='active'";
    $rows=$conn->query($sql);
    if($rows->num_rows==0 && $type=="student")
      {
        echo "<h2>No class details to show</h2>";
        $boardstate=0;
      }
    while($row=$rows->fetch_assoc())
    {
      echo "<button type='submit' value='".$row['coursename']."' onclick='setboard(this.value)' class='btn btn-primary btn-block' style='padding:auto;'><p class='chatdesc'><bold>".$row['coursename']."</bold></p></button>";
    }
    if($type=="admin")
    {
      $sql="SELECT * FROM courses WHERE state='active'";
    $rows=$conn->query($sql);
    while($row=$rows->fetch_assoc())
    {
      echo "<button type='submit' value='".$row['name']."' onclick='setboard(this.value)' class='btn btn-primary btn-block' style='padding:auto;'><p class='chatdesc'><bold>".$row['name']." ".$row['class']."</bold></p></button>";
    }
    }
    ?>
  </div>
   <div class="col-sm-8 text-center">
    <div>
     <button class="btn btn-primary btn-block" id="dashboard1" style="margin-top:10px;margin-right:10px;width: 40%;" onclick="setboard(this.id)">Course Details</button>
     <button class="btn btn-primary btn-block" id="dashboard2" style="margin-top:10px;width: 40%;" onclick="setboard(this.id)">Students` Profile</button>
     <div class="container-fluid" id="setboard" style="width:600px;padding-top: 25px;">
     <?php
  include 'includes/db.php';
  $name=$_SESSION['NAME'];
  $type=$_SESSION['TYPE'];
  if($type!="student")
  {
    if(isset($_POST['notesbtn']))
    {
      $id=$_POST['notesbtn'];
      $file=$_FILES['notes'];
      $filename=$file['name'];
      $filearray=explode('.',$filename);
      $filetype=strtolower(end($filearray));
      $file=$file["tmp_name"];
      $uniq=uniqid();
      $response="documents/".$uniq.".".$filetype;
      move_uploaded_file($file, $response);
      $sql="INSERT INTO messages (id,type,data) VALUES ('".$id."', 'file', '".$response."')";
      if(!$conn->query($sql))
      {
        echo "wowowowowow";
      }
    }
    if(isset($_POST['announcementbtn']))
    {
      $id=$_POST['announcementbtn'];
      $message=mysqli_real_escape_string($conn,$_POST['announcement']);
      $sql="INSERT INTO messages (id,type,data) VALUES ('".$id."','announcement','".$message."')";
      if(!$conn->query($sql))
      {
       echo "wowowwww";
      }
    }
    if(isset($_POST['marks']))
    {
      $id=$_POST['marks'];
      $sql="SELECT * FROM attendance WHERE id=".$id." AND state='active'";
      $rows=$conn->query($sql);
      echo "<div id='marksdiv'><h3>Mark List</h3></div><div class='container' style='margin-top:50px;'><form action='details.php' method='post'>";
      echo "<div classs='form-group'><input type='text' name='testname' class='form-control' id='testname' placeholder='testname' onkeyup='validate()' required/><input type='number' placeholder='Total Marks' name='testtotalmark' class='form-control' required></div><div id='marksbtn'>";
      while($row=$rows->fetch_assoc())
      {
        echo "<br><br><br><div class='form-group'><label for='teststudentname[]'>".$row['studentname']."</label><input type='hidden' class='form-control' name='teststudentname[]' value='".$row['studentname']."'><input type='number' class='form-control' name='testmarks[]' placeholder='Marks'></div><br>";
      }
      echo "<button type='submit' name='entertestbtn' class='btn btn-primary' value='".$id."'>Submit</button></div></form></div>";
    }
    elseif(isset($_POST['testbtn']))
    {
      $_SESSION['courseid']=$_POST['testbtn'];
      header("Location:testbuilder.php?testbtn=".$_POST['testbtn']);
      exit(); 
    }
    elseif(isset($_POST['attendance']))
    {
      $id=$_POST['attendance'];
      $sql="SELECT * FROM attendance WHERE id=".$id." AND state='active'";
      $rows=$conn->query($sql);
      echo "<form action='details.php' method='post'><h3>Attendance Checklist</h3>";
      while($row=$rows->fetch_assoc())
      {
        echo "<div class='custom-control custom-checkbox'><label class='custom-label'><input type='checkbox' class='control-input' name='attended[]' value='".$row['studentname']."' >Present <b>".$row['studentname']."</b></label></div>";
      }
      echo "<button type='submit' name='attendedbtn' class='btn btn-primary' value='".$id."'>Submit</button></form>";
    }
    else
    {
      $stm=mysqli_stmt_init($conn);
      $sql="SELECT t1.studentname,t1.data,t2.name FROM assignments t1,courses t2 WHERE t1.courseid=t2.id AND t2.name='".$_SESSION['COURSENAME']."'";
      if(!mysqli_stmt_prepare($stm,$sql))
        echo "error1";
      mysqli_stmt_execute($stm);
      mysqli_stmt_bind_result($stm,$studentname,$data,$coursename);
      while(mysqli_stmt_fetch($stm))
      {
       echo $studentname." has submitted assignment on ".$coursename."<a href='".$data."'>Click here...</a><br>";
      }
    }
}
?>
     </div>
     <div id="studentboard">
     </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
document.getElementById("marksbtn").style.display="none";
function validate(){
  var testname="";
  testname=document.getElementById('testname').value;
  if(testname!="")
  {
  var xhr=new XMLHttpRequest();
  xhr.open('GET','includes/validate.php?testnamevalid='+testname,true);
  xhr.onreadystatechange=function(){
     if (this.readyState == 4 && this.status == 200)
     {
     
     if(this.responseText=="Try different Testname")
     {
      console.log(this.responseText);
       document.getElementById("marksbtn").style.display="none";
       alert(this.responseText);
     }
     else if(this.responseText=="testname pass")
     {
       document.getElementById("marksbtn").style.display="block";
     }
    }
  }
  xhr.send(); 
  }
}
function setboard(val){
  var coursename="";
  var type=<?php echo json_encode($_SESSION['TYPE']);?>;
  coursename=val;
  console.log(coursename);
  if(coursename!="")
  {
    console.log(coursename);
  var xhr=new XMLHttpRequest();
  xhr.open('GET','includes/validate.php?setcourse='+coursename,true);
  xhr.onreadystatechange=function(){
     if (this.readyState == 4 && this.status == 200)
     {
     document.getElementById("setboard").innerHTML=this.responseText;
     }
  }
  xhr.send(); 
  } 
  if((coursename=="dashboard2")&&(type=="student"))
    {
      var studentname=<?php echo json_encode($_SESSION['NAME']);?>;
      studentstatistics(studentname);
    }
  if((coursename=="dashboard1")&&(type=="student"))
  {
  function studentdashboard() {
  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'studentdashboard.php', true);
  xhttp.onload=function(){
  if(this.status==200)
  {
    document.getElementById('studentboard').innerHTML=this.responseText;
  }
  }
  xhttp.send();
  }
window.setInterval(studentdashboard, 1000);
  }  
}
function studentstatistics(val){
  var studentname=val;  
  window.location.href= "studentprofile.php?studentname="+studentname;
 }
</script>
</div>
</div>
</body>
</html>