<!DOCTYPE html>
<html>
<head>
	<title>Wallet Manager</title>
  <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
  <div id="logoutdiv">
<form action="includes/logout.php" method="post">
    <button type="submit" name="logout">Logout</button> 
</form>
  </div>
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
      if($_GET['error'] == "alrdyshrd")
      {
        echo "<h2>Already Shared with User</h2>";
      }
    }
  ?>
  <div id="view">
<?php
require 'includes/view.php';
?>
 </div>
<h2>Add your Expenses to the list</h2>
<form action="includes/add.php" method="post">
  <input type="text" name="expan" placeholder="expense_name"/>
  <input type="text" name="expad" placeholder="expense_description"/>
  <input type="number" name="expaa" placeholder="Rupees"/>
  <button type="submit" name="add">Add Expense</button>
</form>
<br>
<h2>Save your Budget</h2>
<form action="includes/addbudget.php" method="post">
  <input type="number" name="abudg" placeholder="Rupees"/>
  <button type="submit" name="addbudget">Your Budget</button>
</form>
<br>
<h2>Remove an Expense from the list</h2>
<form action="includes/delete.php" method="post">
  <input type="number" name="expid" placeholder="expense_ID"/>
  <input type="text" name="expdn" placeholder="expense_name"/>
  <button type="submit" name="delete">Delete Expense</button>
</form>
<br>
<h2>You can share your Expenses with others</h2>
<form action="includes/share.php" method="post">
  <input type="number" name="expids" placeholder="expense_ID"/>
  <input type="text" name="expns" placeholder="expense_name"/>
  <input type="text" name="reciever" placeholder="Username"/>
  <button type="submit" name="share">Share Expense</button>
</form>
<div id="shareview">
<?php
require 'includes/shareview.php';
?>
</div>
<div id="settle">
<form action="includes/share.php" method="post">
  <input type="text" name="frndname" placeholder="Friend`s_name"/>
  <input type="number" name="expidcont" placeholder="expense_ID"/>
  <input type="text" name="expnamecont" placeholder="expense_name"/>
  <input type="number" name="expamtcont" placeholder="expense_contribute"/>
  <button type="submit" name="sharecont">Settle Up</button>
</form>
</div>
</body>
</html>
	