<?php
session_start();
require 'db.php';
if(isset($_SESSION['RECIEVER']))
{
  $recievername=$_SESSION['RECIEVER'];
  $chathistory="";
  $sender=$_SESSION['NAME'];
  $recievername=$_SESSION['RECIEVER'];
  $sql="SELECT * FROM courses WHERE name='".$recievername."'";
  $rows=$conn->query($sql);
  if($rows->num_rows==0)
  {
   $sql="SELECT * FROM chatlog WHERE (sender='".$sender."' OR reciever='".$sender."') AND (sender='".$recievername."' OR reciever='".$recievername."')";
   if(!$conn->query($sql))
    echo "pppp";
   $rows=$conn->query($sql);
   $number=$rows->num_rows;
   while($row=$rows->fetch_assoc())
   {
    if($row['sender']==$sender)
     echo "<div class='messageline'><div class='chat-right'>".$row['message']."</div></div>";
    else if($row['reciever']==$recievername)
     echo "<div class='messageline'><div class='chat-right'>".$row['message']."</div></div>";
    else if($row['reciever']==$sender)
     echo "<div class='messageline'><div class='chat-left'>".$row['message']."</div></div>";
   }
  }
  else{
    $sql="SELECT * FROM chatlog WHERE reciever='".$recievername."'";
    if(!$conn->query($sql))
      echo "pbpbppbpb";
    $rows=$conn->query($sql);
    while($row=$rows->fetch_assoc())
    {
      if($row['sender']==$sender)
        echo "<div class='messageline'><div class='chat-right'>".$row['message']."</div></div>";
      else
        echo "<div class='messageline'><div class='chat-left'><u><b>".$row['sender']."</b></u><br>".$row['message']."</div></div>";
    }
  }
}