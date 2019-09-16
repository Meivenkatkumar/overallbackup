<?php
if(isset($_POST['login']))
{
   require 'db.php';
  $name= $_POST['lname'];
  $passwd= $_POST['lpwd'];
  if(empty($name) || empty($passwd))
    {
      header("Location:../index.php?error=lempty");
      exit();
    }
  else
  {
  	$sql="SELECT * FROM users WHERE name=?";
  	$stmt=mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql))
    {
     header("Location:../index.php?error=ldberror");
     exit();	
    }
    else
    {
    	mysqli_stmt_bind_param($stmt, "s", $name);
    	mysqli_stmt_execute($stmt);
    	$rows=mysqli_stmt_get_result($stmt);
        if($row=mysqli_fetch_assoc($rows))
        {
        $hashpwd=$row['pwd'];
        $result=password_verify($passwd, $hashpwd);
         if($result)
           {
               session_start();
               $_SESSION['NAME']=$row['name'];
               $_SESSION['PWD']=$row['pwd'];
               header("Location:../index.php?success=welcome");
               exit();
           }
         else
           {
            	header("Location:../index.php?error=lmismatch");
                exit();
           }
          
        }
        else
        {
       	header("Location:../index.php?error=lnousr");
        exit();
        }
    }
  }

}
else
{
  header("Location:../index.php");
  exit();
}
?>