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
                {{-- {{$listDSs}} --}}
                <div id="googleMap" style="width:100%;height:450px;"></div>

                <script>
                    
                    // var client = new google.loader.ClientLocation();
                    // var lat = client.latitude;
                    // var long = client.longitude;   
                    // alert(lat);
                    var listDSs = {!! json_encode($listDSs->toArray()) !!};
                    // console.log(listDSs);
                    var id = 1;
                    var allMarker = [];
                    var allLocation = [];
                    var start = 0;
                    var end = 0;  
                    var directionsService;
                    var directionsRenderer;
                    var map;
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
                        google.maps.event.addListener(map,'click', function(e) {
                            placeMarker(e.latLng, map);
                            // console.log(allLocation);
                        });


                        function placeMarker(location) {
                            if(allMarker.length < 2){
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
                                    end = location.toString();
                                }else{
                                    start = location.toString();
                                }
                            }else{
                                alert('You must delete one to select another one');
                            }

                            
                        }
                    }

                    function drawRoute(startLatitude, startLongitude, endLatitude, endLongitude){
                        // console.log('STARTLA: ' + startLatitude);
                        // console.log(startLatitude + ',' + startLongitude);
                        // console.log(endLatitude + ',' + endLongitude);
                        var request = {
                            origin:startLatitude+','+startLongitude,
                            destination:endLatitude+','+endLongitude,
                            travelMode: 'DRIVING'
                        };
                        directionsService.route(request, function(response, status) {
                            if (status == 'OK') {
                            directionsRenderer.setDirections(response);
                            }
                        });
                    }

                    function loadRoute(){
                        var bounds = new google.maps.LatLngBounds();
                        for (let i = 0; i < listDSs.length; i++) {
                            var startPoint = new google.maps.LatLng(listDSs[i]['startLatitude'], listDSs[i]['startLongitude']);
                            var endPoint = new google.maps.LatLng(listDSs[i]['endLatitude'], listDSs[i]['endLongitude']);
                            console.log('START: '+startPoint);
                            console.log('END: '+endPoint);
                            var directionsDisplay = new google.maps.DirectionsRenderer({
                                map: map,
                                preserveViewport: true
                            });
                            calculateAndDisplayRoute(directionsService, directionsDisplay, startPoint, endPoint, bounds);
                            // drawRoute(listDSs[i].startLatitude, listDSs[i].startLongtitude, listDSs[i].endLatitude, listDSs[i].endLongtitude);                        
                        }
                    }

                    function calculateAndDisplayRoute(directionsService, directionsDisplay, startPoint, endPoint, bounds) {
                        directionsService.route({
                        origin: startPoint,
                        destination: endPoint,
                        travelMode: 'DRIVING'
                        }, function(response, status) {
                        if (status === 'OK') {
                            directionsDisplay.setDirections(response);
                            bounds.union(response.routes[0].bounds);
                            map.fitBounds(bounds);
                        } else {
                            window.alert('Impossible d afficher la route ' + status);
                        }
                        });
                    }

                    function calcRoute() {
                        // var start = '37.7683909618184, -122.51089453697205';
                        // var end = '41.850033, -87.6500523';
                        // start.slice(1,start.length-1);
                        var request = {
                            origin:start.slice(1,start.length-1),
                            destination:end.slice(1,end.length-1),
                            travelMode: 'DRIVING'
                        };
                        directionsService.route(request, function(response, status) {
                            if (status == 'OK') {
                            directionsRenderer.setDirections(response);
                            }
                        });

                        document.getElementById('start').value = start;
                        document.getElementById('end').value = end;
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

                window.onload = function(){
                    loadRoute();
                }
                </script>
            </div>
            <div style="text-align: center">
                <form action="{{url('admin/dangerousStreets/setDS')}}" method="get">
                    <input type="hidden" name="start" id="start">
                    <input type="hidden" name="end" id="end">
                    <input type="button" value="Thiết lập" class="btn btn-success" id="route" onclick="calcRoute()">
                    <input type="submit" value="Cập nhật" class="btn btn-primary">
                </form>
                
            </div>
            

            <!-- /.container-fluid -->
            <br>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr align="center">                        
                        <th>Mã số</th>
                        <th>Mô tả</th>
                        <th>Huỷ bỏ thiết lập</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listDSs as $ds)
                        <tr align="center" onclick="drawRoute({{$ds->startLatitude}}, {{$ds->startLongitude}}, {{$ds->endLatitude}}, {{$ds->endLongitude}})">         
                            <td>{{$ds->id}}</td>
                            <td>{{$ds->description}}</td>
                            <td>
                                <form action="{{url('admin/dangerousStreets/unsetDS')}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="id" value="{{$ds->id}}">
                                    <input type="submit" class="btn btn-danger" value="Huỷ bỏ">
                                </form>
                            </td>                    
                        </tr>
                    @endforeach 
            </table>
        </div>
        <!-- /#page-wrapper -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRJl0JFqHhM8jQ24VrJnzJE8HarKJ1qF0&callback=myMap"></script>
        
    @include('admin/footer')
</body>

</html>


