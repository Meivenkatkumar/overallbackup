<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
  <div id="info"></div>
<?php
include 'includes/db.php';
session_start();
if(!isset($_SESSION['NAME']))
{
  header("Location:startup.php?error=wrngaccess");
  exit();
}
$stmt=mysqli_stmt_init($conn);
$name=$_SESSION['NAME'];
$type=$_SESSION['TYPE'];
$class=$_SESSION['CLASS'];
if($type=="admin")
{
  $sql="SELECT * FROM courses WHERE state='request'";
  $rows=$conn->query($sql);
  $number=$rows->num_rows;
  echo "<form method='post' action='profile.php'>";
  while($row=$rows->fetch_assoc())
  {
    $data=$row['description'];
       $data=str_replace("<b<","&lt;b&gt;", $data);
    $data=str_replace(">b>","&lt;/b&gt", $data);
    $data=str_replace("<i<","&lt;i&gt;", $data);
    $data=str_replace(">i>","&lt;/i&gt", $data);
  echo "<br><div class='container'><strong>".$row['teacher']."</strong> is willing to open course on <strong>".$row['name']."</strong><br>";
  echo htmlspecialchars_decode($data);
  echo " for <strong>class ".$row['class']."</strong><br><button type='submit' name='auth' value='".$row['id']."' class='btn btn-primary'>Allow</button><button type='submit' name='deny' value='".$row['id']."' class='btn btn-danger'>Reject</button></div><br>";
  }
  echo "</form>";
}
if($type=="student")
{
 $sql="SELECT * FROM attendance WHERE studentname='".$name."'";
 $rows=$conn->query($sql);
 $ids=array();
 while($row=$rows->fetch_assoc())
 {
 array_push($ids, $row['id']);
 }
 $sql="SELECT * FROM courses WHERE state='active' AND class=".$class;
 $rows=$conn->query($sql);
 $number=$rows->num_rows;
 echo "<form method='post' action='profile.php'>";
  while($row=$rows->fetch_assoc())
  {
    if(!in_array($row['id'], $ids))
    {
      $data=$row['description'];
      $data=str_replace("<b<","&lt;b&gt;", $data);
    $data=str_replace(">b>","&lt;/b&gt", $data);
    $data=str_replace("<i<","&lt;i&gt;", $data);
    $data=str_replace(">i>","&lt;/i&gt", $data);
  echo "<div class='container'>".$row['name']." taken by ".$row['teacher'].":";
  echo htmlspecialchars_decode($data);
  echo " for class .".$row['class']."<button type='submit' name='join' value='".$row['id']."' class='btn btn-primary'>Join</button></div><br>";
    }
  }
 echo "</form>";
 $sql="SELECT t1.data,t1.type,t2.coursename,t2.id FROM messages t1,attendance t2 WHERE t2.studentname='".$name."' AND t1.id=t2.id";
 $stmt=mysqli_stmt_init($conn);
 if(!mysqli_stmt_prepare($stmt,$sql)) echo "Not Found";
 mysqli_stmt_execute($stmt);
 mysqli_stmt_bind_result($stmt,$data,$datatype,$coursename,$courseid);
 while(mysqli_stmt_fetch($stmt))
 {
  if($datatype=="announcement")
  {
    $data=str_replace("<<","&lt;b&gt;", $data);
    $data=str_replace(">>","&lt;/b&gt", $data);
    $data=str_replace("<<<","&lt;i&gt;", $data);
    $data=str_replace(">>>","&lt;/i&gt", $data);
    echo htmlspecialchars_decode($data);
  }
  if($datatype=="file")
  {
    echo "<b>".$coursename." notes <a class='' href='".$data."'>Click to view</a></b>";
  }
  echo "<br>";
 }
 $stmt=mysqli_stmt_init($conn);
 $sql="SELECT t1.testname,t2.coursename,t1.courseid,t1.testdate,t1.testduration FROM testlist t1, attendance t2 WHERE t1.courseid=t2.id AND t2.studentname='".$name."'";
 mysqli_stmt_prepare($stmt,$sql);
 mysqli_stmt_execute($stmt);
 mysqli_stmt_bind_result($stmt,$testname,$coursename,$courseid,$testdate,$testduration);
 while(mysqli_stmt_fetch($stmt))
 {
  $date = new DateTime("NOW");
  $timestamp=date_timestamp_get($date);
  $ts2 = strtotime($testdate);  
  $seconds_diff = $ts2 - $timestamp; 
  if($seconds_diff>=(120-($testduration*60)))
  {
  echo "Test ".$testname." on ".$coursename." will be hosted on ".$testdate." for ".$testduration."mins.";  
  if(($seconds_diff<=300)&&($seconds_diff>=(120-($testduration*60))))
  {
   echo "<form action='profile.php' method='post'><input type='hidden' name='testtestname' value='".$testname."'><input type='hidden' name='testcourseid' value=".$courseid."><input type='hidden' name='testtestduration' value=".$testduration."><button class='btn btn-primary' type='submit'>Attend</button></form>";
  }
  }
 }
}
if($type=="teacher")
{
  $sql="SELECT t1.coursename,t1.studentname,t1.id FROM attendance t1,courses t2 WHERE t1.state='request' AND t2.teacher='".$name."' AND t1.id=t2.id";
  $stmt=mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt,$sql))
    echo "problem";
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt,$coursename,$studentname,$id);
  echo "<form action='profile.php' method='post'>";
  while(mysqli_stmt_fetch($stmt))
  {
  echo $studentname." has requested to join ".$coursename. "<input type='hidden' name='studentname' value='".$studentname."'><button type='submit' name='accept' value='".$id."' class='btn btn-primary btn-xs'>Accept</button><button type='submit' name='reject' value='".$id."' class='btn btn-danger btn-xs'>Reject</button>";
  }
  echo "</form>";
}
?>
</body>
</html>
