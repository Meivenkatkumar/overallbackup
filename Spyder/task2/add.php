<?php
session_start();
if(isset($_SESSION['NAME']))
{
	if(isset($_POST['add']))
	{
		require 'dh.php';
		$name=$_SESSION['NAME'];
		$expn=$_POST['expan'];
		$expd=$_POST['expad'];
		$expa=$_POST['expaa'];
		$sql="INSERT INTO ? (expname , expdesc , expamt) VALUES (?, ?, ?)";
		$stmt=mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql))
        {
         header("Location:../index.php?error=dberror");
    	 exit();
        }
        else
        {
      	mysqli_stmt_bind_param($stmt, "sssi", $name, $expn, $expd, $expa);
      	mysqli_stmt_execute($stmt);
      	header("Location:../index.php?success=addedexp");
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