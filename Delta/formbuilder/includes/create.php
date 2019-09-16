<?php
session_start();
if(isset($_SESSION['NAME']))
{
	if(isset($_POST['create'])||isset($_POST['create1'])||isset($_POST['create2'])||isset($_POST['create3']))
	{
    if(isset($_SESSION['create']))
    {
      header("Location:../profile.php?error=another");
      exit();
    }
    require 'db.php';
	  $name=$_SESSION['NAME'];
	  $formname=$_POST['formname'];
    $timelimit=$_POST['timelimit'];
    $userlimit=$_POST['userlimit'];
	  if(empty($formname))
      {
      header("Location:../profile.php?error=empty");
      exit();
      }
      $sql="SELECT * FROM structure WHERE formname=?";
      $stmt=mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql))
      {
        header("Location:../profile.php?error=dberror");
    	  exit();
      }
      mysqli_stmt_bind_param($stmt, "s", $formname);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $row=mysqli_stmt_num_rows($stmt);
      if($row>0)
      {
       header("Location:../profile.php?error=nametaken");
       exit();
      }
      $_SESSION['formname']=$formname;
      $_SESSION['timelimit']=$timelimit;
      $_SESSION['userlimit']=$userlimit;
      if(isset($_POST['create1'])&&(!isset($_SESSION['create'])))
      {
         $name=$_SESSION['NAME'];
         $formname=$_SESSION['formname'];
        $sql="INSERT INTO structure (creator,formname,fieldname,fieldtype,fielddesc,timelimit,userlimit) VALUES ('".$name."','".$formname."','Name','text','your surname', ".$timelimit.",".$userlimit."),('".$name."','".$formname."','Age','text','in years', ".$timelimit.",".$userlimit."),('".$name."','".$formname."','Gender','io','male/female', ".$timelimit.",".$userlimit."),('".$name."','".$formname."','Passport Image','image','Standar picture', ".$timelimit.",".$userlimit."),('".$name."','".$formname."','Resume','file','pdf/.txt file', ".$timelimit.",".$userlimit.")";
        if(!$conn->query($sql))
        {
           header("Location:../profile.php?success=trouble");
           exit();
        }
      }
      if(isset($_POST['create2'])&&(!isset($_SESSION['create'])))
      {
         $name=$_SESSION['NAME'];
         $formname=$_SESSION['formname'];
        $sql="INSERT INTO structure (creator,formname,fieldname,fieldtype,fielddesc,timelimit,userlimit) VALUES ('".$name."','".$formname."','Name','text','your surname', ".$timelimit.",".$userlimit."),('".$name."','".$formname."','Age','text','in years', ".$timelimit.",".$userlimit."),('".$name."','".$formname."','Rating','num','1-10 Genuine rating', ".$timelimit.",".$userlimit."),('".$name."','".$formname."','Recommendation','io','Will you recommend for others?', ".$timelimit.",".$userlimit.")";
        if(!$conn->query($sql))
        {
           header("Location:../profile.php?success=trouble");
           exit();
        }
      }
      $_SESSION['create']=$formname;
      header("Location:../profile.php?success=formmade");
      exit();
    }
	else if(isset($_POST['add']))
	{
		if(isset($_SESSION['formname']))
		{
      if(empty($_POST['afielddesc']) || empty($_POST['afieldname']))
      {
        header("Location:../profile.php?error=empty");
         exit();
      }
           require 'db.php';
           $name=$_SESSION['NAME'];
           $formname=$_SESSION['formname'];
           $fieldname=$_POST['afieldname'];
           $fielddesc=$_POST['afielddesc'];
           $userlimit=$_SESSION['userlimit'];
           $timelimit=$_SESSION['timelimit'];
           if(empty($fieldname) || empty($fielddesc))
           {
           header("Location:../profile.php?error=empty");
           exit();
           }
           $stmt=mysqli_stmt_init($conn);
           if(isset($_POST['fieldtype']))
           {
            $fieldtype=$_POST['fieldtype'];
           }
           else
           {
            $fieldtype="text";
           }
           if($fieldtype=="radiobtn")
           {
            $_SESSION['fieldname']=$fieldname;
           }
           $sql="INSERT INTO structure (creator, formname, fieldname, fielddesc, fieldtype, userlimit, timelimit) VALUES (?,?,?,?,?,?,?)";
           if(!mysqli_stmt_prepare($stmt, $sql))
           {
             header("Location:../profile.php?error=dberror");
             exit();
           }
           mysqli_stmt_bind_param($stmt, "sssssii",$name,$formname, $fieldname, $fielddesc, $fieldtype, $userlimit, $timelimit);
           mysqli_stmt_execute($stmt);
           header("Location:../profile.php?success=fieldadded");
           exit();
		}
		else
		{
          header("Location:../profile.php?error=wrngaccess");
          exit();
		}
      
	}
  else if(isset($_POST['choices']))
  {
    if(!empty($_POST['choice']))
    {
      require 'db.php';
      $choice=$_POST['choice'];
      $formname=$_SESSION['formname'];
      $field=$_SESSION['fieldname'];
      $sql="INSERT INTO choices (formname,fieldname,choice) VALUES (?,?,?)";
      $stmt=mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql))
      {
        header("Location:../profile.php?error=probe");
         exit();
      }
      mysqli_stmt_bind_param($stmt, "sss", $formname,$field,$choice);
      mysqli_stmt_execute($stmt);
      header("Location:../profile.php?success=fieldadded");
      exit();
    }
    else
    {
      header("Location:../profile.php?error=empty");
      exit();
    }
  }
  else if(isset($_POST['end']))
  {
    unset($_SESSION['fieldname']);
    header("Location:../profile.php?success=fieldadded");
           exit();
  }
	else if(isset($_POST['delete']))
	{
    if(isset($_SESSION['formname']))
    {
      if(empty($_POST['fieldid']) || empty($_POST['dfieldname']))
      {
        header("Location:../profile.php?error=empty");
         exit();
      }
      require 'db.php';
      $formname=$_SESSION['formname'];
      $fieldid=$_POST['fieldid'];
      $fieldname=$_POST['dfieldname'];
      $stmt=mysqli_stmt_init($conn);
      $sql="SELECT fieldtype,fieldname FROM structure WHERE id=?";
      if(!mysqli_stmt_prepare($stmt, $sql))
      {
         header("Location:../profile.php?error=dberror");
         exit();
      }
      mysqli_stmt_bind_param($stmt, "i", $fieldid);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt,$fieldtype,$fieldname);
      mysqli_stmt_fetch($stmt);
      if($fieldtype=="radiobtn")
      {
        $sql="DELETE FROM choices WHERE formname='".$formname."' AND fieldname='".$fieldname."'";
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
         header("Location:../profile.php?error=dberror");
         exit();
        }
        mysqli_stmt_execute($stmt);
      }
      $sql="DELETE FROM structure WHERE id=?";
      if(!mysqli_stmt_prepare($stmt, $sql))
      {
         header("Location:../profile.php?error=dberror");
         exit();
      }
      mysqli_stmt_bind_param($stmt, "i", $fieldid);
      mysqli_stmt_execute($stmt);
      header("Location:../profile.php?success=fieldremoved");
      exit();
    } 
    else{
      header("Location:../profile.php?error=wrngaccess");
      exit();
    } 

	}
  else if(isset($_POST['newedit'])){
    if(empty($_POST['newfieldid']))
    {
      header("Location:../profile.php?error=empty");
      exit();
    }
    $fieldid=$_POST['newfieldid'];
    require 'db.php';
    $stmt=mysqli_stmt_init($conn);
    if(!empty($_POST['newfieldname']))
    {
      $fieldname=$_POST['newfieldname'];
      $sql="UPDATE structure SET fieldname=? WHERE id=?";
      if(!mysqli_stmt_prepare($stmt, $sql))
      {
       header("Location:../profile.php?error=dberror");
       exit();
      }
      mysqli_stmt_bind_param($stmt, "si", $fieldname, $fieldid);
      mysqli_stmt_execute($stmt);
    }
    if(!empty($_POST['newfielddesc']))
    {
      $sql="UPDATE structure SET fielddesc=? WHERE id=?";
      $fielddesc=$_POST['newfielddesc'];
      if(!mysqli_stmt_prepare($stmt, $sql))
      {
       header("Location:../profile.php?error=dberror");
       exit();
      }
      mysqli_stmt_bind_param($stmt, "si", $fielddesc, $fieldid);
      mysqli_stmt_execute($stmt);
    }
    $fieldtype="";
          if(isset($_POST['newfieldtype']))
           {
            $fieldtype=$_POST['newfieldtype'];
           }
    if($fieldtype!="")
    {
      $sql="UPDATE structure SET fieldtype=? WHERE id=?";
      if(!mysqli_stmt_prepare($stmt, $sql))
      {
       header("Location:../profile.php?error=dberror");
       exit();
      }
      mysqli_stmt_bind_param($stmt, "si", $fieldtype, $fieldid);
      mysqli_stmt_execute($stmt);
    }
  header("Location:../profile.php?success=modified");
  exit();
  }
  else if(isset($_POST['launch']))
  {
    unset($_SESSION['create']);
    header("Location:../profile.php?success=launched");
    exit();
  }
}
else
{
header("Location:../profile.php?error=nametaken");
exit();
}
?>