<?php
session_start();
include 'db.php';
if(!isset($_SESSION['NAME']))
{
  header("Location:startup.php?error=wrngaccess");
  exit();
}
if(isset($_POST['uploadtime'])){
 $_SESSION['uploadtime']=$_POST['uploadtime'];
}
if(isset($_GET['setcourse']))
{
  $type=$_SESSION['TYPE'];
  $coursename=$_GET['setcourse'];
  $sql="SELECT * FROM attendance WHERE studentname='".$_SESSION['NAME']."' AND coursename='".$coursename."'";
  $rows=$conn->query($sql);
  if($rows->num_rows>0)
    $_SESSION['COURSENAME']=$coursename;
   $sql="SELECT * FROM courses WHERE name='".$coursename."' AND state='active' AND teacher='".$_SESSION['NAME']."'";
      $rows=$conn->query($sql);
      if($rows->num_rows>0)
      {
         $_SESSION['COURSENAME']=$coursename;
          echo "<h2><b>".$coursename."</b></h2><form action='details.php' method='post' enctype='multipart/form-data'>";
      while($row=$rows->fetch_assoc())
      {
        echo "<div class='form-group'>Enter Test Marks <button type='submit' name='marks' value='".$row['id']."' onclick='marks()' class='btn btn-primary'>Enter</button></div>";
        echo "<div class='form-group'>Take attendance <button type='submit' name='attendance' value='".$row['id']."' class='btn btn-primary'>Record</button></div>";
        echo "<div class='file-field'><div class='btn btn-xsm float-left d-inline'><input type='file' style='width:50%;' name='notes' id='notes' accept=' .txt, .pdf'><button type='submit' name='notesbtn' value='".$row['id']."' class='btn btn-primary'>Upload Notes</button></div></div><br>";
        echo '<div class="form-group">Class announcement <div class="input-group"><input placeholder="announcement" type="text" name="announcement" id="announcement" class="form-control"><span class="input-group-btn"><button type="submit" name="announcementbtn" value="'.$row['id'].'" class="btn btn-primary">Announce</button></span></div></div><br>'; 
        echo "<div class='form-group'>Assign an assignment <div class='input-group'><input type='text' placeholder='Assignment Topic' name='assignment_".$row['id']."' id='assignment_".$row['id']."' class='form-control'><span class='input-group-btn'><button type='submit' name='assignmentbtn' value='".$row['id']."' class='btn btn-primary'>Assign</button></span></div></div><br>";
        echo "<div class='form-group'>Create MCQ Test Paper <button type='submit' name='testbtn' value='".$row['id']."' class='btn btn-primary'>Create Test</button></div>";
        // echo "<div class='form-group'>Create Online Session <button type='submit' name='videobtn' value='".$row['id']."' class='btn btn-primary'><i class='fas fa-video'></i></button></div>";
      }
      echo "</form>";
      }
      elseif($coursename=="dashboard1")
      {
         $coursename=$_SESSION['COURSENAME'];
         $sql="SELECT * FROM courses WHERE name='".$coursename."' AND state='active' AND teacher='".$_SESSION['NAME']."'";
         $rows=$conn->query($sql);
          echo "<h2><b>".$coursename."</b></h2><form action='details.php' method='post' enctype='multipart/form-data'>";
      while($row=$rows->fetch_assoc())
      {
        echo "<div class='form-group'>Enter Test Marks <button type='submit' name='marks' value='".$row['id']."' onclick='marks()' class='btn btn-primary'>Enter</button></div>";
        echo "<div class='form-group'>Take attendance <button type='submit' name='attendance' value='".$row['id']."' class='btn btn-primary'>Record</button></div>";
        echo "<div class='file-field'><div class='btn btn-xsm float-left d-inline'><input type='file' style='width:50%;' name='notes' id='notes' accept=' .txt, .pdf'><button type='submit' name='notesbtn' value='".$row['id']."' class='btn btn-primary'>Upload Notes</button></div></div><br><br>";
        echo '<br><br><div class="form-group">Class announcement <div class="input-group"><input placeholder="announcement" type="text" name="announcement" id="announcement" class="form-control"><span class="input-group-btn"><button type="submit" name="announcementbtn" value="'.$row['id'].'" class="btn btn-primary">Announce</button></span></div></div><br>'; 
        echo "<div class='form-group'>Assign an assignment <div class='input-group'><input type='text' placeholder='Assignment Topic' name='assignment_".$row['id']."' id='assignment_".$row['id']."' class='form-control'><span class='input-group-btn'><button type='submit' name='assignmentbtn' value='".$row['id']."' class='btn btn-primary'>Assign</button></span></div></div><br>";
        echo "<div class='form-group'>Create MCQ Test Paper <button type='submit' name='testbtn' value='".$row['id']."' class='btn btn-primary'>Create Test</button></div>";
        // echo "<div class='form-group'>Create Online Session <button type='submit' name='videobtn' value='".$row['id']."' class='btn btn-primary'><i class='fas fa-video'></i></button></div>";
      }
      echo "</form>";
      }
      elseif($coursename=="dashboard2")
      {
         $coursename=$_SESSION['COURSENAME'];
         $sql="SELECT * FROM attendance WHERE coursename='".$coursename."' AND state='active'";
         $rows=$conn->query($sql);
         echo "<h2><b>".$coursename."</b></h2>";
         while($row=$rows->fetch_assoc())
         {
          echo "<button type='submit' value='".$row['studentname']."' class='btn btn-secondary btn-block studentlistbtn' onclick='studentstatistics(this.value)'><p class='studentlistp'><bold>".$row['studentname']."</bold></p></button>";
         }
      }
}
if(isset($_GET['testnamevalid']))
{
  $validate=$_GET['testnamevalid'];
  $sql="SELECT * FROM marks WHERE testname=?";
  $stmt=mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt,$sql))
  {
    echo "bpbpbp";
  }
  mysqli_stmt_bind_param($stmt,"s",$validate);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  if(mysqli_stmt_num_rows($stmt)>0)
  {
    echo "Try different Testname";
  }
  else
  {
  	echo "testname pass";
  }
}
if(isset($_GET['valid']))
{
$courseid=$_SESSION['courseid'];
$valid=$_GET['valid'];
$sql="SELECT * FROM testlist t1,marks t2 WHERE t1.testname='".$valid."' OR t2.testname='".$valid."'";
if(!$rows=$conn->query($sql))
  header("Location:index.php");
if($rows->num_rows>0)
 {
   echo "Try different name";
 }
 else
 {
 	echo "Valid name";
 }
}
if(isset($_POST['testname']))
{
$courseid=$_SESSION['courseid'];
$testname=$_POST['testname'];
$_SESSION['testname']=$testname;
$sql="INSERT INTO testlist (testname,courseid) VALUES ('".$testname."','".$courseid."')";
$conn->query($sql);
}
if(isset($_POST['totalmarks']))
{
$courseid=$_SESSION['courseid'];
$testmarks=$_POST['totalmarks'];
$testname=$_SESSION['testname'];
$sql="UPDATE testlist SET totalmarks=".$testmarks." WHERE testname='".$testname."' AND courseid='".$courseid."'";
$conn->query($sql);
}
if(isset($_POST['question']))
{
$question=$_POST['question'];
$courseid=$_SESSION['courseid'];
$testname=$_SESSION['testname'];
$questionid=$_POST['questionid'];
$questionmarks=$_POST['questionmarks'];
if(!($questionmarks>=1))
  $questionmarks=5;
$sql="INSERT INTO questionlist (courseid,testname,questionid,question,questionmarks) VALUES (".$courseid.",'".$testname."',".$questionid.",'".$question."',".$questionmarks.") ON DUPLICATE KEY UPDATE question='".$question."'";	
if(!$conn->query($sql))
	header("Location:3.php");
}
if(isset($_POST['option']))
{
$courseid=$_SESSION['courseid'];
$testname=$_SESSION['testname'];
$option=$_POST['option'];
$optionid=$_POST['optionid'];
$questionid=$_POST['questionid'];
$sql="INSERT INTO optionlist (courseid,testname,choiceid,choice,questionid) VALUES (".$courseid.",'".$testname."',".$optionid.",'".$option."',".$questionid.") ON DUPLICATE KEY UPDATE choice='".$option."'";
if(!$conn->query($sql))
	header("Location:4.php");
}
if(isset($_POST['deleteoption']))
{
$courseid=$_SESSION['courseid'];
$testname=$_SESSION['testname'];
$optionid=$_POST['optionid'];
$questionid=$_POST['questionid'];
$sql="DELETE FROM optionlist WHERE courseid=".$courseid." AND testname='".$testname."' AND choiceid=".$optionid." AND questionid=".$questionid;
if(!$conn->query($sql))
  header("Location:5.php".$sql);
}
if(isset($_GET['answerscript']))
{
$today = date("Y-m-d");
$tmp=0;
$courseid=$_SESSION['courseid'];
$testname=$_SESSION['testname'];
$stmt=mysqli_stmt_init($conn);
$sql="SELECT t1.questionid,t1.question, t2.choice FROM questionlist t1,optionlist t2 WHERE t1.questionid=t2.questionid AND t1.testname='".$testname."' AND t1.courseid='".$courseid."' AND t1.courseid=t2.courseid AND t1.testname=t2.testname";
if(!mysqli_stmt_prepare($stmt,$sql))
	echo "qqqwwwww";
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$qid,$question,$choices);
echo "<form action='profile.php' method='post' class='form-input'>";
echo "<div class='form-group row'><div class='col-xs-3'><input type='date' name='testdate' id='testdate' min='".$today."' required></div>
	<div class='col-xs-3'><input type='time' name='testtime' required></div>
	<div class='col-xs-3'><input type='number' name='testduration' id='testduration' placeholder='duration in mins..'></div></div><div class='form-group'>";
while(mysqli_stmt_fetch($stmt))
{
 if(($tmp!=$qid) && ($tmp!=0))
 {
 	echo "</div></div><br><br><div class='form-group'><label><h4>".$question."</h4></label><br><div class='form-check-inline'>";
 	$tmp=$qid;
 }
 if($tmp==0)
 {
  echo "</div><div class='form-group'><label><h4>".$question."</h4></label><br><div class='form-check-inline'>";
  $tmp=$qid;
 }
 echo "<label class='form-check-label'><input type='radio' class='form-check-input' name='".$qid."' value='".$choices."' required>".$choices."</label>";
}
echo "</div></div><button type='submit' class='btn btn-primary'>Save Answer Script</button>";
echo "</form>";
}

