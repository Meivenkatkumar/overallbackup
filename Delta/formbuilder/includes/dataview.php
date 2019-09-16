<?php
require 'includes/db.php';
if(isset($_GET['id']) && isset($_GET['datatype']))
{
	$datatype=$_GET['datatype'];
	if($datatype==".txt" || $datatype==".pdf")
	{
       $id=$_GET['id'];
       $datatype=$_GET['datatype'];
       $stmt=mysqli_stmt_init($conn);
       $sql="SELECT datablob FROM dir WHERE id=? AND datatype=?";
       if(!mysqli_stmt_prepare($stmt, $sql))
       {
       	echo "failure";
       }
       mysqli_stmt_bind_param($stmt, "ss", $id, $datatype);
       mysqli_stmt_execute($stmt);
       mysqli_stmt_bind_result($stmt, $data);
       header("Content-Type:".$datatype)
	}
}
?>