<?php
session_start();
if($_SESSION['redirect'])
{
 if(isset($_SESSION['redirect']))
 {
  header("Location:".$_SESSION['redirect']);
  exit();
 }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Form Builder</title>
  <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body class="profile">
  <div class="navbar">
    <ul>
      <li><a href="profile.php">Home</a></li>
      <li>Services</li>
      <li>Contacts
          <ul class="sublist">
          </ul>
           <span class="arrow">&#9660;</span>
      </li>
    <li><form action="includes/logout.php" method="post">
    <button type="submit" name="logout">Logout</button> 
</form></li>
    </ul>
  </div>
  <?php
if(isset($_GET['error']))
    {
      if($_GET['error'] == "empty")
      {
        echo "<h2>Fill all the fields</h2>";
      }
      if($_GET['error'] == "noentry")
      {
        echo "<h2>Invalid Entry</h2>";
      }
      if($_GET['error'] == "dberror")
      {
        echo "<h2>try again after sometime</h2>";
      }
      if($_GET['error'] == "nametaken")
      {
        echo "<h2>Try Different Name</h2>";
      }
      if($_GET['error']=="wrngaccess")
      {
        echo "<h2>Access Denied</h2>";
      }
      if($_GET['error']=="another")
      {
        echo "<h2>Finish Launching current form</h2>";
      }
    }
  ?>
  <br>
  <br>
  <br>
<h2>Create Your Form</h2>
<form action="includes/create.php" method="post">
  <input type="text" name="formname" placeholder="Form_name" required/>
  <input type="number" name="timelimit" placeholder="Form validation period (in hour)" required/>
  <input type="number" name="userlimit" placeholder="Response per User" required/>
  <button type="submit" name="create">Start Building</button>
  <button type="submit" name="create1">Bio Form</button>
  <button type="submit" name="create2">Review Form</button>
</form>
<br>
<div id="view">
<?php
 if(isset($_SESSION['formname'])&&isset($_SESSION['create']))
 {
  require 'includes/db.php';
  $formname=$_SESSION['formname'];
  $sql="SELECT id, fieldname, fielddesc, fieldtype from structure WHERE formname='".$formname."'";
  $stmt=mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql))
  {
    echo "error";
  }
  echo "<h2>".$formname."</h2>";
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt,$id, $fieldname, $fielddesc, $fieldtype);
  echo "<table style='margin-top:40px;'><h3><tr><th>ID</th><th>Fieldname</th><th>Description</th><th>Type</th>";
  while(mysqli_stmt_fetch($stmt))
  {
    echo "<tr><td>".$id."</td><td>".$fieldname."</td><td>".$fielddesc."</td><td>".$fieldtype."</td></tr>";
  }
  echo "</h3></table>";
 }
?>
 </div>
 <br>
<?php
if(isset($_SESSION['formname'])&&(isset($_SESSION['create'])) && !isset($_SESSION['fieldname']))
{
  echo '<h2>Add a field</h2>';
echo '<form action="includes/create.php" method="post">
  <input type="text" name="afieldname" placeholder="Field Name" required/>
  <input type="text" name="afielddesc" placeholder="Field Description"/>
  <br>
  <input type="radio" name="fieldtype" value="text" required>Text
  <input type="radio" name="fieldtype" value="image">Image
  <input type="radio" name="fieldtype" value="file">(.txt)File
  <input type="radio" name="fieldtype" value="io">yes/no
  <input type="radio" name="fieldtype" value="num">1-10 scale
  <input type="radio" name="fieldtype" value="radiobtn">Radio<br><br>
  <button type="submit" name="add">Add Field</button>
</form>';
}
?>
<?php
if(isset($_SESSION['fieldname']))
{
  $formname=$_SESSION['formname'];
  $fieldname=$_SESSION['fieldname'];
  echo "<form action='includes/create.php' method='post'>";
  echo "<input type='text' name='choice' placeholder='choice'/>";
  echo "<button type='submit' name='choices'>Add Choice</button>";
  echo "<button type='submit' name='end'>Finish</button>";
  echo "</form>";
}
?>
<br>
<?php
if(isset($_SESSION['formname']) &&(isset($_SESSION['create'])) && !isset($_SESSION['fieldname']))
{
echo '<h2>Delete a field</h2>';
echo '<form action="includes/create.php" method="post">
  <input type="number" name="fieldid" placeholder="Field_ID" required/>
  <input type="text" name="dfieldname" placeholder="Field_Name"/><br><br>
  <button type="submit" name="delete">Delete Field</button>
</form>
<h2>Modify a field</h2>
<form action="includes/create.php" method="post">
  <input type="text" name="newfieldid" placeholder="ID to be modified" required/>
  <input type="text" name="newfieldname" placeholder="New_Fielname"/>
  <input type="text" name="newfielddesc" placeholder="New_FieldDescription" />
  <br>
  <input type="radio" name="newfieldtype" value="text">Text
  <input type="radio" name="newfieldtype" value="image">Image
  <input type="radio" name="newfieldtype" value="file">(.txt)File
  <input type="radio" name="newfieldtype" value="io">yes/no
  <input type="radio" name="newfieldtype" value="num">1-10 scale<br><br>
  <button type="submit" name="newedit">Modify</button>
</form>
<br>
<form action="includes/create.php" method="post">
  <button type="submit" name="launch">Launch</button>
</form>';
}
?>
<br>
<hr style="border: 2px solid red;"/>
<?php
require 'includes/db.php';
$creator=$_SESSION['NAME'];
echo "<h2>Your Forms</h2>";
$stmt=mysqli_stmt_init($conn);
$sql="SELECT DISTINCT formname from structure WHERE creator=?";
  if(!mysqli_stmt_prepare($stmt, $sql))
  {
    echo "error";
  }
  mysqli_stmt_bind_param($stmt, "s", $creator);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $formname);
  $i=0;
  echo "<h3>";
  while(mysqli_stmt_fetch($stmt))
  {
    $i+=1;
    echo "&#160".$formname."&#160";
    if($i>=9)
    {
      $i=0;
      echo "<br>";
    }
  }
  echo "</h3>";
echo "<h2>Trending Forms Right Now</h2>";
$sql="SELECT formname FROM structure GROUP BY formname ORDER BY MAX(count) DESC";
if(!mysqli_stmt_prepare($stmt,$sql))
{
  echo "problem";
}
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$print);
$i=0;
echo "<h3>";
while(mysqli_stmt_fetch($stmt) && ($i<3))
{
  $i=$i+1;
  echo $i.".".$print."<br>";
}
echo "</h3>";
$sql="SELECT username, formname FROM notify WHERE creator=? ORDER BY filltime";
if(!mysqli_stmt_prepare($stmt,$sql))
{
  echo "problem";
}
mysqli_stmt_bind_param($stmt,"s",$creator);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$username,$formname);
$i=0;
$user=array();
$form=array();
while(mysqli_stmt_fetch($stmt) && ($i<3))
{
  $user[]=$username;
  $form[]=$formname;
  $i=$i+1;
}
$sql="DELETE FROM notify WHERE creator=?";
if(!mysqli_stmt_prepare($stmt,$sql))
{
  echo "probs";
}
mysqli_stmt_bind_param($stmt,"s",$creator);
mysqli_stmt_execute($stmt);
?>
<script type="text/javascript">
  var user=<?php echo json_encode($user); ?>;
  var form=<?php echo json_encode($form); ?>;
  var i=0;
  while(i<user.length)
  {
  alert(user[i]+" has filled "+form[i]);
  i=i+1;
  }
</script>
<form action="view.php" method="post">
  <input type="text" name="viewform" placeholder="Form to view"/>
  <button type="submit" name="viewbutton">View responses</button>
</form>
<br>
<hr style="border: 2px solid red;"/>
<h2>Fill a Form</h2>
<form action="" method="post">
  <input type="text" name="fillform" placeholder="Formname"/>
  <button type="submit" name="fillbutton">Fill Form</button>
</form>
<?php
if(!empty($_POST['fillform']) || isset($_POST['fillbutton']))
{
  $_SESSION['formname']=$_POST['fillform'];
  $formname=$_POST['fillform'];
  header("Location:formfill.php?fillform=".$formname);
  exit();
}
if(!empty($_POST['viewform']) && isset($_POST['viewbutton']))
{
  $_SESSION['formname']=$_POST['viewform'];
  $formname=$_POST['viewform'];
  header("Location:view.php?viewform=".$formname);
  exit();
}
?>
</body>
</html>
	