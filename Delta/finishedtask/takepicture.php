<?php
include 'includes/db.php';
session_start();
if(!isset($_SESSION['NAME']))
{
  header("Location:startup.php?error=wrngaccess");
  exit();
}
$name=$_SESSION['NAME'];
$type=$_SESSION['TYPE'];
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
<body class="profle">
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
<div class="col-sm-4"></div>
<div class="col-sm-4 text-center">
<div id="videowrap" style="margin:auto auto ;"><video id="video" playsinline  autoplay ></video></div>
<div class="snap">
  <button id="takesnap">Capture</button>
</div>
<canvas id="snappedpic" width="400" height="400"></canvas>
<div id="extra"></div>
</div>
<div class="col-sm-4"></div>
</div>
<?php 
session_start();
include 'includes/db.php';
?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
'use strict';
const video=document.getElementById("video");
const canvas=document.getElementById("snappedpic");
const snap=document.getElementById("takesnap");
const errorMsgElement=document.getElementById("span#ErrorMsg");
const constraints={
  audio: false,
  video:{
    width:400,height:400
  }
};
async function init(){
  try{
    const stream = await navigator.mediaDevices.getUserMedia(constraints);
    handleSuccess(stream);
  }
  catch(e){
    errorMsgElement.innerHTML=`navigator.getUserMedia.error:'${e.toString}`;
  }
}
function handleSuccess(stream){
 window.stream=stream;
 video.srcObject=stream;
}
init();
var context=canvas.getContext('2d');
snap.addEventListener("click",function(){
context.drawImage(video,0,0,400,400);
const dataurl= canvas.toDataURL("image/jpeg");
console.log(dataurl);
var xml=new XMLHttpRequest();
xml.open("POST","profile.php",true);
var param="uploadimage="+dataurl;
xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
xml.send(param);
});
</script> 
</body>
</html>
