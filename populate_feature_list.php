<?php

//create a list and then store for each Q-Ride Provider, a list of mappings for their
//properties to be added to the database.
include 'database_info.php';
// Create db connection

$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
 die('Could not connect: ' . mysql_error());
}

//query
$stmt = $conn->prepare("SELECT * FROM qride ORDER BY rating DESC LIMIT 5");
$stmt->execute();

if(mysql_num_rows($stmt)){
$select= '<select name="select">';
while($rs=mysql_fetch_array($stmt)){
      $select.='<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
  }
}
$select.='</select>';
echo $select;

$stmt->close();


?>
