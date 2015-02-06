<?php
// Create connection
$con=mysql_connect("localhost","eventhub","eventhub") or die("not connected");
$db_con = mysql_select_db("db_eventhub",$con) or die("not connected with DB"); 


// Check connection
//if (mysql_connect_errno()) {
  //echo "Failed to connect to MySQL: " . mysql_connect_error();
//}
?> 
