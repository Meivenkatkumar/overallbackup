<?php
session_start();
if(isset($_SESSION['NAME']))
{
  if(isset($_POST['share']))
  {
  require 'db.php';
  $num=0;
	$name=$_SESSION['NAME'];
	$expname=$_POST['expns'];
  $expid=$_POST['expids'];
  $reciever=$_POST['reciever'];
if(empty($expname) || empty($expid) || empty($reciever))
{
      header("Location:../profile.php?error=empty");
      exit();
}      
$sql="SELECT name FROM users WHERE name=?";
$stmt=mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql))
{
  header("Location:../profile.php?error=dberror");
  exit();
}
  mysqli_stmt_bind_param($stmt, "s", $reciever);
  mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $row=mysqli_stmt_num_rows($stmt);
        if($row>0)
        {
          $sql="SELECT * FROM ".$name." WHERE id=".$expid;
          if(!$conn->query($sql))
          {
             header("Location:../profile.php?error=dberror");
             exit();
          }
          else
          {
             $rows=$conn->query($sql);
             while($row = $rows->fetch_assoc())
             {
                $num+=1;
                $expdesc=$row['expdesc'];
                $expamt=$row['expamt'];
                $sql="SELECT * FROM share WHERE id=".$expid." AND reciever='".$reciever."' AND name='".$name."'";
                 if(!$conn->query($sql))
                 {
                    header("Location:../profile.php?error=problem1");
                    exit();
                 }
                 else
                 {
                   $rows=$conn->query($sql);
                   $exprow=$rows->num_rows;
                   if($exprow==0)
                   {
                   $sql="INSERT INTO share (name, id, expname, expdesc, expamt, reciever) VALUES (?,?,?,?,?,?)";
                   $stmt=mysqli_stmt_init($conn);    
                   if(!mysqli_stmt_prepare($stmt, $sql))
                   {
                    header("Location:../profile.php?error=dberror");
                    exit(); 
                   }
                   else
                   {
                    mysqli_stmt_bind_param($stmt, "sissis", $name, $expid, $expname, $expdesc, $expamt, $reciever);
                    mysqli_stmt_execute($stmt);
                    $sql="UPDATE ".$name." SET expstate=".$num." WHERE id=".$expid;
                    if(!$conn->query($sql))
                    {
                     header("Location:../profile.php?error=dberror");
                     exit();
                    }
                   }
                }
                else{
                   header("Location:../profile.php?error=alrdyshrd");
                   exit();
                }

              }         
          
          }
          if($num==0)
          {
            header("Location:../profile.php?error=noentry");
            exit();
          }
    header("Location:../profile.php?success=requestedshare");
    exit();
  }
  }
  else{
  header("Location:../profile.php?error=noentry");
    exit();
  }
    mysqli_close($conn);
  }
  if(isset($_POST['sharecont']))
  {
  require 'db.php';
  $name=$_SESSION['NAME'];
  $billname=$_POST['frndname'];
  $expname=$_POST['expnamecont'];
  $expamt=$_POST['expamtcont'];
  $expid=$_POST['expidcont'];
  if(empty($expname) || empty($expid) || empty($billname) || empty($expamt))
  {
      header("Location:../profile.php?error=empty");
      exit();
  }      
   $sql="SELECT * FROM share WHERE id=".$expid." AND name='".$billname."' AND reciever='".$name."'";
      if(!$conn->query($sql))
      {
        header("Location:../profile.php?error=dberror");
        exit();
      }
      else
      {
          $rows=$conn->query($sql);
          $row = $rows->fetch_assoc();
          $expdesc=$row['expdesc'];
          $expfullamt=$row['expamt'];
          if(($expfullamt>=$expamt) && ($expamt>0))
          {
            $sql="INSERT INTO ".$name." (expname , expdesc , expamt, expstate) VALUES (?, ?, ?, 1)";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql))
              {
                header("Location:../profile.php?error=dberror");
                exit();
              }
            else
            {
            mysqli_stmt_bind_param($stmt, "ssi", $expname, $expdesc, $expamt);
            mysqli_stmt_execute($stmt);
            $expfullamt=$expfullamt-$expamt;
            mysqli_stmt_reset($stmt);
            $sql="UPDATE ".$billname." SET expamt=".$expfullamt." WHERE id=".$expid;
            if(!mysqli_stmt_prepare($stmt, $sql))
              {
                header("Location:../profile.php?error=dberror");
                exit();
              }
            else
            {
                mysqli_stmt_bind_param($stmt, "ssi", $expname, $expdesc, $expamt);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_reset($stmt);
                $sql="UPDATE share SET expamt=".$expfullamt." WHERE id=".$expid;
                if(!mysqli_stmt_prepare($stmt, $sql))
                {
                  header("Location:../profile.php?error=dberror");
                  exit();
                }
                else
                {
                mysqli_stmt_bind_param($stmt, "ssi", $expname, $expdesc, $expamt);
                mysqli_stmt_execute($stmt);
                header("Location:../profile.php?success=addedexp");
                exit();
                }
            }
          }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);   
          }
          else
          {
            header("Location:../profile.php?error=noentry");
            exit();
          }

      }
  }
  else
  {
  header("Location:../profile.php?error=wrngaccess");
  exit();
  }
}
else
{
header("Location:../index.php?error=wrngaccess");
exit();
}
?>