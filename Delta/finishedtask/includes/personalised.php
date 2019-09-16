<?php
session_start();
$name=$_SESSION['NAME'];
if(isset($_POST['source']))
{
  $source=$_POST['source'];
  echo "111";
  if(file_exists('personalised.json'))
  {
   $variables=file_get_contents('personalised.json');
   $variables=json_decode($variables,true);
   if(empty($variables))
   {
    $variables=array();
   }
   if(array_key_exists($name,$variables))
   {
    if(array_key_exists($source,$variables[$name]))
    {
     $variables[$name][$source]=$variables[$name][$source]+1;
    }
    else
    {
      $variables[$name][$source]=1;
    }
   }
   else
   {
     $variables[$name]=[];
     $variables[$name][$source]=1;
     echo $variables[$name][$source];
   }
   $variables=json_encode($variables);
   file_put_contents('personalised.json',$variables);
  }
  else
  {
    echo "Problem";
  }
  echo "final";
}
else
{
  echo "tb";
}
?>