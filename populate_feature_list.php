<?php
include 'database_info.php';

$con = new mysqli($servername, $username, $password, $dbname);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// Perform queries
if ($result = mysqli_query($con,"SELECT name, rating FROM qride ORDER BY rating DESC LIMIT 5")) {

  while($row = $result->fetch_assoc()) {
     echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
 }
 /* free result set */
 $result->close();

} else {
 echo "0 results";

 }

mysqli_close($con);
?>
