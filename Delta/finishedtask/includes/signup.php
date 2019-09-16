<?php
if(isset($_POST['signup']))
{
  require 'db.php';
  $name= $_POST['sname'];
  $passwd= $_POST['spwd'];
  $rpasswd= $_POST['spwdc'];
  $type=$_POST['acc-type'];
  $class=$_POST['class'];
  $gender=$_POST['gender'];
  if(empty($name) || empty($passwd) || empty($rpasswd))
    {
      header("Location:../index.php?error=sempty");
      exit();
    }
  else if($passwd !== $rpasswd)
    {
    	header("Location:../index.php?error=smismatch&sname=".$name);
    	exit();
    }
   else
    {
      $sql="SELECT name FROM users WHERE name=?";
      $stmt=mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql))
      {
       header("Location:../index.php?error=sdberror");
    	exit();
      }
      else
      {
      	mysqli_stmt_bind_param($stmt, "s", $name);
      	mysqli_stmt_execute($stmt);
      	mysqli_stmt_store_result($stmt);
      	$row=mysqli_stmt_num_rows($stmt);
      	if($row>0)
      	{
        header("Location:../index.php?error=susralrdy");
    	exit();
      	}
      	else
      	{
      		$hashpasswd= password_hash($passwd, PASSWORD_DEFAULT);
          if($type=="teacher")
            $class=12;
      		$sql="INSERT INTO users (name, pwd, type,class,gender) VALUES (?, ?,?,?,?)";
      		if(!mysqli_stmt_prepare($stmt, $sql))
      		{
      		header("Location:../index.php?error=sdberror");
    	    exit();	
      		}
      		mysqli_stmt_bind_param($stmt, "sssis", $name, $hashpasswd, $type,$class,$gender);
      	  mysqli_stmt_execute($stmt);
      	  header("Location:../index.php?signup=successful");
    	    exit();
        }
      }
    }
 mysqli_stmt_close($stmt);
 mysqli_close($conn);
}
else
{
  	header("Location:../index.php");
    exit();
}
?>