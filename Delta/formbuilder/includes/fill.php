<?php
session_start();
if(!isset($_POST['submitform']))
{
  header("Location:../formfill.php?error=wrngaccess");
  exit();
}
require 'db.php';
$name=$_SESSION['NAME'];
$formname=$_SESSION['formname'];
$stmt=mysqli_stmt_init($conn);
$sql="SELECT creator ,fieldname, fielddesc, fieldtype FROM structure WHERE formname='".$formname."'";
if(!$conn->query($sql))
{
  header("Location:../formfill.php?error=dberror");
  exit();
}
$rows=$conn->query($sql);
$ind=0;
$arra= array();
while($row=$rows->fetch_assoc())
{
  $arra[]=$row;
  $ind=$ind+1;
}
$index=0;
while($index<$ind)
{
   $creator=$arra[$index]['creator'];
   $fieldname=$arra[$index]['fieldname'];
   $fieldtype=$arra[$index]['fieldtype'];
   $fielddesc=$arra[$index]['fielddesc'];
   $string='response_'.$index;
    if($fieldtype=="text" || $fieldtype=="num" || $fieldtype=="radiobtn")
    {
     $response=mysqli_real_escape_string($conn,$_POST[$string]);
     $sql="INSERT INTO records (creator, formname, fieldname, fieldtype, fielddesc, response, username) VALUES ('".$creator."', '".$formname."', '".$fieldname."', '".$fieldtype."', '".$fielddesc."', '".$response."', '".$name."')";
       if(!mysqli_query($conn, $sql))
       {
       	header("Location:../formfill.php?error=dberror");
        exit();
       }
       if($fieldtype=="radiobtn")
       {
        $sql="UPDATE choices SET count=count+1 WHERE formname='".$formname."' AND fieldname='".$fieldname."' AND choice='".$response."'";
        if(!$conn->query($sql))
        {
          echo "probs";
        }
       }
    }
    else if($fieldtype=="io")
    {
      if(isset($_POST[$string]))
      {
       $response=$_POST[$string];
      }
      else
      {
        $response='YES';
      }
      $sql="INSERT INTO records (creator, formname, fieldname, fieldtype, fielddesc, response, username) VALUES ('".$creator."', '".$formname."', '".$fieldname."', '".$fieldtype."', '".$fielddesc."', '".$response."', '".$name."')";
      if(!mysqli_query($conn, $sql))
      {
        header("Location:../formfill.php?error=dberror");
        exit();
      }
    }
    else if($fieldtype=="file")
    {
     $filename=$_FILES[$string]['name'];
      if(isset($filename))
        { 
          if(!empty($filename))
          {
             $file=$_FILES[$string];
             $filename=$file['name'];
             $filearray=explode('.',$filename);
             $filetype=strtolower(end($filearray));
             $file=$file["tmp_name"];
             $uniq=uniqid();
             $response="../document/".$uniq.".".$filetype;
             move_uploaded_file($file, $response);
             $response="document/".$uniq.".".$filetype;
             $sql="INSERT INTO records (creator, formname, fieldname, fieldtype, fielddesc, response, username) VALUES ('".$creator."', '".$formname."', '".$fieldname."', '".$fieldtype."', '".$fielddesc."', '".$response."', '".$name."')";
            if(!mysqli_query($conn, $sql))
            {
             header("Location:../formfill.php?error=dberror");
             exit();
            }
          }
        }
    }
    else if($fieldtype=="image")
    {
      $filename=$_FILES[$string]['name'];
      if(isset($filename))
        { if(!empty($filename)){
      $image=$_FILES[$string];
      $imagename=$image['name'];
      $imagearray=explode('.',$imagename);
      $imagetype=strtolower(end($imagearray));
      $image=$image["tmp_name"];
      $response=uniqid();
      $uniq=uniqid();
      $response="../document/".$uniq.".".$imagetype;
      move_uploaded_file($image, $response);
      $response="document/".$uniq.".".$imagetype;
      $sql="INSERT INTO records (creator, formname, fieldname, fieldtype, fielddesc, response, username) VALUES ('".$creator."', '".$formname."', '".$fieldname."', '".$fieldtype."', '".$fielddesc."', '".$response."', '".$name."')";
       if(!mysqli_query($conn, $sql))
       {
        header("Location:../formfill.php?error=dberror");
        exit();
       }
     }
   }
   else{
    header("Location:../profile.php?error=select");
        exit();
   }
    }
$index = $index+1; 
}
$sql="UPDATE structure SET count=count+1 WHERE formname=?" ;
if(!mysqli_stmt_prepare($stmt,$sql))
{
  header("Location:../formfill.php?error=dberror1");
  exit();
}
mysqli_stmt_bind_param($stmt,"s",$formname);
mysqli_stmt_execute($stmt);
$sql="INSERT INTO notify (creator,username,formname) VALUES (?,?,?)" ;
if(!mysqli_stmt_prepare($stmt,$sql))
{
  header("Location:../formfill.php?error=dberror1");
  exit();
}
mysqli_stmt_bind_param($stmt,"sss",$creator,$name,$formname);
mysqli_stmt_execute($stmt);
header("Location:../formfill.php?fillform=".$formname);
exit();
?>
