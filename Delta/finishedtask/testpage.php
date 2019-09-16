<?php
session_start();
if(!isset($_SESSION['NAME']))
{
  header("Location:startup.php?error=wrngaccess");
  exit();
}
include 'includes/db.php';
if(isset($_POST['submitanswer']))
{
$testname=$_SESSION['testtestname'];
$courseid=$_SESSION['testcourseid'];
$name=$_SESSION['NAME'];
$actualtotal=0;
$sql="SELECT * FROM questionlist WHERE testname='".$testname."' AND courseid=".$courseid;
$rows=$conn->query($sql);
$qids=array();
while($row=$rows->fetch_assoc())
{
 $qid=$row['questionid'];
 $actualtotal+=$row['questionmarks'];
 array_push($qids,$qid);
}
foreach($qids as $questionid)
 {
 if(isset($_POST[$questionid]))
    $choice=$_POST[$questionid];
 else
 	$choice="";
 $sql="INSERT INTO answerrecords (testname,courseid,studentname,choice,qid) VALUES ('".$testname."','".$courseid."','".$name."','". $choice."',".$questionid.")";
 if(!$conn->query($sql))
  	echo "errormachaaan";
 }
 $totalmarks=0;
 $qidss=array();
 $stmt=mysqli_stmt_init($conn);
 $sql="SELECT t2.qid,t2.questionmarks FROM answerrecords t1,answersscript t2 WHERE t1.courseid=t2.courseid AND t1.testname=t2.testname AND t1.qid=t2.qid AND t1.studentname='".$name."' AND t1.choice=t2.choice AND t2.courseid=".$courseid." AND t2.testname='".$testname."'";
 if(!mysqli_stmt_prepare($stmt,$sql))
 	{echo "pbpbpb";header("Location:1234.php");}
 mysqli_stmt_execute($stmt);
 mysqli_stmt_bind_result($stmt,$qid,$marks);
 echo mysqli_stmt_num_rows($stmt);
 $number=mysqli_stmt_num_rows($stmt);
 while(mysqli_stmt_fetch($stmt))
 {
    array_push($qidss,$qid);
    $totalmarks+=$marks;
    echo "11";
 }
 foreach($qidss as $queid)
 {
 	$sql="UPDATE answerrecords SET state=1 WHERE qid=".$queid." AND testname='".$testname."' AND courseid=".$courseid." AND studentname='".$name."'";
 	if(!$conn->query($sql))
 		echo "pbb1";
 }
 $sql="SELECT * FROM testlist WHERE testname='".$testname."' AND courseid=".$courseid;
 if(!$conn->query($sql))
 	echo "pbb2";
 $rows=$conn->query($sql);
 $row=$rows->fetch_assoc();
 $papermarks=$row['totalmarks'];
 $totalmarks=floor($totalmarks*$papermarks/$actualtotal);
 $sql="INSERT INTO marks (studentname,id,totalmarks,marks,testname) VALUES ('".$name."',".$courseid.",".$papermarks.",".$totalmarks.",'".$testname."')";
  if(!$conn->query($sql))
 {
 	echo "pbb3";
 }
 $sql="UPDATE testlist SET strength=strength+1 WHERE testname='".$testname."' AND courseid=".$courseid;
 if(!$conn->query($sql))
    {echo "pbb4";}
 header("Location:profile.php?examover=submitted".$number);
 exit();
}
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
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
  <div id="beginboard" style="margin-top: 50px;"></div>
<?php
$testname=$_SESSION['testtestname'];
$courseid=$_SESSION['testcourseid'];
$testduration=$_SESSION['testtestduration'];
$stmt=mysqli_stmt_init($conn);
$status=0;
 $sql="SELECT * FROM testlist WHERE courseid=".$courseid." AND testname='".$testname."'";
 $rows=$conn->query($sql);
 $row=$rows->fetch_assoc();
 $starttime=$row['testdate'];
 $date = new DateTime("NOW");
 $timestamp=date_timestamp_get($date);
 $ts2 = strtotime($starttime);
 $_SESSION['starttime']=$ts2;     
 $seconds_diff = $ts2-$timestamp;
 if($seconds_diff>=0)
 {
 	echo "Wait";
    $_SESSION['teststatus']=0;
 } 
 else
 {
   $_SESSION['teststatus']=1;
 }
?>

      <?php
      $qtiming=array();
      $sql="SELECT * FROM questionlist WHERE testname='".$testname."' AND courseid=".$courseid;
      $rows=$conn->query($sql);
      $count=0;
      echo '<div class="container-fluid"><div class="row" style="margin-top:60px;" id="totalboard"><div class="col-sm-4 border-right"><div id="timeboard"></div>';
      while($row=$rows->fetch_assoc())
      {
        $qnumber=$count+1;
        echo '<button value="'.$count.'" class="btn btn-lg" onclick="setquestion(this.value)">Q'.$qnumber.'</button>';
        $count=$count+1;
      }
      echo "</div>";
      $stmt=mysqli_stmt_init($conn);
      $tmp=0;
      $count=0;
      $sql="SELECT t1.questionid,t1.question, t2.choice FROM questionlist t1,optionlist t2 WHERE t1.questionid=t2.questionid AND t1.testname='".$testname."' AND t1.courseid='".$courseid."' AND t1.courseid=t2.courseid AND t1.testname=t2.testname";
      if(!mysqli_stmt_prepare($stmt,$sql))
        echo "qqqwwwww";
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt,$qid,$question,$choices);
      echo "<div class='col-sm-4' style='padding-left:30px;'><form action='testpage.php'  method='post'>";
      while(mysqli_stmt_fetch($stmt))
      {
        if(($tmp!=$qid) && ($tmp!=0))
        {
           echo "</div></div><div class='form-group' id='question_".$count."'><label><h4>".$question."</h4></label><br><br><br><div class='form-check-inline'>";
           $tmp=$qid;
           $count+=1;
        }
        if($tmp==0)
        {
           echo "<br><br><br><div class='form-group' id='question_".$count."'><label><h4>".$question."</h4></label><br><br><br><div class='form-check-inline'>";
           $tmp=$qid;
           $count+=1;
        }
        echo "<label style='margin:10px 15px;'><input type='radio' class='form-check-input' name='".$qid."' value='".$choices."'>".$choices."</label>";
      }
      echo "</div></div><button type='submit' name='submitanswer' id='submitanswer' class='btn btn-primary' onclick='uploadtime()'>Submit</button>";
      echo "</form></div></div></div>";
      ?>
<script>
  var timeindex=0;
  var times=[];
  var totalnumber=<?php echo $count;?>;
  var number=0;
   while(number<totalnumber)
  {
    times.push(0);
    document.getElementById("question_"+number).style.display="none";
  number=number+1;
  }
function setquestion(val){
  var qid= val;
  var number=0;
  while(number<totalnumber)
  {
    document.getElementById("question_"+number).style.display="none";
    number=number+1;
  }
  document.getElementById("question_"+qid).style.display="block";
  timeindex=parseInt(val);
}
document.getElementById("totalboard").style.visibility="hidden";
var teststatus=<?php echo json_encode($_SESSION['teststatus']);?>;
var starttime=<?php echo json_encode($_SESSION['starttime']);?>;
var testduration=<?php echo json_encode($_SESSION['testtestduration']);?>;
testduration=parseInt(testduration);
var elem = document.documentElement;
 if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.mozRequestFullScreen) { /* Firefox */
    elem.mozRequestFullScreen();
  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE/Edge */
    elem.msRequestFullscreen();
  }
function starttest(){
 u=setInterval(testclock,1000);
 document.getElementById("totalboard").style.visibility="visible";
 document.getElementById("beginboard").style.display="none";
}
function testclock(){
 var current=Math.floor(Date.now()/1000);
 var clockcount=(testduration*60)-(current-starttime);
 if(clockcount<=0)
 {
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  } else if (document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) {
    document.msExitFullscreen();
  }	
  document.getElementById("submitanswer").click();
 }
 var seconds=clockcount%60;
 clockcount=Math.floor(clockcount/60);
 document.getElementById("timeboard").innerHTML="<p>Time Remaining "+clockcount+"."+seconds+"</p>";
  times[timeindex]+=1;
  console.log(timeindex+" of "+times[timeindex]);
  var timestring=JSON.stringify(times);
  var xml=new XMLHttpRequest();
  xml.open("POST","includes/validate.php",true);
  xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  var param="uploadtime="+timestring;
  xml.send(param);
  console.log(<?php echo $_SESSION['uploadtime'];?>);
}
function timeit(){
var current=Math.floor(Date.now()/1000);
var clockcount=starttime-current;
document.getElementById("beginboard").innerHTML="Test will begin in <h2>"+clockcount+"</h2>";
if(clockcount<=0)
 {
  clearInterval(t);
  starttest();
 }
}
if(teststatus==0)
{
 t=setInterval(timeit,1000);
}
else if(teststatus==1)
{
  starttest();
}
</script>	
</body>
</html>
