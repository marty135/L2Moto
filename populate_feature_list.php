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
$top_five = mysql_fetch_array($result);

foreach ($top_five as &$value) {
    print_r($value);
}
 /* free result set */
 $result->close();
}

mysqli_close($con);
?>
