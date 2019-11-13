<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
        <div id="googleMap" style="width:100%;height:450px;"></div>
        <script>
            function myMap() {
                directionsService = new google.maps.DirectionsService();
                directionsRenderer = new google.maps.DirectionsRenderer();   
                var mapProp= {
                    center:new google.maps.LatLng(10.794378,106.731063),
                    zoom:20,
                };
                // var marker = new google.maps.Marker({
                //     position:new google.maps.LatLng(10.794378,106.731063),
                //     icon:'image/address.png',
                // });

                map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
                // marker.setMap(map); 

                directionsRenderer.setMap(map);
            }

            function calcRoute() {
                var start = '10.8473082, 106.7933117';
                var end = '10.8473195, 106.7933092';
                location = {location:'10.848447843372,106.79265752435'}
                var waypoint = [
                    {location:'10.848447843372,106.79265752435'}, 
                    {location:'10.8473059,106.7933112'}
                                ];
                var request = {
                    origin:start,
                    destination:end,
                    waypoints: waypoint,
                    travelMode: 'DRIVING'
                };
                directionsService.route(request, function(response, status) {
                    if (status == 'OK') {
                    directionsRenderer.setDirections(response);
                    }
                });

            }

            window.onload = function(){
                calcRoute();
            }
        </script>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRJl0JFqHhM8jQ24VrJnzJE8HarKJ1qF0&callback=myMap"></script>
</html>