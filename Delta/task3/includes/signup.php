<?php
if(isset($_POST['signup']))
{
  require 'db.php';
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
      		$hashpasswd= password_hash($passwd, PASSWORD_DEFAULT);
      		$sql="INSERT INTO users (name, pwd) VALUES (?, ?)";
      		if(!mysqli_stmt_prepare($stmt, $sql))
      		{
      		header("Location:../index.php?error=sdberror");
    	    exit();	
      		}
      		mysqli_stmt_bind_param($stmt, "ss", $name, $hashpasswd);
      	    mysqli_stmt_execute($stmt);
      	    $sql="CREATE TABLE ".$name." (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, expname VARCHAR(20) not null, expdesc VARCHAR(20), expamt INT(11) not null, expstate INT(11) DEFAULT 0, createtime TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
      	    if(!$conn->query($sql))
            {
             header("Location:../index.php?error=sdberror");
             exit(); 
            }
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