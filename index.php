<!doctype html>

<head>
  <meta charset="utf-8" />

  <title>L2Moto</title>
  <meta name="description" content="Queensland's greatest motorbike teachers" />
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
  <link rel="icon" href="images/favicon.ico" type="image/ico" />
  <link rel="stylesheet" href="css/styles.css">
  <link href="images/Twitter.png" type="image/png" rel="stylesheet" />
  <link href="images/Facebook.png" type="image/png" rel="stylesheet" />
  <link href="images/Instagram.png" type="image/css" rel="stylesheet" />
  <link href="images/LinkedIn.png" type="image/css" rel="stylesheet" />

  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3&libraries=geometry"></script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPQhe1MxS69bPLryapwhD6f-rF1TlJR5Q&libraries=places"></script>
</head>

<body>

  <section class="SectionOne">

    <div id="tabcontainer">
      <a href="index.html">Home</a>
      <a href="about.html">About</a>
      <button type="button" class="buttonimposter" data-toggle="modal" data-target="#myModal">Ride Now</button>
      <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">

              <?php
                $providers = 'https://data.qld.gov.au/dataset/9b4990ba-c083-40bd-a52b-c59d8dd2e793/resource/0647759d-9f68-44f9-bd7e-eb96d37d11e4/download/20160323qrideprovider.csv';
                $provider_names = [];
                  $row = 1;
                  if (($handle = fopen($providers, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                      $num = count($data);
                    //  echo $data[0] . "<br />\n";
                      array_push($provider_names,$data[0]);
                    //  echo "<p> $num fields in line $row: <br /></p>\n";
                      $row++;
                for ($c=0; $c < $num; $c++) {
                  //echo $data[0] . "<br />\n";
              }
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
              
              echo print_r($json_output->results[0]->rating);

            }
            //for loop over each Q-Ride provider
            $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=%22london%20eye%22&key=AIzaSyDPQhe1MxS69bPLryapwhD6f-rF1TlJR5Q';
            $searchResult = file_get_contents($url);
            //parse output to get their rating
            $json_output = json_decode($searchResult, false);
            echo print_r($json_output->results[0]->rating);
            ?>

            <button id="testButton"> Test </button>
            <div  class="fb-like"
                  data-share="true"
                  data-width="450"
                  data-show-faces="true">
            </div>
            <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
            </fb:login-button>

            <div id="status">
            </div>

            <p>Seeing blank? Your location settings are currently turned off</p>
            </div>
            <div id="rankings">

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>


    </div>
    <div id="namecontainer">
      <h1 id="websitename">L2Moto</h1>
      <p id="description">Helping you find Queensland's best QRide Providers</p>
    </div>
  </section>

  <section class="SectionTwo">
    <div id="missionpiccontainer">
      <div id="missionpic1">
      </div>
      <div id="missionpic2">
      </div>
      <div id="missionpic3">
      </div>
    </div>
    <h1 id="missionstatement">Ride with Confidence</h1>
  </section>

  <section class="SectionThree">
    <p>Scroll down to see your nearest QRide Provider!</p>

  </section>

  <section class="SectionFour" id="feature">
    <br>
    <br>
    <div id="map"></div>
    <p>Seeing blank? Your location settings are currently turned off</p>
    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap" async defer></script>

  </section>

  <section class="SectionFive">
    <p>Find us on Social Media</p>
    <div id="iconpiccontainer">
      <div id="icon1">
      </div>
      <div id="icon2">
      </div>
      <div id="icon3">
      </div>
      <div id="icon4">
      </div>
    </div>

  </section>

  <script src="js/scripts.js"></script>
</body>

</html>
