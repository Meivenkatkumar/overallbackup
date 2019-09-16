<?php
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600)); 
session_start();
if(!isset($_SESSION['NAME']))
{
  header("Location:startup.php?error=wrngaccess");
  exit();
}
include 'includes/db.php';
$name=$_SESSION['NAME'];
$type=$_SESSION['TYPE'];
$coursename=$_SESSION['COURSENAME'];
if(isset($_GET['studentname']))
{
	$_SESSION['STUDENTNAME']=$_GET['studentname'];
}
if($type!="student")
   $studentname=$_SESSION['STUDENTNAME'];
else
	$studentname=$name;
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
  <script type="text/javascript" src="https://gstatic.com/charts/loader.js"></script>
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
          <a class="dropdown-item" href="#">Change Password</a>
          <a class="dropdown-item" href="javascript:{}" onclick="document.getElementById('logout').click();">Logout</a>
    </ul>
</div>
 </div>
</nav>
<form action="includes/logout.php" method="post" style="display:none;">
    <button type="submit" name="logout" id="logout">Logout</button> 
</form>
<div class="container-fluid text-center">
	<h1><?php echo $coursename;?></h1>
  <div class="row" style="min-height: 500px;">
    <div class="col-sm-3 text-center">
       <?php
     $sql="SELECT * FROM users WHERE name='".$studentname."'";
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
  echo "<div class='imgcontain'>
<img id='profilepic' src='".$picture."' class='img' height='250' width='250'> <div class='imgoverlay'></div>
  <div id='imgbutton'><a href='#'>".$row['name']."</a></div></div><h3>".$row['name']."</h3>";
 }
 ?>
    </div>
    <div class="col-sm-6 text-center">
      <?php
      $testnames=array();
      $testmarks=array();
      $totalmarks=array();
      $sql="SELECT t1.testname as testname,t1.marks as mark,t1.totalmarks as totalmarks FROM marks t1, courses t2 WHERE t2.name='".$coursename."' AND t1.studentname='".$studentname."' AND t1.id=t2.id";
      $rows=$conn->query($sql);
      if(!$conn->query($sql))
        echo "ppppbb";
      if($rows->num_rows>0)
      {
      $_SESSION['REPORTSTATE']=1;
      echo '<table class="table table-striped"><thead><tr class="table-info"><th>#</th><th>Testname</th><th>Marks</th><th>Totalmarks</th></tr></thead>';
      $i=0;
      while($row=$rows->fetch_assoc())
      {
      	if($i%2!=0)
      	{
      	echo '<tr class="table-info"><th scope="row">'.$i.'</th>
         <td>'.$row["testname"].'</td><td>'.$row["mark"].'</td><td>'.$row["totalmarks"].'</td></tr>';
        }else{
        	echo '<tr class="bluebg" style="background-color:rgb(255,255,255);"><th scope="row">'.$i.'</th>
         <td>'.$row["testname"].'</td><td>'.$row["mark"].'</td><td>'.$row["totalmarks"].'</td></tr>';
         }
         array_push($testnames,$row['testname']);
         array_push($testmarks,$row['mark']);
         array_push($totalmarks,$row['totalmarks']);
         $i=$i+1;
      }
      $_SESSION['i']=$i;
      echo '</tbody></table>';
    }
    else 
      {
        $_SESSION['i']=0;
        echo "<h5>No Test results</h5>";
        $_SESSION['REPORTSTATE']=0;
      }
      ?>
      <div id="testreport" style="min-height: 500px;"></div>
      <div id="testreport1" style="min-height: 500px;"></div>
      <div id="testreport2" style="min-height: 500px;"></div>
      <div id="testreport3" style="min-height: 500px;"></div>
      </div>
    <div class="col-sm-3 d-inline">
      <div id="studentboard"  style="margin-top: 20px;">
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript">
 var reportstate="<?php echo $_SESSION['REPORTSTATE'];?>";
 console.log(reportstate); 
 google.charts.load('current',{'packages':['corechart']});
 google.charts.setOnLoadCallback(drawPieChart);
 if(reportstate=="1")
 {
 var count=1;
 google.charts.setOnLoadCallback(drawBarGraph);
 var i="<?php echo $_SESSION['i'];?>";
<?php
$_SESSION['j']=0;
?>
console.log(i+"donedone");
 i-=1;
 j=1;
if((i>=0)&&(j<=3))
 {
  google.charts.setOnLoadCallback(drawspecialBarGraph);
  i-=1;
  j+=1;
 }
 
 }
  function drawPieChart(){
   var data=google.visualization.arrayToDataTable([<?php 
    $stmt=mysqli_stmt_init($conn);
	$sql="SELECT t1.attendance,t2.noclass FROM attendance t1,courses t2 WHERE t2.name=? AND t1.studentname=? AND t2.id=t1.id";
    mysqli_stmt_prepare($stmt,$sql);
    mysqli_stmt_bind_param($stmt, "ss", $coursename, $studentname);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$indattendance,$totalattendance);
    mysqli_stmt_fetch($stmt);
    $absence=$totalattendance-$indattendance;
    $string="['attended','totalclass'],['attendance',".$indattendance."],['absence',".$absence."]";
    $initial=$string;
   	echo $string;
   	?>]);
   var string="<?php echo $string;?>";

   var option={
    title:"Class attendance percentage",
    is3D:true,
    chatArea:{
    	width:'400px',
    	height:'400px'
    }
   }
   if(string=="<?php echo $initial;?>")
    {
    console.log("Attendance not taken");  
      document.getElementById("studentboard").innerHTML="<p>Attendance count unavailable</p>";
    }  
  else
   {
    console.log("attendance taken");
   var chart=new google.visualization.PieChart(document.getElementById("studentboard"));
   chart.draw(data,option);
 }
}
function drawBarGraph(){
	var testnames=<?php echo json_encode($testnames);?>;
  console.log(testnames);
	document.getElementById("testreport").innerHTML=testnames;
	var data=google.visualization.arrayToDataTable([<?php 
    $i=0;
    $stmt=mysqli_stmt_init($conn);
    $string="['Testname','Firstmark','Class Average','You','Totalmarks']";
    foreach($testnames as $testname)
    {
  	$sql="SELECT MAX(marks) ,AVG(marks) FROM marks WHERE testname='".$testname."'";
   	mysqli_stmt_prepare($stmt,$sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$max,$avg);
    mysqli_stmt_fetch($stmt);
    $string.=",['".$testname."',".$max.",".$avg.",".$testmarks[$i].",".$totalmarks[$i]."]";
    $i+=1;
    }
   	echo $string;
    $_SESSION['string']=$string;
   	?>]);
   var option={
    title:"Test Report",
    vAxis: {title: 'Marks'},
    hAxis: {title: 'Tests'},
    chatArea:{
    	width:500,
    	height:500
    },
    seriesType: 'bars',
    series: {4: {type: 'line'}}
   }
   var chart=new google.visualization.ComboChart(document.getElementById("testreport"));
   chart.draw(data,option);
}

function drawspecialBarGraph(){
  var testnames=<?php echo json_encode($testnames);?>;
  var data=google.visualization.arrayToDataTable([<?php 
    $stmt=mysqli_stmt_init($conn);
  $sql="SELECT qid,timetaken FROM answerrecords WHERE testname='".$testnames[$_SESSION['j']]."' AND studentname='".$_SESSION['STUDENTNAME']."'";
  $_SESSION['j']=$_SESSION['j']+1;
    $string="['question','questiontime']";
    mysqli_stmt_prepare($stmt,$sql);
    mysqli_stmt_execute($stmt);
    // mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt,$qid,$timetaken);
    // if(mysqli_stmt_num_rows($stmt)>0)
    // {
    while(mysqli_stmt_fetch($stmt))
    {
       $string.=",['".$qid."',".$timetaken."]";
    }
    echo $string;
    ?>]);
  console.log(<?php echo $string;?>);
   var option={
    title:"<?php echo $testnames[$_SESSION['i']];?>",
    vAxis: {title: 'questiontime(seconds)'},
    hAxis: {title: 'Testname'},
    seriesType: 'bars'
   }
   var i=count;
   count+=1;
   console.log(i);
   var chart=new google.visualization.ComboChart(document.getElementById("testreport"+i));
   chart.draw(data,option);
}
</script>
</body>
</html>