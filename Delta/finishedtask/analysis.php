<?php
session_start();
if(!isset($_SESSION['NAME']))
{
	header("Location:startup.php?error=wrngaccess");
	exit();
}
$name=$_SESSION['NAME'];
$type=$_SESSION['TYPE'];
if($type!="admin")
{
	header("Location:index.php");
	exit();
}
include 'includes/db.php';
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
  <script type="text/javascript" src="https://gstatic.com/charts/loader.js"></script>
  <link rel="stylesheet" type="text/css" href="style1.css">
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
     <li class="nav-item active">
      <a class="nav-link" href="profile.php">Home</a>
     </li>
     <?php
     if($type!="admin")
     {
     echo '<li class="nav-item"><a class="nav-link" href="details.php">Courses</a></li>';
     } 
     if($type=="admin")
     {
       echo ' <li class="nav-item">
      <a class="nav-link" href="analysis.php">Web Analysis</a>
     </li>';
     }
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
          <a class="dropdown-item" href="includes/changepasswd.php" >Change Password</a>
          <a class="dropdown-item" href="javascript:{}" onclick="document.getElementById('logout').click();">Logout</a>
    </ul>
</div>
 </div>
</nav>
<form action="includes/logout.php" method="post" style="display:none;">
    <button type="submit" name="logout" id="logout">Logout</button> 
</form>
<div class="row">
	<div class="col-sm-3 text-center">
		
	</div>
	<div class="col-sm-6">	
    <div id="curlresponse" style="min-height: 500px;"></div>
        <div id="webpageresponse1"></div>
        <div id="webpageresponse2"></div>
	</div>
	<div class="col-sm-3">
		
	</div>
	
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript">
  console.log("1");
google.charts.load('current', {'packages':['corechart']});
console.log("2");
google.charts.setOnLoadCallback(drawLine);
google.charts.setOnLoadCallback(pageLine);
function drawLine(){
  console.log("3");
	 var data=google.visualization.arrayToDataTable([ <?php 
   $string="['Time','nameLookup','Connect time','Pretransfer','Starttransfer','Totaltime']";
   $lines = file("analysis/datalog");
   $i=0;
   foreach($lines as $line)  
   {
    if($line!="")
    {
     $dataarray=array();
     $dataarray=explode(" ",$line);
     $dataarray[0]=preg_replace( "/\r|\n/", "", $dataarray[0]);
     $dataarray[1]=preg_replace( "/\r|\n/", "", $dataarray[1]);
     $dataarray[2]=preg_replace( "/\r|\n/", "", $dataarray[2]);
     $dataarray[3]=preg_replace( "/\r|\n/", "", $dataarray[3]);
     $dataarray[4]=preg_replace( "/\r|\n/", "", $dataarray[4]);
     $dataarray[5]=preg_replace( "/\r|\n/", "", $dataarray[5]);
     $dataarray[0]=$dataarray[0]*1000000;
     $dataarray[1]=$dataarray[1]*1000000;
     $dataarray[2]=$dataarray[2]*1000000;
     $dataarray[3]=$dataarray[3]*1000000;
     $dataarray[4]=$dataarray[4]*1000000;
    str_replace("-",".", $dataarray[5]);
    if($dataarray[0]!=0 && $dataarray[1]!=0 && $dataarray[2]!=0 && $dataarray[3]!=0 && $dataarray[4]!=0)
     $string.=",['".$dataarray[5]."',".$dataarray[0].",".$dataarray[1].",".$dataarray[2].",".$dataarray[3].",".$dataarray[4]."]";
    }
    $i+=1;   
   } 
   echo $string;
   ?>]);
   console.log(<?php echo json_encode($string);?>);
	 var options = {
      title: 'Server Performace',
       hAxis: {
                  title: 'Time',
                  textStyle: {
                   color: '#01579b',
                   fontSize: 15,
                   fontName: 'Arial',
                   bold: true,
                   italic: true
                 }
                  },
       vAxis: {
                  title: 'Microseconds',
                  textStyle: {
                     color: '#01579b',
                     ontName: 'Arial',
                     fontSize: 15,
                     bold: true,
                     italic: true
                  }
                },
      legend: { position: 'bottom' }
   };
   var chart = new google.visualization.LineChart(document.getElementById('curlresponse'));
   chart.draw(data, options);
}
function pageLine(){
   var data1=google.visualization.arrayToDataTable([ <?php 
   $string="['Time','Index']";
   $lines = file("analysis/indexresponsetime");
   $i=0;
   foreach($lines as $line)  
   {
    if($line!="")
    {
     $dataarray=array();
     $dataarray=explode(" ",$line);
     $dataarray[0]=preg_replace( "/\r|\n/", "", $dataarray[0]);
     $dataarray[1]=preg_replace( "/\r|\n/", "", $dataarray[1]);
     str_replace(":",".", $dataarray[1]);
    if($dataarray[0]!=0 && $dataarray[1]!=0)
     $string.=",['".$dataarray[1]."',".$dataarray[0]."]";
    }
    $i+=1;   
   } 
   echo $string;
   ?>]);
   var data2=google.visualization.arrayToDataTable([<?php 
    $string="['Time','Details Page']";
   $lines = file("analysis/detailsresponsetime");
   $i=0;
   foreach($lines as $line)  
   {
    if($line!="")
    {
     $dataarray=array();
     $dataarray=explode(" ",$line);
     $dataarray[0]=preg_replace( "/\r|\n/", "", $dataarray[0]);
     $dataarray[1]=preg_replace( "/\r|\n/", "", $dataarray[1]);
     str_replace(":",".", $dataarray[1]);
    if($dataarray[0]!=0 && $dataarray[1]!=0)
     $string.=",['".$dataarray[1]."',".$dataarray[0]."]";
    }
    $i+=1;   
   } 
   echo $string;
    ?>]);
   var options = {
      title: "Webpage's individual Performace",
      height: 700,
      hAxis: {
                  title: 'Time',
                  textStyle: {
                   color: '#01579b',
                   fontSize: 15,
                   fontName: 'Arial',
                   bold: true,
                   italic: true
                 }
                  },
       vAxis: {
                  title: 'Duration (microseconds)',
                  textStyle: {
                     color: '#01579b',
                     ontName: 'Arial',
                     fontSize: 15,
                     bold: true,
                     italic: true
                  }
                },
      legend: { position: 'bottom' }
   };
    var data = google.visualization.data.join(data1, data2, 'full', [[0, 0]], [1], [1]);
   var chart = new google.visualization.AreaChart(document.getElementById('webpageresponse1'));
   chart.draw(data, options);
}
</script>
</body>
</html>