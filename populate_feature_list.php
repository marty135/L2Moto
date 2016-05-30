<?php
//create a list and then store for each Q-Ride Provider, a list of mappings for their
//properties to be added to the database.
include 'database_info.php';

// Create db connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
 die('Could not connect: ' . mysql_error());
}

//Query to find top 5 q-ride providers in Queensland.
$query = mysql_query("SELECT name, rating FROM qride ORDER BY rating DESC LIMIT 5;");
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Provider: " . $row["name"]. " - Rating: " . $row["rating"]. "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();

?>
