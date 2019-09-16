<?php
session_start();
include 'includes/db.php';
$name=$_SESSION['NAME'];
$type=$_SESSION['TYPE'];
if(isset($_POST['testbtn']))
{
 $_SESSION['courseid']=$_POST['testbtn'];
 echo $_SESSION['courseid'];
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
</head>
<body style="text-align: center;">
<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
 <a class="navbar-brand" href="profile.php"><img src="includes/logo.png" height="50" width="250"></a>
 <div class="container-fluid">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
  <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar navbar-collapse" id="navbarResponsive" style="padding:0px 30px;">
    <ul class="navbar-nav ml-auto">
     <li class="nav-item">
      <a class="nav-link" href="profile.php">Home</a>
     </li>
     <li class="nav-item">
      <a class="nav-link" href="details.php">Courses</a>
     </li>
     <?php
     if($type=="teacher")
     {
      echo ' <li class="nav-item">
      <a class="nav-link" href="new.php" active>New Courses</a>
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
<div class="container-fluid">
  <div class="row">
    <div class="col">
    </div>
    <div class="col-9">
      <div id="testbuilder">
  <div id="teststate" class="text-center"></div>
  <div class="input-group"><div class='input-group-prepend input-group-text justify-content-center' style="margin:30px auto;">
    <input type="text" placeholder="Test Name" class="form-control" style="float:none;max-width:350px;" id="testtitle" onkeyup="validate(this.value)" required>
   <span class="input-group-btn">
        <button type="submit" class="btn btn-primary btn-md" onclick="startbuilding()">Start Building</button>
   </span>
 </div>
</div>
  <div id="startbuilding" style="margin-top: 20px;"></div>
</div>
    </div>
    <div class="col">
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
var status=0,qnumber=1,optionnumber=1;
function validate(val)
{
var xml=new XMLHttpRequest();
xml.open("GET","includes/validate.php?valid="+val,true);
xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    	console.log(this.responseText);
      if(this.responseText=="Try different name")
      {
        status=0;
      	console.log("111");
      	document.getElementById("teststate").innerHTML = this.responseText;
      }
      if(this.responseText=="Valid name")
      { console.log("pass");
      	document.getElementById("teststate").innerHTML="";
         status=1;
      }
    }
 };
xml.send();
}
function startbuilding(){
if(status==1)
 {
   var title=document.getElementById("testtitle").value;
   var xml= new XMLHttpRequest();
   xml.open("POST","includes/validate.php",true);
   var param="testname="+title;
   xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
   xml.send(param);
   document.getElementById("startbuilding").innerHTML="<div id='qdiv' class='form-group'><input type='number' placeholder='Paper Mark' onkeyup='uploadscore(this.value)' min=10><div id='qdiv_"+qnumber+"' class='form-input'><input type='number' id='marks_"+qnumber+"' min=1 placeholder='Question Mark'><input type='text' id='"+qnumber+"' placeholder='Question "+qnumber+"' onkeyup='uploadquestion(this)'><div id='optiondiv_"+qnumber+"' class='form-group row'><div class='col-xs-2'><input type='text' id='"+qnumber+"_"+optionnumber+"' placeholder='option "+optionnumber+"' onkeyup='uploadoption(this)' class='form-control'></div></div><div id='optionbtn_"+qnumber+"' class='btn-group'><button type='submit' value='"+qnumber+"_"+optionnumber+"' onclick='addoption(this.value)' id='addoptbtn_"+qnumber+"_"+optionnumber+"' class='btn btn-primary'>Add Option</button><button type='submit' value='"+qnumber+"_"+optionnumber+"' id='deleteoptbtn_"+qnumber+"_"+optionnumber+"' onclick='deleteoption(this.value)' class='btn btn-danger'>Delete Option</button></div></div></div><div id='newquestiondiv'><button type='submit' onclick='addquestion()'>Add Question</button><button type='submit' onclick='answerscript()'>Answer Script</button></div>";
 }
else
	alert("Try different Testname");
}
function addoption(val){
var optnumber=val.split("_");
var question=parseInt(optnumber[0]);
var option=parseInt(optnumber[1]);
option=option+1;
var string="optiondiv_"+question;
var optiondiv = document.getElementById(string);
var newoption="<div class='col-xs-2'><input type='text' id='"+question+"_"+option+"' onkeyup='uploadoption(this)' class='form-control'></div>";
optiondiv.insertAdjacentHTML("beforeend",newoption); 
string="optionbtn_"+question;
document.getElementById(string).innerHTML="<button type='submit' value='"+question+"_"+option+"' onclick='addoption(this.value)' id='addoptbtn_"+question+"_"+option+"' class='btn btn-primary'>Add Option</button><button type='submit' value='"+question+"_"+option+"' onclick='deleteoption(this.value)' class='btn btn-danger' id='addoptbtn_"+question+"_"+option+"'>Delete Option</button>";
console.log(document.getElementById(string).innerHTML);
}

function addquestion(){
qnumber=qnumber+1;
optionnumber=1;
var qdiv=document.getElementById("qdiv");
var newquestion="<div id='qdiv_"+qnumber+"' class='form-input'><input type='number' id='marks_"+qnumber+"' min=1 placeholder='Question Mark'><input type='text' id='"+qnumber+"' placeholder='Question "+qnumber+"' onkeyup='uploadquestion(this)'><div id='optiondiv_"+qnumber+"' class='form-group row'><div class='col-xs-2'><input type='text' id='"+qnumber+"_"+optionnumber+"' onkeyup='uploadoption(this)' class='form-control'></div></div><div id='optionbtn_"+qnumber+"' class='btn-group'><button type='submit' value='"+qnumber+"_"+optionnumber+"' class='btn btn-primary' id='addoptbtn_"+qnumber+"_"+optionnumber+"' onclick='addoption(this.value)'>Add Option</button><button type='submit' value='"+qnumber+"_"+optionnumber+"' onclick='deleteoption(this.value)' class='btn btn-danger' id='deleteoptbtn_"+qnumber+"_"+optionnumber+"'>Delete Option</button></div></div>";
qdiv.insertAdjacentHTML("beforeend",newquestion);
}
function deleteoption(val){
 var ids=val.split("_");
 var optionid=ids[1];
 var questionid=ids[0];
 console.log(questionid+"_"+optionid);
 var xml= new XMLHttpRequest();
 xml.open("POST","includes/validate.php",true);
 var param="questionid="+questionid+"&optionid="+optionid+"&deleteoption=1";
 xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xml.send(param);
 var elem = document.getElementById(questionid+"_"+optionid);
 elem.parentNode.removeChild(elem);
 newoptionid=optionid-1;
 document.getElementById("addoptbtn_"+questionid+"_"+optionid).id="addoptbtn_"+questionid+"_"+newoptionid;
 document.getElementById("deleteoptbtn_"+questionid+"_"+optionid).id="deleteoptbtn_"+questionid+"_"+newoptionid;
 document.getElementById("addoptbtn_"+questionid+"_"+newoptionid).value=questionid+"_"+newoptionid;
 document.getElementById("deleteoptbtn_"+questionid+"_"+newoptionid).value=questionid+"_"+newoptionid;
 
}
function uploadscore(val){
 var totalmarks=val;
 var xml= new XMLHttpRequest();
 xml.open("POST","includes/validate.php",true);
 var param="totalmarks="+totalmarks;
 xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xml.send(param);
}
function uploadquestion(val){
 var questionid=parseInt(val.id);
 var question=val.value;
 console.log(question);
 var questionmarks=document.getElementById("marks_"+questionid).value;
 var xml= new XMLHttpRequest();
 xml.open("POST","includes/validate.php",true);
 var param="question="+question+"&questionid="+questionid+"&questionmarks="+questionmarks;
 xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xml.send(param);
}
function uploadoption(val){
 var idarray=val.id;
 var ids=idarray.split("_");
 var optionid=ids[1];
 var questionid=ids[0];
 var option=val.value;
 var xml= new XMLHttpRequest();
 xml.open("POST","includes/validate.php",true);
 var param="questionid="+questionid+"&optionid="+optionid+"&option="+option;
 xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xml.send(param);
}
function answerscript(){
  document.getElementById('testbuilder').innerHTML="";
  var xml=new XMLHttpRequest();
  xml.open("GET","includes/validate.php?answerscript=answerscript",true);
  xml.onload=function(){
  	document.getElementById("testbuilder").innerHTML=this.responseText;
  }
  xml.send();
}
</script>
</body>
</html>