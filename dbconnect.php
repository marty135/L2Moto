<?php
	include 'database_info.php';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	//$xml = file_get_contents("http://www.example.com/file.xml");
	$csv = array_map('str_getcsv', file('https://data.qld.gov.au/dataset/9b4990ba-c083-40bd-a52b-c59d8dd2e793/resource/0647759d-9f68-44f9-bd7e-eb96d37d11e4/download/20160323qrideprovider.csv'));
	echo $csv;
	echo "HELLO TEST";
	//$stmt = $conn->prepare("INSERT INTO education(degree, time, location) VALUES(?, ?, ?)");
	//$stmt->bind_param("sss", $degree, $time, $location);

	//$degree = $_GET['degree'];
	//$time = $_GET['time'];
	//$location = $_GET['location'];

//	echo "degree" . $degree;
//	echo "time" . $time;
//	echo "location" . $location;

//	$stmt->execute();

//	$stmt->close();
	mysqli_close($conn);

//	header("Location: index.php");

?>
