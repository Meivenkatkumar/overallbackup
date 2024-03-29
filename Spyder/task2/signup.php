<?php
if(isset($_POST['signup']))
  {
   require 'dh.php';
  $name= $_POST['sname'];
  $passwd= $_POST['spwd'];
  $rpasswd= $_POST['spwdc'];
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
      		$sql="INSERT INTO users(name, pwd) VALUES(?, ?)";
      		if(!mysqli_stmt_prepare($stmt, $sql))
      		{
      		header("Location:../index.php?error=sdberror");
    	    exit();	
      		}
      		$hashpasswd= password_hash($passwd, PASSWORD_DEFAULT)
      		mysqli_stmt_bind_param($stmt, "ss", $name ,$hashpasswd);
      	    mysqli_stmt_execute($stmt);
      	    $sql="CREATE TABLE ? (expname varchar(20), expdesc varchar(20), expamt INT not null)";
      	  mysqli_stmt_prepare($stmt, $sql);
      	  mysqli_stmt_bind_param($stmt, "s", $name);
         	mysqli_stmt_execute($stmt);
      	    header("Location:../index.php?signup=successful");
    	    exit();
        }
      }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
  }
  else{
  	header("Location:../index.php");
    exit();
  }
?>