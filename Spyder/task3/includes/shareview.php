<?php
session_start();
if(isset($_SESSION['NAME']))
{
  require 'db.php';
  $name=$_SESSION['NAME'];
  $sql="SELECT * FROM share";
  if(!$conn->query($sql))
    {
    	header("Location:../index.php?error=dberror");
    	exit();
    }
    else
    {
    	$rows=$conn->query($sql);
      echo "<h2>Sharing is Caring</h2>";
      echo "<h3><table>";
      echo "<tr><td>BillOwner</td><td>ID</td><td>ExpenseName</td><td>Description</td><td>Expense</td></tr>";
      	while($row=$rows->fetch_assoc())
      	{
      		if(($name == $row['reciever'])&&($row['expamt']>0))
      		{echo "<tr><td>".$row['name']."</td><td>".$row['id']."</td><td>".$row['expname']."</td><td>".$row['expdesc']."</td><td>".$row['expamt']."</td></tr>";}
      	}
        echo "</table></h3>";
    }
  mysqli_close($conn);
}
else
{
 header("Location:../index.php?error=wrngaccess");
exit();
}
?>