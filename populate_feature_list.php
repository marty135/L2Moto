<?php
include 'database_info.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Query to find top 5 q-ride providers in Queensland.
$sql = mysql_query($conn,"SELECT name, rating FROM qride ORDER BY rating DESC LIMIT 5");

$result = $conn->query($sql);

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
