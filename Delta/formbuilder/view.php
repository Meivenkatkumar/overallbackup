<!DOCTYPE html>
<html>
<head>
    <title>Form Builder</title>
    <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body class="profile">
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
if(isset($_POST['viewbutton']) && !empty($_POST['viewform']) && isset($_SESSION['NAME']))
{
    require 'includes/db.php';
    $name=$_SESSION['NAME'];
    $formname=$_POST['viewform'];
    $formname=mysqli_real_escape_string($conn,$formname);
    $sql="SELECT * FROM structure WHERE formname='".$formname."'";
    if(!$conn->query($sql))
    {
        echo "problem";
    }
    $rows=$conn->query($sql);
    $row=$rows->fetch_assoc();
    $count=$row['count'];
    $sql="SELECT * FROM records WHERE creator='".$name."' AND formname='".$formname."' ORDER BY username";
    if(!$conn->query($sql))
    {
        echo "problem1";
    }
    $rows=$conn->query($sql);
    $data=array();
    $ind=0;
    $sum=0;
    $yescount=0;
    $nocount=0;
    while($row=$rows->fetch_assoc())
    {
     $arra[]=$row;
     $ind = $ind +1;
    }
    if($ind==0)
    {
        header("Location:profile.php?error=wrngaccess");
        exit();
    }
    $stmt=mysqli_stmt_init($conn);
    $index=0;
    echo "<br><br>";
    echo "<h1>Responses</h3>";
    echo "<table><tr><th>Name</th><th>Field</th><th>Description</th><th>Response</th></tr>";
    while($index<$ind)
    {
    $username=$arra[$index]['username'];
    $fieldname=$arra[$index]['fieldname'];
    $fieldtype=$arra[$index]['fieldtype'];
    $fielddesc=$arra[$index]['fielddesc'];
    $response=$arra[$index]['response'];
    if($index==0)
    {
        $check=$fieldname;
    }
    if($check==$fieldname)
       echo "<tr><td><h3>".$username."</h3></td>";
    else
       echo "<tr><td><h3></h3></td>";
    echo "<td><h4>".$fieldname."</h4></td><td><h4>".$fielddesc."</h4></td>";
     if($fieldtype=="text" || $fieldtype=="io" || $fieldtype=="num" || $fieldtype=="radiobtn")
     {
        echo "<td><h4>".$response."</h4></td>";
        if($fieldtype=="num")
        {
            $sum=$sum+(int)$response;
        }
     }
     else if($fieldtype=="image")
     {
        echo "<td><h4><a target='_blank' href='".$response."'>Image</a></h4></td>";
     }
     else if($fieldtype=="file")
     {
        echo "<td><h4><a target='_blank' href='".$response."'>Document</a></h4></td></tr>";
     }
    $index=$index+1;
    }
    echo '</table><hr style="border: 2px solid red;"/>';
    $sql="SELECT * FROM choices WHERE formname='".$formname."'";
    if(!$conn->query($sql))
    {
        echo "CHOICE PROBLEM";
    }
    $rows=$conn->query($sql);
    $pievalues=array();
    $pienames=array();
    $piechoices=array();
    while($row=$rows->fetch_assoc())
    {
        $pienames[]=$row['fieldname'];
        $pievalues[]=$row['count'];
        $piechoices[]=$row['choice'];
    }
    $sql="SELECT DISTINCT fieldname FROM records WHERE formname='".$formname."' AND fieldtype='io'";
    if(!$conn->query($sql))
     {
         echo "CHOICE PROBLEM1";
     }
     $rows=$conn->query($sql);
     $total=$rows->num_rows;
    $sql="SELECT * FROM records WHERE fieldtype='io' AND formname='".$formname."'";
     if(!$conn->query($sql))
     {
         echo "CHOICE PROBLEM1";
     }
     $rows=$conn->query($sql);
     $j=$rows->num_rows;
     $iovalues=array_fill(0,$j,0);
     $namefield=array();
     $namecheck="";
     $j=0;
     $index=0;
     while($row=$rows->fetch_assoc())
     {   
      if($index<$total)
      {
        $namefield[]=$row['fieldname'];
      }
      if($j==0)
      {
        $namecheck=$row['fieldname'];
      }
      if($namecheck==$row['fieldname'])
      {
        $j=0;
      }
      if($row['response']=='YES')
         $iovalues[$j]+=1;
      $j=$j+1;
      $index=$index+1;
     }
}
?>
<canvas></canvas>
<script>
    var count=<?php echo $count; ?>;
    var piechoices=<?php echo json_encode($piechoices); ?>;
    var pievalues=<?php echo json_encode($pievalues); ?>;
    var pienames=<?php echo json_encode($pienames); ?>;
    var namefield=<?php echo json_encode($namefield);?>;
    var iovalues=<?php echo json_encode($iovalues); ?>;
    var canvas=document.querySelector('canvas');
    canvas.width=500;
    if(pienames.length>3)
    {
        canvas.height=600+peinames.length*400;
    }
    else{
    canvas.height=1000;
    }
    var c = canvas.getContext('2d');
    var x=250;
    var r=100;
    var y=0;
    var index=0;
    var beginangle=0;
    var angle=0;
    var check="";
    var colorset=["#FF0000","#0000FF","#FFFF00 ","#32CD32","#191970","#FF8C00","#FF00FF","#808080","#F4A460","#800000"];
    var ind=0;
    var p=0,q=0,percent=0,percent0=0,percent1=0;
    if(pienames.length>0)
    {
        c.fillStyle="rgb(0,0,0)";
        c.font="30px verdana";
        c.fillText("Time for Analysis",150,y+40);
    }
    while(index<pienames.length)
    {   
        angle=(pievalues[index]/count)*2*Math.PI;
        percent=(pievalues[index]/count)*100;
        percent=Math.round(percent*10)/10;
        if(check!=pienames[index])
        {
            check=pienames[index];
            y=y+300;
            var beginangle=0;
            c.fillStyle="rgb(0,0,0)";
            c.font="30px verdana";
            c.fillText(check,210,y-120);
        }
        if(ind>9)
        { 
         ind=0;
        }
        c.fillStyle=colorset[ind];
        c.beginPath();
        c.fillRect(10,y-110+(ind*20),10,10);
        c.stroke();
        c.font="20px verdana";
        c.fillStyle="rgb(0,0,0)";
        c.fillText(piechoices[ind],30,y-100+(ind*20));
        c.fillStyle=colorset[ind];
        c.beginPath();
        c.moveTo(x,y);
        c.arc(x,y,r,beginangle,beginangle+angle);
        c.closePath();
        c.fill();
        c.stroke();
        if((beginangle+(angle/2)) > Math.PI)
        {  
        p=x+(r*0.60)*Math.cos(beginangle+(angle/2)-(15*Math.PI/180));
        q=y+(r*0.60)*Math.sin(beginangle+(angle/2)-(15*Math.PI/180));
        }
        else
        {
        p=x+(r*0.60)*Math.cos(beginangle+(angle/2)+(15*Math.PI/180));
        q=y+(r*0.60)*Math.sin(beginangle+(angle/2)+(15*Math.PI/180));
        }
        if(percent>0)
        {
        c.fillStyle="rgb(0,0,0)";
        c.font="bold 10px verdana";
        c.fillText(percent+"%",p,q);
        }
        beginangle=beginangle+angle;
        index=index+1;
        ind=ind+1;
    }
    index=0;
    y=y+150;
    while(index<namefield.length)
    {
        percent1=(iovalues[index]/count)*100;
        percent1=Math.round(percent1*10)/10;
        percent0=100-percent1;
        alert(percent1);
        alert(percent0);
        check=namefield[index];
        c.fillStyle="rgb(0,0,0)";
        c.font="30px verdana";
        c.fillText(check,210,y+40);

    
        c.font="20px verdana";
        c.fillStyle="rgb(0,0,0)";
        c.fillText("YES",10,y+70);
        
        c.beginPath();
        c.fillStyle="blue";
        c.fillRect(50,y+50,percent1*4,30);
        c.stroke();
        c.fillStyle="rgb(0,0,0)";
        c.font="bold 20px verdana";
        c.fillText(percent1+"%",(percent1*4)+55,y+80);

        c.font="20px verdana";
        c.fillStyle="rgb(0,0,0)";
        c.fillText("NO",10,y+110);

        c.fillStyle="red";
        c.beginPath();
        c.fillRect(50,y+90,percent0*4,30);
        c.stroke();
        c.fillStyle="rgb(0,0,0)";
        c.font="bold 20px verdana";
        c.fillText(percent0+"%",(percent0*4)+55,y+110);
        y=y+120;
        index=index+1;
    }
</script> 
</body>
</html>
