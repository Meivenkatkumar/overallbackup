<?php
session_start();
if(isset($_SESSION['NAME']))
{
  require 'db.php';
  $name=$_SESSION['NAME'];
  $sql="SELECT * FROM ".$name;
  if(!$conn->query($sql))
    {
    	header("Location:../index.php?error=dberror");
    	exit();
    }
    else
    {
      $rows=$conn->query($sql);
      echo '<link href="../index.css" media="">';
      echo "<h2>Your Expenses</h2>";
      echo "<h3><table><tr><thead><td>ID</td><td>Expense_Name</td><td>Description</td><td>Expense</td></thead></tr>";
      	while($row=$rows->fetch_assoc())
      	{
         echo "<tr><td>".$row['id']."</td><td>".$row['expname']."</td><td>".$row['expdesc']."</td><td>".$row['expamt']."</td></tr>";
      	}
      echo "</table></h3>";
      $sql="SELECT SUM(expamt) as total FROM ".$name;
      $rows=$conn->query($sql);
      $row=$rows->fetch_assoc();
      $netexp=$row['total'];
      $sql="UPDATE users SET netexp=? WHERE name=?";
      $stmt=mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql))
        {
         header("Location:../index.php?error=dberror");
         exit();
        }
        else
        {
        mysqli_stmt_bind_param($stmt, "is", $netexp, $name);
        mysqli_stmt_execute($stmt);
        }
      $sql="SELECT budget, netexp FROM users WHERE name=?";
      if(!mysqli_stmt_prepare($stmt, $sql))
        {
         header("Location:../index.php?error=error");
         exit();
        }
      else
        {
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $budg, $exp);
        while(mysqli_stmt_fetch($stmt))
        {
         echo "<h3>Budget =".$budg;
         echo "NetExpense =".$exp;
         $bal=$budg-$exp;
         echo "Balance =".$bal."</h3>";
         if($bal<0)
          echo "<br><h3>Recharge Your wallet</h3>";
        }
        }
    }
  mysqli_close($conn);
}
else
{
 header("Location:../index.php?error=wrngaccess");
exit();
}
?>