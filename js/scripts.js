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

  var second_map = new google.maps.Map(document.getElementById('secondmap'), {
    center: {
      lat: -34.397,
      lng: 150.644
    },
    zoom: 20
  });
  var infoWindowTwo = new google.maps.InfoWindow({
    second_map: second_map
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

      infoWindowTwo.setPosition(currPos);
      infoWindowTwo.setContent('You are currenlty here.');
      map.setCenter(currPos);

    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
  }
}


//Enables Map to work within the modal
google.maps.event.addDomListener(window, 'load', initialize);

google.maps.event.addDomListener(window, "resize", resizingMap());

$('#myModal').on('show.bs.modal', function() {
  //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
  resizeMap();
})

function resizeMap() {
  if (typeof map == "undefined") return;
  setTimeout(function() {
    resizingMap();
  }, 400);
}

function resizingMap() {
  if (typeof map == "undefined") return;
  var center = map.getCenter();
  google.maps.event.trigger(map, "resize");
  map.setCenter(center);
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
