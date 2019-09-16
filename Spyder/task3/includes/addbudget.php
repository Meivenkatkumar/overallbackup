<?php
session_start();
if(isset($_SESSION['NAME']))
{
  if(isset($_POST['addbudget']))
	{
	require 'db.php';	
	$name=$_SESSION['NAME'];
	$budg=$_POST['abudg'];
  if(empty($budg))
    {
      header("Location:../profile.php?error=empty");
      exit();
    }
	$sql="UPDATE users SET budget=? WHERE name=?";
    $stmt=mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql))
        {
         header("Location:../profile.php?error=dberror");
    	 exit();
        }
        else
        {
      	mysqli_stmt_bind_param($stmt, "is", $budg, $name);
      	mysqli_stmt_execute($stmt);
      	header("Location:../profile.php?success=addedexp");
    	exit();
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
	}
   else
  {
  header("Location:../index.php?error=wrngaccess");
  exit();
  }
}
 else
  {
  header("Location:../index.php?error=wrngaccess");
  exit();
  }
?>