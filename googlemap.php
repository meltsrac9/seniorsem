<?

$lat  = 0;  
$long = 0;

if ( isset($_GET['lat']) ) {
  $lat  = $_GET['lat'];  
}
if ( isset($_GET['long']) ) {
  $long  = $_GET['long'];  
}



?>

<!DOCTYPE html>
<html>
  <head>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVvB4VW0HvcA_4S2xYru5BBLbvS31WDIo&callback=initMap" type="text/javascript"></script>
    
    <style>
       #map {
        height: 400px;
        width: 100%;
       }
    </style>
  </head>
  <body>
    <h3>Google Maps Demo</h3>
    <b>Latitude: <?=$lat?></b>
    <br>
    <b>Longitude: <?=$long?></b>
    <br><br>
    
    <div id="map"></div>
    <script>
      function initMap() {
        var mycenter = {lat: <?=$lat?>, lng: <?=$long?>};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: mycenter
        });
        var marker = new google.maps.Marker({
          position: mycenter,
          map: map
        });
      }
    </script>
  </body>
</html>