//Google provided function to initialise Google geolocation API
//Reference: https://developers.google.com/maps/documentation/javascript/examples/map-geolocation
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {
      lat: -34.397,
      lng: 150.644
    },
    zoom: 20
  });
  var infoWindow = new google.maps.InfoWindow({
    map: map
  });

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {

      //Gets the current position of the user
      var currPos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
      //Q-Ride providers in Queensland dataset - file format: CSV
      var data = httpGet();
      //Parses CSV file, and returns list of each provider's position
      var providerCoordinates = parseData(data);
      //lastly, finds the closest QRide Provider
      var shortestPath = findShortestPath(currPos, providerCoordinates);

      infoWindow.setPosition(shortestPath);
      infoWindow.setContent('This is your closest QRide Provider.');
      map.setCenter(shortestPath);
    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
  }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
    'Error: The Geolocation service failed.' :
    'Error: Your browser doesn\'t support geolocation.');
}


//function to find the shortest path between two possible lat/lng co-ordinates
//takes a current location in lat/lng format and a list of possible locations,
//each possible location is in lat/lng format. It then checks the current location
//to every possible location, and returns the lat/lng of the smallest distance.
function findShortestPath(currentLocation, possibleLocations) {

  var latLngs = [];
  var toCheck = [];

  for (var key in possibleLocations) {
    var value = possibleLocations[key];

    for (var nextKey in value) {
      var nextValue = value[nextKey];
      latLngs.push(nextValue);
    }
  }

  var lats = [];
  var lngs = [];

  for (var i = 0; i < latLngs.length; i++) {
    var lat = 0;
    var lng = 0;

    if (i % 2) {
      lng = latLngs[i];
      lngs.push(lng);
    } else {
      lat = latLngs[i];
      lats.push(lat);
    }
  }

  for (var i = 0; i < lats.length; i++) {
    var checks = new google.maps.LatLng(lats[i], lngs[i]);
    toCheck.push(checks);
  }

  var distances = [];
  //if (min == current index  return it, that's co-ords we show on the map.)
  for (var i = 0; i < toCheck.length; i++) {
    var distance = google.maps.geometry.spherical.computeDistanceBetween(currentLocation, toCheck[i]);
    distances.push(distance);
  }
  var min = distances.min();
  var index = distances.indexOf(min);

  return toCheck[index];
}

//function to take a csv file and parse it by splitting up the headings, and
//rest of the file contents. It then isolates the columns associated with lat/lng
//co-ordinates and adds them in a dictionary fashion (key,value) to the array.
function parseData(file) {
  var allLines = file.split(/\r\n|\n/);
  var headings = allLines[0].split(',');
  var lines = [];
  var latLong = [];

  for (var i = 1; i < allLines.length; i++) {
    var data = allLines[i].split(',');
    if (data.length == headings.length) {
      var arr = [];
      for (var j = 0; j < headings.length; j++) {
        arr.push(headings[j] + ":" + data[j]);
      }
      lines.push(arr);
    }
  }

  for (var i = 0; i < lines.length; i++) {
    var lat = lines[i][7].split(':')[1];
    var long = lines[i][8].split(':')[1];

    latLong.push({
      key: lat,
      value: long
    });

  }
  return latLong;
}

//Array min helper function
Array.prototype.min = function() {
  return Math.min.apply(Math, this);
};

// helper function for getting the data set
function httpGet() {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open("GET", "https://data.qld.gov.au/dataset/9b4990ba-c083-40bd-a52b-c59d8dd2e793/resource/0647759d-9f68-44f9-bd7e-eb96d37d11e4/download/20160323qrideprovider.csv", false);
  xmlHttp.send(null);
  return xmlHttp.responseText;
}

// Adding onclick functions to social media icons - links to websites
document.getElementById('icon1').onclick = function() {
  window.location.href = 'https://twitter.com/?lang=en';
};
document.getElementById('icon2').onclick = function() {
  window.location.href = 'https://www.instagram.com/?hl=en';
};
document.getElementById('icon3').onclick = function() {
  window.location.href = 'https://au.linkedin.com/';
};
document.getElementById('icon4').onclick = function() {
  window.location.href = 'https://www.facebook.com/';
};


///////////////////////////////////////////////////////////////////////
///// Below is using Facebook API to pull review data from each Q-Ride
///// Provider.
///////////////////////////////////////////////////////////////////////

// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
  console.log('statusChangeCallback');
  console.log(response);
  // The response object is returned with a status field that lets the
  // app know the current login status of the person.
  // Full docs on the response object can be found in the documentation
  // for FB.getLoginStatus().
  if (response.status === 'connected') {
    // Logged into your app and Facebook.
    testAPI();
    getFBRating();
  } else if (response.status === 'not_authorized') {
    // The person is logged into Facebook, but not your app.
    document.getElementById('status').innerHTML = 'Please log ' +
      'into this app.';
  } else {
    // The person is not logged into Facebook, so we're not sure if
    // they are logged into this app or not.
    document.getElementById('status').innerHTML = 'Please log ' +
      'into Facebook.';
  }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}

window.fbAsyncInit = function() {
FB.init({
  appId      : '106153123135194',
  cookie     : true,  // enable cookies to allow the server to access
                      // the session
  xfbml      : true,  // parse social plugins on this page
  version    : 'v2.5' // use graph api version 2.5
});

// Now that we've initialized the JavaScript SDK, we call
// FB.getLoginStatus().  This function gets the state of the
// person visiting this page and can return one of three states to
// the callback you provide.  They can be:
//
// 1. Logged into your app ('connected')
// 2. Logged into Facebook, but not your app ('not_authorized')
// 3. Not logged into Facebook and can't tell if they are logged into
//    your app or not.
//
// These three cases are handled in the callback function.

FB.getLoginStatus(function(response) {
  statusChangeCallback(response);
});

};

// Load the SDK asynchronously
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
  console.log('Welcome!  Fetching your information.... ');
  FB.api('/me', function(response) {
    console.log('Successful login for: ' + response.id);
    document.getElementById('status').innerHTML =
      'Thanks for logging in, ' + response.id + '!';
  });
}

// get fb rating
function getFBRating() {
  /* make the API call */
  FB.api("/me/accounts",
      function (response) {
        if (response && !response.error) {
          /* handle the result */
          document.getElementById('status').innerHTML =
            'Rating: ' + response.rating + '!';
        } else {
          document.getElementById('status').innerHTML =
            JSON.stringify(response.error);
        }
      }
  );
}



/////////////////////////////////////////////////////////////////////////////
////////  Google Places API to get Q-Ride data
/////////////////////////////////////////////////////////////////////////////
