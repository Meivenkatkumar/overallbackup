<?php
session_start();
if(isset($_SESSION['NAME']))
{
  require 'db.php';
  $name=$_SESSION['NAME'];
  $sql="SELECT * FROM ?";
  $stmt=mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql))
    {
    	header("Location:../index.php?error=dberror");
    	exit();
    }
    else
    {
    	mysqli_stmt_bind_param($stmt, "s", $name);
      	mysqli_stmt_execute($stmt);
      	mysqli_stmt_store_result($stmt);
      	while($row=mysqli_fetch_assoc($stmt))
      	{
         echo "<p>expense:".$row['expname'].": ".$row['expdesc']." : Rupees ".$row['expamt']."</p><br>";
      	}
     }
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
else
{
 header("Location:../index.php?error=wrngaccess");
exit();
}
?>