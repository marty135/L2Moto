<?php

$scrape_URLS = array(
    "https://www.facebook.com/amaqride/reviews?ref=page_internal",
    "https://www.facebook.com/cycleright.cc/reviews?ref=page_internal",
    "https://www.facebook.com/DARTS-Driver-and-Rider-Training-School-144166892293553/reviews?ref=page_internal"
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


echo $file;

?>
