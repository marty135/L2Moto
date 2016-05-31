<?php

 $scrape_URLS = array(
    "https://www.facebook.com/amaqride/reviews?ref=page_internal",
    "https://www.facebook.com/CycleRightMotorcyclingTrainingAcademy/reviews?ref=page_internal",
    "https://www.facebook.com/DARTS-Driver-and-Rider-Training-School-144166892293553/reviews?ref=page_internal",
    "https://www.facebook.com/bikesmarttraining/reviews",
    "https://www.facebook.com/Bundaberg-Motorcycle-Training-526052524109891/reviews?ref=page_internal",
    "https://www.facebook.com/ianwatsonsdrivingschool/reviews/"
    );

$scrape_Ratings = [];
$scrape_Titles = [];

for ($i = 0; $i < count($scrape_URLS); $i++) {
  $url = $scrape_URLS[$i];
  $options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
              "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
  )
);
$context = stream_context_create($options);
$data = file_get_contents($url, false, $context);

  if (strpos($data, '_3-ma _2bne')) {
    $source1 = substr((string)$data, strpos((string)$data, '_3-ma _2bne') + 13, 3);

    if ($i == 0) {
      array_push($scrape_Titles, "Australian Motorcycle Academy");
      array_push($scrape_Ratings, $source1);
    } else if ($i == 1) {
      array_push($scrape_Titles, "Cycle Right");
      array_push($scrape_Ratings, $source1);
    } else if ($i == 2) {
      array_push($scrape_Titles, "DARTS Driver and Rider Training School");
      array_push($scrape_Ratings, $source1);
    } else if ($i == 3) {
      array_push($scrape_Titles, "Bike Smart Motocycle Training");
      array_push($scrape_Ratings, $source1);
    } else if ($i == 4) {
      array_push($scrape_Titles, "Bundaberg Motorcycle Training");
      array_push($scrape_Ratings, $source1);
    } else if ($i == 5) {
      array_push($scrape_Titles, "Ian Watson's Driver Training Centre");
      array_push($scrape_Ratings, $source1);
    }
  }
}


for ($k = 0; $k < count($scrape_Titles); $k++) {
  echo $scrape_Titles[$k];
  echo ", ";
  echo $scrape_Ratings[$k];
  echo "...";
}



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

    if (in_array($name, $scrape_Titles)) {
      $key = array_search($name, $scrape_Titles);

      if ($rating === NULL) {
        $rating = ($scrape_Ratings[$key]);
      } else {
        $rating = ($rating + $scrape_Ratings[$key])/2;
      }

    }

    $tableNames = mysql_query("SELECT name FROM qride");
    $storeTableNames = Array();
    while ($row = mysql_fetch_array($tableNames, MYSQL_ASSOC)) {
      $storeTableNames[] = $row['name'];
    }

    if (in_array($name, $storeTableNames)) {
      $stmt = $conn->prepare("DELETE FROM qride WHERE name=$name");
      $stmt->bind_param("sd", $name, $rating);
      $stmt->execute();
      $stmt->close();

      $stmt = $conn->prepare("INSERT INTO qride(name, rating) VALUES(?, ?)");
      $stmt->bind_param("sd", $name, $rating);
      $stmt->execute();
      $stmt->close();

    } else {
      $stmt = $conn->prepare("INSERT INTO qride(name, rating) VALUES(?, ?)");
      $stmt->bind_param("sd", $name, $rating);
      $stmt->execute();
      $stmt->close();
    }

  }
}
//close the db connection.
mysqli_close($conn);
?>
