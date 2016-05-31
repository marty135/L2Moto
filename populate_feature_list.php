<?php
include 'database_info.php';

$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// Finds top 5 highest rated Q-Ride providers in Queensland
if ($result = mysqli_query($con,"SELECT name, rating FROM qride ORDER BY rating DESC LIMIT 5")) {

  while($row = $result->fetch_assoc()) {
     echo $row["name"] ."  " . $row["rating"]. "<br>";
 }
 /* free result set */
 $result->close();

} else {
 echo "0 results";

 }

mysqli_close($con);
?>
