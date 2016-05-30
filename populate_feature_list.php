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
$query = mysql_query("SELECT name, rating FROM qride ORDER BY rating DESC LIMIT 5");

while ($row = mysql_fetch_array($query)){
echo "<option value=\"owner1\">" . $row['name'] . $row['rating'] . "</option>";
}

//close the db connection.
mysqli_close($conn);

?>
