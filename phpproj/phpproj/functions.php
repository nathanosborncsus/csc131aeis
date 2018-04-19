<?php

require("db.inc.php");

function getUsersData($id)
{
$array = array();
$q = mysql_query("SELECT * FROM 'users' WHERE 'id'=".$id);

while($r = mysql_fetch_assoc($q))
  {
    $array['user_id'] = $r['user_id'];
    $array['firstName'] = $r['firstName'];
    $array['lastName'] = $r['lastName'];
    $array['email'] = $r['email'];
    $array['passwordHash'] = $['passwordHash'];

  }
  return $array;
}

function getId($username)
{
  $q = mysql_query("SELECT 'id' FROM 'users' WHERE 'username'=''".username."'");
  while($r = mysql_fetch_assorc($q))
  {
    return $r['id'];
  }
}




 ?>
