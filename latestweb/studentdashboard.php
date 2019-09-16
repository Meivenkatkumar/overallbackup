<?php
session_start();
if(!isset($_SESSION['NAME']))
{
  header("Location:startup.php?error=wrngaccess");
  exit();
}
include 'includes/db.php';
$coursename=$_SESSION['COURSENAME'];
$name=$_SESSION['NAME'];
$class=$_SESSION['CLASS'];
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
  echo $row['name']." taken by ".$row['teacher'].":".$row['description']." for class .".$row['class']."<button type='submit' name='join' value='".$row['id']."' class='btn btn-primary'>Join</button>";
  }
 echo "</form>";
//  $sql="SELECT * FROM courses WHERE name='".$coursename."' AND videostate=1";
//  $rows=$conn->query($sql);
// $row=$rows->fetch_assoc();
//  echo "<form action='details.php' method='post'>Live class going on <button class='btn btn-primary' name='videobtn' value='".$row['id']."'></button></form>";
 $sql="SELECT t1.data,t1.type,t2.coursename,t2.id FROM messages t1,attendance t2 WHERE t2.studentname='".$name."' AND t1.id=t2.id AND t2.coursename='".$coursename."'";
 $stmt=mysqli_stmt_init($conn);
 if(!mysqli_stmt_prepare($stmt,$sql)) echo "Not Found";
 mysqli_stmt_execute($stmt);
 mysqli_stmt_bind_result($stmt,$data,$datatype,$coursename,$courseid);
 while(mysqli_stmt_fetch($stmt))
 {
  if($datatype=="announcement")
  {
    $data=str_replace("<b<","&lt;b&gt;", $data);
    $data=str_replace(">b>","&lt;/b&gt", $data);
    $data=str_replace("<i<","&lt;i&gt;", $data);
    $data=str_replace(">i>","&lt;/i&gt", $data);
    echo htmlspecialchars_decode($data);
  }
  if($datatype=="file")
  {
    echo "<b>".$coursename." Assignment <a href='".$data."'>Click to view</a></b>";
  }
  echo "<br>";
 }
 $stmt=mysqli_stmt_init($conn);
 $sql="SELECT t1.testname,t2.coursename,t1.courseid,t1.testdate,t1.testduration FROM testlist t1, attendance t2 WHERE t1.courseid=t2.id AND t2.studentname='".$name."' AND t2.coursename='".$coursename."'";
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
  echo "Test ".$testname." on ".$coursename." will be hosted on".$testdate." for ".$testduration."mins.";  
  if(($seconds_diff<=300)&&($seconds_diff>=(120-($testduration*60))))
  {
    echo $seconds_diff;
   echo "<form action='profile.php' method='post'><input type='hidden' name='testtestname' value='".$testname."'><input type='hidden' name='testcourseid' value=".$courseid."><input type='hidden' name='testtestduration' value=".$testduration."><button class='btn btn-primary' type='submit'>Attend</button></form>";
  }
  }
 }
?>