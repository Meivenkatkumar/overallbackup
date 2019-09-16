<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
    <form action="image.php" method="post" enctype="multipart/form-data">
   <input type="file" name="image">
   <button type="submit" name="imgupload" value="UPLOAD">
    </form>
</body>
</html>
<?php
if(isset($_POST['imgupload']))
{
	require 'db.php';
	$file=$_FILES['image'];
	$filename=$file['name']['name'];
	$filetmpname=$file['name']['tmp_name'];
	$filesize=$file['name']['size'];
	$fileerror=$file['name']['error'];
	$fileext=explode(".", $filename);
	$fileext= strtolower(end($fileext));
	$format=array('jpg', 'jpeg', 'png');
	if(in_array($fileext, $format))
	{
       if($fileerror === 0)
       {
          if($filesize < 1000000)
          {
            $filenewname=$formname.$fieldname.$id.".".$fileext;
            $filedestination='../uploads/'.$filenewname;
            move_uploaded_file($filetmpname, $filedestination);
            $sql="INSERT INTO ".$formname." (".$fieldname.") VALUES (".$filedestination.")";
            $stmt=mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql))
            {
              header("Location:form.php?error=dberror");
              exit();
            }
            mysqli_stmt_execute($stmt);
            header("Location:form.php?success=uploaded");
            exit();
          }
          else
          {
          	header("Location:image.php?error=hugefile");
            exit();
          }
       }
       else
       {
       	header("Location:image.php?error=wrngformat");
        exit();
       }
	}
	else
	{
		header("Location:image.php?error=wrngformat");
        exit();
	}
}
?>