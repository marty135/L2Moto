<?php
  $providers = 'https://data.qld.gov.au/dataset/9b4990ba-c083-40bd-a52b-c59d8dd2e793/resource/0647759d-9f68-44f9-bd7e-eb96d37d11e4/download/20160323qrideprovider.csv';
  $provider_names = [];
  $provider_ratings = [];
    $row = 1;
    if (($handle = fopen($providers, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        //push each qride provider name to array
        array_push($provider_names,$data[0]);
        $row++;
      }
$provider_names = array_unique($provider_names);
//echo print_r($provider_names);
fclose($handle);
}
//encode provider names
foreach ($provider_names as &$value) {
  $value = urlencode($value);
}
//loop through each provider in provider names and make a request to google API
//to get the rating data for the provider
for($i = 1; $i < count($provider_names); $i++) {
$provider_name = $provider_names[$i];
$url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=".$provider_name."&key=AIzaSyDPQhe1MxS69bPLryapwhD6f-rF1TlJR5Q";
//JSON object returned by google for each provider
$searchResult = file_get_contents($url);
//parse output to get their rating
$json_output = json_decode($searchResult, false);
array_push($provider_ratings, $json_output->results[0]->rating);
}
//create a list and then store for each Q-Ride Provider, a list of mappings for their
//properties to be added to the database.
include 'database_info.php';
// Create db connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
 die('Could not connect: ' . mysql_error());
}
for($i = 1; $i < count($provider_names); $i++) {
$name = urldecode($provider_names[$i]);
$rating = $provider_ratings[$i-1];
  if($name != null) {

    // segment by James
    if (strcmp((string)$name, "Australian Motorcycle Academy") == 0) {
      $ch = curl_init("https://www.facebook.com/amaqride/reviews?ref=page_internal");

    } else if (strcmp((string)$name, "Cycle Right") == 0) {
        $ch = curl_init("https://www.facebook.com/cycleright.cc/reviews?ref=page_internal");
      }

      else if (strcmp((string)$name, "DARTS Driver and Rider School") == 0) {
        $ch = curl_init("https://www.facebook.com/DARTS-Driver-and-Rider-Training-School-144166892293553/reviews?ref=page_internal");
      }
      curl_setopt( $ch, CURLOPT_POST, false );
      curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
      curl_setopt( $ch, CURLOPT_HEADER, false );
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $data = curl_exec( $ch );

      if (strpos($data, '_3-ma _2bne')) {
        $source1 = substr((string)$data, strpos((string)$data, '_3-ma _2bne') + 13, 3);
        $rating  = ($source1 + $rating)/2;
      }
    }


  $stmt = $conn->prepare("INSERT INTO qride(name, rating) VALUES(?, ?)");
  $stmt->bind_param("sd", $name, $rating);
  $stmt->execute();
  $stmt->close();
}

//close the db connection.
mysqli_close($conn);
?>
