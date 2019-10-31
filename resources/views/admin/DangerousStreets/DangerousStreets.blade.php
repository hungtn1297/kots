<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Danh sách sự cố</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div id="googleMap" style="width:100%;height:450px;"></div>

                <script>
                    // var client = new google.loader.ClientLocation();
                    // var lat = client.latitude;
                    // var long = client.longitude;   
                    // alert(lat);   
                    var id = 1;
                    var allMarker = [];
                    var allLocation = [];
                    var start = 0;
                    var end = 0;  
                    var directionsService;
                    var directionsRenderer;
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

                        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
                        // marker.setMap(map); 

                        directionsRenderer.setMap(map);
                        google.maps.event.addListener(map,'click', function(e) {
                            placeMarker(e.latLng, map);
                            console.log(allLocation);
                        });


                        function placeMarker(location) {
                            var marker = new google.maps.Marker({
                                position: location, 
                                map: map
                            });
                            marker.id = id;
                            id++;

                            google.maps.event.addListener(marker,'click', function(e){
                                var content = 'Latitude: ' + location.lat() + '<br />Longitude: ' + location.lng();
                                content += "<br /><input type = 'button' va;ue = 'Delete' onclick = 'removeMarker(" + marker.id + ");' value = 'Delete' />";
                                var infoWindow = new google.maps.InfoWindow({
                                    content: content
                                });
                                infoWindow.open(map, marker);
                            });
                            allLocation.push(location.toString());
                            allMarker.push(marker);

                            if(allMarker.length % 2 == 0){
                                $end = location.toString();
                            }else{
                                $start = location.toString();
                            }
                        }
                        
                    }

                    function calcRoute() {
                        var request = {
                            origin:'71 Nguyễn Văn Lượng',
                            destination:'Ngã 6 Gò Vấp',
                            travelMode: 'DRIVING'
                        };
                        directionsService.route(request, function(response, status) {
                            if (status == 'OK') {
                            directionsRenderer.setDirections(response);
                            }
                        });
                    }

                    function removeMarker(id){
                        for (var i = 0; i < allMarker.length; i++) {
                            if (allMarker[i].id == id) {
                                //Remove the marker from Map                  
                                allMarker[i].setMap(null);
                                //Remove the marker from array.
                                allMarker.splice(i, 1);
                                allLocation.splice(i, 1);
                                return;
                            }
                        }
                    }

                    function drawPath(directionsService, directionsDisplay,start,end) {
                        directionsService.route({
                            origin: start,
                            destination: end,
                            waypoints: waypoints,
                            optimizeWaypoints: true,
                            travelMode: 'DRIVING'
                        }, function(response, status) {
                            if (status === 'OK') {
                            directionsDisplay.setDirections(response);
                            } else {
                            window.alert('Problem in showing direction due to ' + status);
                            }
                        });
                    }
                </script>
            </div>
            <input type="button" value="Route" id="route" onclick="calcRoute()">
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRJl0JFqHhM8jQ24VrJnzJE8HarKJ1qF0&callback=myMap"></script>
        
    @include('admin/footer')
</body>

</html>


