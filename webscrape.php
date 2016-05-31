<?php


$scrape_URLS = array(
    "https://www.facebook.com/amaqride/reviews?ref=page_internal",
    "https://www.facebook.com/cycleright.cc/reviews?ref=page_internal",
    "https://www.facebook.com/DARTS-Driver-and-Rider-Training-School-144166892293553/reviews?ref=page_internal"
    );

$scrape_Ratings = [];
$scrape_Titles = [];

for ($i = 0; $i < count($scrape_URLS); $i++) {
//  $ch = curl_init($scrape_URLS[$i]);
// //  curl_setopt( $ch, CURLOPT_POST, false );
//   curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
//   curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
//   curl_setopt( $ch, CURLOPT_HEADER, false );
//   curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//  $data = curl_exec( $ch );

  if (strpos($data, '_3-ma _2bne')) {
    $source1 = substr((string)$data, strpos((string)$data, '_3-ma _2bne') + 13, 3);

    if ($i == 0) {
      array_push($scrape_Titles, "Australian Motorcycl");
      array_push($scrape_Ratings, $source1);
    } else if ($i == 1) {
      array_push($scrape_Titles, "Cycle Right");
      array_push($scrape_Ratings, $source1);
    } else if ($i == 2) {
      array_push($scrape_Titles, "DARTS Driver and Rid");
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

echo "Hello world";
// echo $scrape_Titles[0];
// echo ", ";
// echo $scrape_Ratings[0];
// echo "...";
// echo $scrape_Titles[1];
// echo ", ";
// echo $scrape_Ratings[1];
// echo "...";
// echo $scrape_Titles[2];
// echo ", ";
// echo $scrape_Ratings[2];
// echo "...";

?>
