<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<form action="includes/logout.php" method="post">
    <button type="submit" name="logout">Logout</button> 
</form>
<?php
require 'view.php';
?>
<form action="includes/add.php" method="post">
  <input type="text" name="expan" placeholder="expense_name"/>
  <input type="text" name="expad" placeholder="expense_description"/>
  <input type="number" name="expaa" placeholder="Rupees"/>
  <button type="submit" name="add">Add the Expense</button>
</form>
<br>
<br>
<form action="includes/delete.php" method="post">
  <input type="text" name="expdn" placeholder="expense_name"/>
  <button type="submit" name="add">Add the Expense</button>
</form>
</body>
</html>
	