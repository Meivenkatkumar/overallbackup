<?php
session_start();
if(isset($_SESSION['NAME']))
{
  if(isset($_POST['delete']))
  {
  	require 'dh.php';
	$name=$_SESSION['NAME'];
	$expn=$_POST['expdn'];
    $sql="DELETE FROM ? WHERE expname=?";
    $stmt=mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql))
    {
    	header("Location:../index.php?error=dberror");
    	exit();
    }
    else
    {
    	mysqli_stmt_bind_param($stmt, "ss", $name, $expn);
      	mysqli_stmt_execute($stmt);
       	header("Location:../index.php?success=deletedexp");
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