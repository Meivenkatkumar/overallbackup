<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style1.css">
	<title>
		My Online School
	</title>
</head>
<body>
<?php
  if(isset($_SESSION['NAME']))
  {
  header("Location:profile.php?login=success");
  exit();
  }
  else
  {
    require 'startup.php'; 
  }
?>
</body>
</html>