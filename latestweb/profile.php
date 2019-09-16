<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
session_start();
include 'includes/db.php';
if(!isset($_SESSION['NAME']))
{
  header("Location:startup.php?error=wrngaccess");
  exit();
}
if(isset($_POST['testtestname']))
{
  $_SESSION['testtestname']=$_POST['testtestname'];
  $_SESSION['testcourseid']=$_POST['testcourseid'];
  $_SESSION['testtestduration']=$_POST['testtestduration'];
  header("Location:testpage.php");
  exit();
}
if(isset($_POST['testdate']))
{
 $date=$_POST['testdate'];
 $time=$_POST['testtime'];
 $testduration=$_POST['testduration'];
 $courseid=$_SESSION['courseid'];
 $testname=$_SESSION['testname'];
$dattime=strval(strval($date)." ".strval($time.":00"));
 $sql="UPDATE testlist SET testduration=".$testduration.", testdate='".$dattime."' WHERE testname='".$testname."' AND courseid=".$courseid;
 $conn->query($sql);
 $sql="SELECT * FROM questionlist WHERE testname='".$testname."' AND courseid=".$courseid;
 $rows=$conn->query($sql);
 $qids=array();
 $marks=array();
 while($row=$rows->fetch_assoc())
 {
   $qid=$row['questionid'];
   $mark=$row['questionmarks'];
   array_push($qids,$qid);
   array_push($marks,$mark);
   echo $qid.":".$mark."<br>";
 }
 $i=0;
 foreach($qids as $questionid)
 {
   $choice=$_POST[$questionid];
   echo $choice;
   $sql="INSERT INTO answersscript (testname,courseid,qid,choice,questionmarks) VALUES ('".$testname."','".$courseid."',".$questionid.",'". $choice."',".$marks[$i].")";
   $conn->query($sql);
 $i+=1;
 }
}
if(isset($_SESSION['redirect']))
{
 header("Location:".$_SESSION['redirect']);
 exit();
}
if(!isset($_SESSION['NAME']))
{
   header("Location:index.php?error=wrngaccess");
   exit();  
}
$name=$_SESSION['NAME'];
$type=$_SESSION['TYPE'];
$class=$_SESSION['CLASS'];
if(isset($_REQUEST['deny']))
{
  $sql="DELETE FROM courses WHERE id=".$_REQUEST['deny'];
 if(!$conn->query($sql))
  echo "problem";
}
if(isset($_REQUEST['auth']))
{
 $sql="UPDATE courses SET state='active' WHERE id=".$_REQUEST['auth'];
 if(!$conn->query($sql))
  echo "problem";
}
if(isset($_REQUEST['assignment']))
{
  $id=$_REQUEST['assignment'];
  echo $id;
  $file=$_FILES['data'];
  $studentname=$_POST['assignmentstudentname'];
  echo $studentname;
  $filename=$file['name'];
  echo $filename;
  $filearray=explode('.',$filename);
  $filetype=strtolower(end($filearray));
  $file=$file["tmp_name"];
  $uniq=uniqid();
  echo $filetype;
  $response="documents/".$uniq.".".$filetype;
  move_uploaded_file($file, $response);
  $sql="INSERT INTO assignments (data,courseid,studentname) VALUES ('".$response."',".$id.",'".$studentname."')";
  if(!$conn->query($sql))
    echo "tiktiktik";
}
if(isset($_POST['reciever']))
{
  $recievername=$_POST['reciever'];
  $_SESSION['RECIEVER']=$recievername;
}
if(isset($_REQUEST['join']))
{
  $id=$_REQUEST['join'];
  $sql="SELECT * FROM courses WHERE id=".$_REQUEST['join'];
  if(!$conn->query($sql))
  {
    echo "pblm 1";
  }
  $rows=$conn->query($sql);
  $row=$rows->fetch_assoc();
  echo $row['name'].$name.$id;
 $sql="INSERT INTO attendance (coursename,studentname,id) VALUES ('".$row['name']."','".$name."','".$id."')";
 if(!$conn->query($sql))
  { echo "pblm 2";}
}
if(isset($_REQUEST['reject']))
{
  $id=$_REQUEST['reject'];
  $studentname=$_POST['studentname'];
  $sql="DELETE FROM attendance WHERE state='request' AND studentname='".$studentname."'";
 if(!$conn->query($sql))
  { echo "pbl 2";}
}
if(isset($_REQUEST['accept']))
{
  $id=$_REQUEST['accept'];
  $studentname=$_POST['studentname'];
 $sql="UPDATE attendance SET state='active' WHERE id=".$id." AND studentname='".$studentname."'";
 if(!$conn->query($sql))
  { echo "pbl 2";}
}
if(isset($_POST['chatreciever']))
{
  $chatreciever=$_POST['chatreciever'];
  $chatsender=$_POST['chatsender'];
  $chatmessage=$_POST['chatmessage'];
  $sql="INSERT INTO chatlog (sender,reciever,message) VALUES ('".$chatsender."','".$chatreciever."','".$chatmessage."')";
  if(!$conn->query($sql))
    echo "failed";
}
if(isset($_POST['uploadimage'])){
  $image=$_POST['uploadimage'];
  $image = str_replace('data:image/jpeg;base64,', '', $image);
  $image = str_replace(' ', '+', $image);
  $fileData = base64_decode($image);
  $sql="SELECT * FROM users WHERE name='".$name."'";
  $rows=$conn->query($sql);
  $row=$rows->fetch_assoc();
  $picturename=$row['picture'];
  if($picturename===null)
  {
  $picturename=uniqid();
  $picturename="images/".$picturename.".jpeg";
  $sql="UPDATE users SET picture='".$picturename."' WHERE name='".$name."'";
  $conn->query($sql);
  }
  file_put_contents($picturename, $fileData);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Online School</title>
   <script src="https://kit.fontawesome.com/99682bc45a.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
  <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body class="profile">
<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
 <a class="navbar-brand" href="profile.php"><img src="includes/logo.png" height="50" width="250"></a>
 <div class="container-fluid">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
  <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar navbar-collapse" id="navbarResponsive" style="padding:0px 30px;">
    <ul class="navbar-nav ml-auto">
     <li class="nav-item active">
      <a class="nav-link" href="profile.php">Home</a>
     </li>
     <li class="nav-item">
      <a class="nav-link" href="details.php">Courses</a>
     </li>
     <?php
     if($type=="teacher")
     {
      echo ' <li class="nav-item">
      <a class="nav-link" href="new.php">New Courses</a>
     </li>';
     }
     ?>
     <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php 
       $sql="SELECT * FROM users WHERE name='".$name."'";
       $rows=$conn->query($sql);
       while($row=$rows->fetch_assoc())
       {
        $picture=$row['picture'];
        if($picture==null)
        {
          if($row['gender']=="male")
            $picture="includes/0.jpeg";
          else 
            $picture="includes/1.jpg";
        }
        echo "<img src='".$picture."' class='rounded-circle' alt='you' height='30' width='30'>  ".$_SESSION['NAME'];
       }
      ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Change Password</a>
          <a class="dropdown-item" href="javascript:{}" onclick="document.getElementById('logout').click();">Logout</a>
    </ul>
</div>
 </div>
</nav>
<form action="includes/logout.php" method="post" style="display:none;">
    <button type="submit" name="logout" id="logout">Logout</button> 
</form>
  <!-- <div id="slides" class="carousel slide" data-ride="carousel">
    <ul class="carousel-indicators">
      <li data-target="#slides" data-slide-to="0" class="active"></li>
      <li data-target="#slides" data-slide-to="1"></li>
      <li data-target="#slides" data-slide-to="2"></li>
    </ul>
    <div class="carousel-inner">
      <div class="carousel-item">
        <img src="includes/bg1.png">
      </div>
       <div class="carousel-item">
        <img src="includes/bg2.jpg">
      </div>
       <div class="carousel-item">
        <img src="includes/bg3.jpg">
      </div>
      <div class="carousel-item">
        <img src="includes/bg4.png">
      </div>
    </div>
  </div> -->
  <?php
if(isset($_GET['error']))
    {
      if($_GET['error'] == "empty")
      {
        echo "<h2>Fill all the fields</h2>";
      }
      if($_GET['error'] == "noentry")
      {
        echo "<h2>Invalid Entry</h2>";
      }
      if($_GET['error'] == "dberror")
      {
        echo "<h2>try again after sometime</h2>";
      }
      if($_GET['error'] == "nametaken")
      {
        echo "<h2>Try Different Name</h2>";
      }
      if($_GET['error']=="wrngaccess")
      {
        echo "<h2>Access Denied</h2>";
      }
      if($_GET['error']=="another")
      {
        echo "<h2>Finish Launching current form</h2>";
      }
    }
if(isset($_GET['examover']))
{
  echo "Your answers were recorded successfully";
  $timearray=json_decode($_SESSION['uploadtime']);
  $testname=$_SESSION['testtestname'];
  $courseid=$_SESSION['testcourseid'];
  $name=$_SESSION['NAME'];
  $sql="SELECT qid FROM answerrecords WHERE testname=? AND courseid=? AND studentname=? ORDER BY qid ASC";
  $QID=array();
  $stmt=mysqli_stmt_init($conn);
  if(mysqli_stmt_prepare($stmt,$sql))
  {
  mysqli_stmt_bind_param($stmt,"sis",$testname,$courseid,$name);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_bind_result($stmt,$questionid);
   $rows=$conn->query($sql);
   while(mysqli_stmt_fetch($stmt))
   {
     array_push($QID, $questionid);
   }
   $i=0;
   foreach ($QID as $qid) {
     $sql="UPDATE answerrecords SET timetaken=".$timearray[$i]." WHERE qid=".$qid." AND testname='".$testname."' AND courseid=".$courseid." AND studentname='".$name."'";
     $conn->query($sql);
     $i=$i+1;
   }
 }
}
  ?>

  <div class="container-fluid">
  <div class="row border-between">
    <div class="col-sm-3"  id="columnleft">
     <?php
     $sql="SELECT * FROM users WHERE name='".$name."'";
 $rows=$conn->query($sql);
 while($row=$rows->fetch_assoc())
 {
  $picture=$row['picture'];
  if($picture==null)
  {
    if($row['gender']=="male")
      $picture="includes/0.jpeg";
    else
      $picture="includes/1.jpg";
  }
  echo "<div class='imgcontain'>
<img id='profilepic' src='".$picture."' class='img-thumbnail float-left' height='250' width='250'> <div class='imgoverlay'></div>
  <div id='imgbutton'><a href='takepicture.php'>Change Photo</a></div></div>";
 }
 ?>
 <div id="chatlist">
 <?php
     if($type=="student")
   $sql="SELECT * FROM users WHERE name<>'".$name."' AND type<>'student'";
 else if($type=="teacher")
   $sql="SELECT * FROM users WHERE name<>'".$name."'";
 else if($type=="admin")
   $sql="SELECT * FROM users WHERE name<>'".$name."'";
 $rows=$conn->query($sql);
 while($row=$rows->fetch_assoc()){
  if($row['loginstatus']==1)
  {
    if($row['picture']==null)
    {
      if($row['gender']=="male")
      {
        $picture="includes/0.jpeg";
      }
      else
      {
        $picture="includes/1.jpg";
      }
    }
    else
      $picture=$row['picture'];
     echo "<button type='submit' value='".$row['name']."' onclick='chatname(this.value)' class='btn btn-primary btn-block'><p class='chatdesc'><img src='".$picture."' class='rounded-circle' alt='pic' height='50' width='50'><bold>".$row['name']."</bold>online</p></button>";
  } 
  else if($row['loginstatus']==0)
  {
    if($row['picture']==null)
    {
      if($row['gender']=="male")
        $picture="includes/0.jpeg";
      else
        $picture="includes/1.jpg";
    }
    else
      $picture=$row['picture'];
    $date = new DateTime("NOW");
    $nowtime=date_timestamp_get($date);
    $ts2 = strtotime($row['lastlogin']);
    $lastseen=$nowtime-$ts2;
    $lastseenstring="";
    if($lastseen<60)
      $lastseenstring=$lastseen." sec";
    elseif($lastseen<3600)
      $lastseenstring=strval(floor($lastseen/60))." mins";
    elseif($lastseen<86400)
      $lastseenstring=strval(floor($lastseen/3600))." hours";
    else
      $lastseenstring=strval(floor($lastseen/86400))." days";
     echo "<button type='submit' value='".$row['name']."' onclick='chatname(this.value)' class='btn btn-primary btn-block' style='padding:auto;'><p class='chatdesc'><img src='".$picture."' class='rounded-circle' alt='pic' height='50' width='50'><bold>".$row['name']."</bold> last seen ".$lastseenstring." ago</p></button>";
  }
 }
 if($type=="student")
   $sql="SELECT t1.name FROM courses t1,attendance t2 WHERE t1.state='active' AND t2.studentname='".$name."' AND t1.id=t2.id";
 if($type=="admin")
   $sql="SELECT name FROM courses WHERE state='active'";  
 if($type=="teacher")
   $sql="SELECT name FROM courses WHERE state='active' AND teacher='".$name."'";
 $stmt=mysqli_stmt_init($conn);
 mysqli_stmt_prepare($stmt,$sql);
 mysqli_stmt_execute($stmt);
 mysqli_stmt_store_result($stmt);
 mysqli_stmt_bind_result($stmt,$groupname);
if(mysqli_stmt_num_rows($stmt)>0)
{
  echo "<br>Class Chat<br>";
 while(mysqli_stmt_fetch($stmt))
 {
   echo "<button type='submit' value='".$groupname."' onclick='chatname(this.value)' class='btn btn-primary btn-block'>".$groupname."</button>";
 }
} 
     ?>
</div>
    </div>
    <div class="col-sm" id="columncenter">
    <?php 
    $sql="SELECT t1.data,t1.type,t2.coursename,t2.id FROM messages t1,attendance t2 WHERE t2.studentname='".$name."' AND t1.id=t2.id";
 $stmt=mysqli_stmt_init($conn);
 if(!mysqli_stmt_prepare($stmt,$sql)) echo "Not Found";
 mysqli_stmt_execute($stmt);
 mysqli_stmt_bind_result($stmt,$data,$datatype,$coursename,$courseid);
 while(mysqli_stmt_fetch($stmt))
 {  
    if($datatype=="assignment")
    {
      $data1=$data;
      $data1=str_replace("<<","&lt;b&gt;", $data1);
    $data1=str_replace(">>","&lt;/b&gt", $data1);
    $data1=str_replace("<<<","&lt;i&gt;", $data1);
    $data1=str_replace(">>>","&lt;/i&gt", $data1);
    $data1=htmlspecialchars_decode($data1);
    echo "Assignment on ".$coursename." ".$data1."<form method='post' action='profile.php' enctype='multipart/form-data'><input type='file' class='form-control' style='min-height:50px;;' name='data' accept='.txt,.pdf'><br><br><button type='submit' name='assignment' class='btn btn-primary' value='".$courseid."'>Submit</button><input type='hidden' name='assignmentstudentname' value='".$name."'></form>";
    }
  }
  ?>
       <div id="detailedview"></div>
    </div>
    <div class="col-sm-3" id="newsdiv">
      <form>
    <input type="text" name="news" id="news" style="float: none;" placeholder="NEWS Title" onkeyup="searchnews()"required>
    <br><br>
    <button type="submit">Search</button>
  </form>
    <br>
      <button type="submit" class="newstitlebtn" name="source" value="CNN" onclick="searchnews(this.value)">CNN</button>
      <button type="submit" class="newstitlebtn" name="source" value="fox-news" onclick="searchnews(this.value)">Fox News</button>
      <button type="submit" class="newstitlebtn" name="source" value="google-news-in" onclick="searchnews(this.value)">Google News</button>
      <button type="submit" class="newstitlebtn" name="source" value="the-wall-street-journal" onclick="searchnews(this.value)">Wall Street</button>
      <button type="submit" class="newstitlebtn" name="source" value="bbc-news" onclick="searchnews(this.value)">BBC News</button>
      <button type="submit" class="newstitlebtn" name="category" value="business" onclick="searchnew(this.value)">Business</button>
      <button type="submit" class="newstitlebtn" name="category" value="entertainment" onclick="searchnew(this.value)">Entertainment</button>
      <button type="submit" class="newstitlebtn" name="category" value="science" onclick="searchnew(this.value)">Science</button>
      <button type="submit" class="newstitlebtn" name="category" value="technology" onclick="searchnew(this.value)">Technology</button>
      <button type="submit" class="newstitlebtn" name="category" value="sports" onclick="searchnew(this.value)">Sports</button>
  <br>
<br>
<div id="view" class="overflow-auto">
</div>
    </div>
  </div>
</div>
<div class="fixed-bottom" id="fullchat">
  <div id="chat">
    <div id="chathead"></div>
    <div id="chatbody">
    <div id="chatlog"></div>
    <div id="chatinput"></div>
  </div>
  </div>
</div>
 <?php
if(file_exists('includes/personalised.json'))
{
   $variables=file_get_contents('includes/personalised.json');
   $variables=json_decode($variables,true);
}
 ?>
}
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript">
var height=document.getElementById("chatbody").clientHeight;
var chathead=document.getElementById("chathead");
chathead.addEventListener("click",function(){
  console.log("working");
 var chatbody=document.getElementById("fullchat");
 console.log(chatbody.clientHeight)
  if(chatbody.clientHeight>100)
  {
     document.getElementById("fullchat").style.height="32px";
     document.getElementById("chatinput").style.visibility="hidden";
    console.log(document.getElementById("fullchat").clientHeight);
  }
  else
  {
     document.getElementById("chatinput").style.visibility="visible";
     document.getElementById("fullchat").style.height="400px";
  }

});
var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
    var data={
    "reciever":"server_checkinitial",
    "sender":"<?php echo $_SESSION['NAME'];?>"
  }
  conn.send(JSON.stringify(data));
};
conn.onmessage = function(e) {
    console.log(e.data);
    var data=JSON.parse(e.data);
    var sender=<?php echo json_encode($_SESSION['NAME']);?>;
    if(data['sender']==sender)
     var text="<p class='chat-right'>"+data['message']+"</p>";
    else if(data['sender']==reciever)
    {
     var text="<p class='chat-left'>"+data['sender']+data['message']+"</p>";
     chatname(reciever);
    }
    document.getElementById("chatlog").insertAdjacentHTML("beforend",text);
};
var reciever="";
document.getElementById("fullchat").style.display="none";
function chatname(val){
 reciever=val;
 var xml=new XMLHttpRequest();
 xml.open('POST','profile.php',true);
 var param="reciever="+reciever;
 xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xml.send(param);
 var xml=new XMLHttpRequest();
 xml.open('GET','includes/chathistory.php',true);
 xml.onload=function(){
   var chathistory=this.responseText;
   document.getElementById("fullchat").style.display="block";
   document.getElementById("chathead").innerHTML="<h4 class='chattitle' style='color:rgb(255,255,255);'>"+reciever+"</h4>";
   document.getElementById("chatlog").innerHTML=chathistory;
   document.getElementById("chatinput").innerHTML='<input type="text" id="textmessage"><button type="submit" class="btn btn-primary" id="textbtn" onclick="sendtext()"><i class="far fa-paper-plane"></i></button>';
   console.log(chathistory);
 }
 xml.send();
 
}
function sendtext(){
  var message=document.getElementById('textmessage').value;
  var sender=<?php echo json_encode($_SESSION['NAME']);?>;
  console.log(sender);
  var data={
    "reciever":reciever,
    "message":message,
    "sender":sender
  }
  conn.send(JSON.stringify(data));
  var xml =new XMLHttpRequest();
  xml.open('POST','profile.php',true);
  var param="chatreciever="+reciever+"&chatsender="+sender+"&chatmessage="+message;
  xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  xml.send(param);
  chatname(reciever);
}
  var personalarray = <?php echo json_encode($variables[$name]);?>;
  console.log(personalarray);
  if(personalarray==null)
  {
    console.log("thisthisthis");
    var xml =new XMLHttpRequest();
    xml.open('GET','https://newsapi.org/v2/top-headlines?country=us&apiKey=0b225379335c4c80af67088fb4b1f3f5',true);
    xml.onload=function(){
    console.log("hi");
    var content =JSON.parse(this.responseText);
    var output="";
    var i=0;
    while(i<=9)
    {
    output+="<button class='btn btn-secondary btn-lg btn-block newsbtn'><div class='image-wrapper float-left pr-3'><img class='newsimage' src='"+content.articles[i].urlToImage+"' width='150' height='150'></div>"+content.articles[i].source.name+":"+content.articles[i].title+"<a href='"+content.articles[i].url+"'> For more details</a></p>";
    i+=1;
    }
    document.getElementById('view').innerHTML=output;
    }
    xml.send();
  }
  else
  { 
    const keysSorted = Object.keys(personalarray).sort(function(a,b){return personalarray[a]-personalarray[b]})
  keysSorted.reverse();
  // console.log(keysSorted);
  const arr = [];
  const flow=[];
  var sum=0;
  for (let i=0; i<keysSorted.length;i++) {
   const obj = {};
   obj.per= keysSorted[i];
   obj.val= personalarray[keysSorted[i]];
   flow.push(obj.val);
   sum+=obj.val;
   arr.push(obj);
}
    for(var key in arr)
    {
      var xml=new XMLHttpRequest();
      if(arr[key]["per"]=="bbc-news" || arr[key]["per"]=="fox-news" || arr[key]["per"]=="google-news-in" || arr[key]["per"]=="CNN" || arr[key]["per"]=="the-wall-street-journal"){
       xml.open('GET','https://newsapi.org/v2/top-headlines?sources='+arr[key]["per"]+'&apiKey=0b225379335c4c80af67088fb4b1f3f5',true);
      }
      else{
       xml.open('GET','https://newsapi.org/v2/top-headlines?category='+arr[key]["per"]+'&apiKey=0b225379335c4c80af67088fb4b1f3f5',true);
      }
      xml.onload=function()
      {
      var content=JSON.parse(this.responseText);
      var i=0;
      var output="";
       while((i<3)&&(i<content.totalResults))
       {
        flow.push(arr[key]["per"]);
        output+="<button class='btn btn-secondary btn-lg btn-block newsbtn'><div class='image-wrapper float-left pr-3'><img class='newsimage' src='"+content.articles[i].urlToImage+"' width='100' height='100' ></div>"+content.articles[i].source.name+":"+content.articles[i].title+"<a class='moredetails' href='"+content.articles[i].url+"'> more...</a></button>";
          i+=1;
          // document.getElementById('view').innerHTML+="<a href='"+content.articles[i].url+"'>For more details</a>";
       }
       document.getElementById('view').innerHTML+=output;
      }
      xml.send();
    }
  }

function searchnews(value){
  var source=value;
  console.log(source);
  xml.open('GET','https://newsapi.org/v2/top-headlines?sources='+source+'&apiKey=0b225379335c4c80af67088fb4b1f3f5',true);
  xml.onload=function(){
    console.log("hi");
    var content =JSON.parse(this.responseText);
    var output="";
    var i=0;
    while(i<=9)
    {
    output+="<button class='btn btn-secondary btn-lg btn-block newsbtn'><div class='image-wrapper float-left pr-3'><img class='newsimage' src='"+content.articles[i].urlToImage+"' width='150' height='150'></div>"+content.articles[i].source.name+":"+content.articles[i].title+"<a href='"+content.articles[i].url+"'> For more details</a></button>";
    i+=1;
    }
    document.getElementById('view').innerHTML=output;
  }
  xml.send();
  xml=new XMLHttpRequest();
  xml.open('POST','includes/personalised.php',false);
  xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  var param="source="+source;
  console.log(source);
  xml.send(param);
}
function searchnew(value){
  var category=value;
  console.log(category);
  xml.open('GET','https://newsapi.org/v2/top-headlines?category='+category+'&apiKey=0b225379335c4c80af67088fb4b1f3f5',true);
  xml.onload=function(){
    var content =JSON.parse(this.responseText);
    console.log(content);
    var output="";
    var i=0;
    while(i<=9)
    {
    output+="<button class='btn btn-secondary btn-lg btn-block newsbtn'><div class='image-wrapper float-left pr-3'><img class='newsimage' src='"+content.articles[i].urlToImage+"' width='150' height='150'></div>"+content.articles[i].source.name+":"+content.articles[i].title+"<a href='"+content.articles[i].url+"'> For more details</a></button>";
    i+=1;
    }
    document.getElementById('view').innerHTML=output;
  }
  xml.send();
  xml=new XMLHttpRequest();
  xml.open('POST','includes/personalised.php',false);
  xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  var param="source="+category;
  console.log(category);
  xml.send(param);
}
function loadphp() {
  var xhttp = new XMLHttpRequest();
  xhttp.open('GET', 'form.php', true);
  xhttp.onload=function(){
  if(this.status==200)
  {
    document.getElementById('detailedview').innerHTML=this.responseText;
  }
 }
 xhttp.send();
}
window.setInterval(loadphp, 1500);
 </script>
</body>
</html>
	