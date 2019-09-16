<?php
session_start();
include 'db.php';
$date = new DateTime("NOW");
$timestamp=date_timestamp_get($date);
$sql="UPDATE users SET lastlogin=".$timestamp." WHERE name='".$_SESSION['NAME']."'";
$conn->query($sql);
$sql="UPDATE users SET loginstatus=0 WHERE name='".$_SESSION['NAME']."'";
if($conn->query($sql))
	header("Location:../index.php?logout=successful");
session_unset();
session_destroy();

?>
