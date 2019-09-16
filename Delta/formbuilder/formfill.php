<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="style1.css">
  <link href='http://fonts.googleapis.com/css?family=Cookie' rel='stylesheet' type='text/css'>
</head>
<body class="profile" style="padding-top: 180px;">
   <div class="navbar">
    <ul>
      <li><a href="profile.php">Home</a></li>
      <li>Services</li>
      <li>Contacts
          <ul class="sublist">
          </ul>
           <span class="arrow">&#9660;</span>
      </li>
    <li><form action="includes/logout.php" method="post">
    <button type="submit" name="logout">Logout</button> 
</form></li>
    </ul>
  </div>
<?php
session_start();
$link="http://";
$link.=$_SERVER['HTTP_HOST'];
$link.=$_SERVER['REQUEST_URI'];
if(!isset($_SESSION['NAME']))
{
$_SESSION['redirect']=$link;
$_SESSION['formname']=$_GET['fillform'];
require 'startup.php';
}
if(isset($_SESSION['NAME']))
{
  if(isset($_SESSION['formname']))
  {
    unset($_SESSION['redirect']);
    require 'includes/db.php';
    $link="http://";
    $link.=$_SERVER['HTTP_HOST'];
    $link.=$_SERVER['REQUEST_URI'];
    $name=$_SESSION['NAME'];
    $formname=$_SESSION['formname'];
    $_SESSION['formname']=$formname;
    $sql="SELECT fieldname FROM structure WHERE formname='".$formname."'";
    if(!$conn->query($sql))
    {
      header("Location:../formfill.php?error=1");
      exit();
    }
    $rows=$conn->query($sql);
    if($row=$rows->fetch_assoc())
    {
    $fieldcheck=$row['fieldname'];
    }
    else{
      echo "<h1>Form Doesnt Exist";
      goto end;
    }
    $sql="SELECT * FROM records WHERE formname='".$formname."' AND fieldname='".$fieldcheck."' AND username='".$name."'";
    if(!$conn->query($sql))
    {
      header("Location:../formfill.php?error=2");
      exit();
    }
    $rows=$conn->query($sql);
    $number=$rows->num_rows;
    $sql="SELECT DISTINCT createtime, timelimit, userlimit FROM structure WHERE formname='".$formname."'";
    if(!$conn->query($sql))
    {
      header("Location:../formfill.php?error=dberror");
      exit();
    }
    $rows=$conn->query($sql);
    $row=$rows->fetch_assoc();
    $time=$row['createtime'];
    $timelimit=$row['timelimit'];
    $userlimit=$row['userlimit'];
    $starttime=strtotime($time);
    $currenttime=time();
    $hourdiff=($currenttime-$starttime)/3600;
    if((($hourdiff <= $timelimit) || ($timelimit==0)) && (($number<$userlimit)|| $userlimit==0))                                     //checking time and user limit
    {
      $stmt=mysqli_stmt_init($conn);
      $sql="SELECT * FROM choices WHERE formname='".$formname."'";
      if(!$conn->query($sql))
      {
        header("Location:../profile.php?error=dberror");
        exit();
      }
      $rows=$conn->query($sql);
      $ARRA=array();
      $count=array();
      $index=0;
      $field="";
      while($row=$rows->fetch_assoc())
      {
        if($index==0)
        {
          $field=$row['fieldname'];
        }
        if($field!=$row['fieldname'])
        {
          array_push($count,$index);
          $field=$row['fieldname'];
          $index=0;
        }
        $ARRA[]=$row['choice'];
        $index=$index+1;
      }
      array_push($count,$index);
      $sql="SELECT creator ,fieldname, fielddesc, fieldtype FROM structure WHERE formname=?";
      if(!mysqli_stmt_prepare($stmt, $sql))
      {
        header("Location:../profile.php?error=dberror");
        exit();
      }
      echo "<h1>Fill ".$formname."</h1>";
      end:
      mysqli_stmt_bind_param($stmt, "s", $formname);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $creator, $fieldname, $fielddesc , $fieldtype);
      echo "<form method='post' action='includes/fill.php' enctype='multipart/form-data'>";
      $index=0;
      $j=0;
      $k=0;
      $st=mysqli_stmt_init($conn);
      while(mysqli_stmt_fetch($stmt))
      {
        echo "<h2>".$fieldname." : ".$fielddesc."</h2>";
        if($fieldtype=="text")
        {
        echo "<input type='text' name='response_".$index."' placeholder='your Response'/>";    
        }
        else if($fieldtype=="image")
        {
        echo "<input type='file' name='response_".$index."' accept='image/*' required/>";    
        }
        else if($fieldtype=="num")
        {
        echo "<input type='number' name='response_".$index."' min='1' max='10' placeholder='1-10'/>";
        }
        else if($fieldtype=="io")
        {
        echo "<input type='radio' name='response_".$index."' value='YES' id='YES".$index."'required><label for='YES".$index."'>YES</label>";
        echo "<input type='radio' name='response_".$index."' value='NO' id='NO".$index."'><label for='NO".$index."'>NO</label>";
        }
        else if($fieldtype=="file")
        {
          echo "<input type='file' name='response_".$index."' accept='.txt, .pdf' required/>";
        }
        else if($fieldtype=="radiobtn")
        {
          $limit=$count[$j];
          $i=0;
          while($i<$limit)
          {
            $value=$ARRA[$k];
           echo "<input type='radio' name='response_".$index."' value='".$value."' id='".$value."'/><label for='".$value."'>".$value."</label>"; 
           $i=$i+1;
           $k=$k+1; 
          }  
          $j=$j+1;
        }
        echo '<hr style="border: 2px solid red;"/>';
        $index= $index+1;
      }
      echo "<br><br><button type='submit' name='submitform'>Submit</button>";
      echo  "</form>";
      echo '<br><br><iframe src="https://www.facebook.com/plugins/share_button.php?href='.$link.'&layout=button_count&size=large&width=84&height=28&appId" width="84" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>';
      echo '<a href="'.$link.'" class="twitter-share-button" data-size="large" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
    }
    else
    {
      echo "<h2>SORRY</h2>";
      if($hourdiff >= $timelimit)
      {
      echo "<h3>Time Over</h3>";
      }
      else if($number >= $userlimit)
      {
      echo "<h3>User limit approached</h3>";
      }
    }
  }
}
?>
</body>
</html>