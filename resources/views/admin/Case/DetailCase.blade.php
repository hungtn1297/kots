<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thông tin chi tiết sự cố</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"> Sự cố
                            <small>Thông tin chi tiết</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="col-lg-7" style="padding-bottom:120px">

                            <div class="form-group">
                                <label>Mã số</label>
                            <input class="form-control" name="id" value="{{$case->id}}" disabled/>
                            </div>
                            <br>
                        
                            <div class="form-group">
                                <label>Người gửi</label>
                            <input class="form-control" name="name" value="{{$case->user->name}}" disabled/>
                            </div>
                            <br>
        
                            <div class="form-group">
                                <label>Tin nhắn</label>
                                <input class="form-control" name="message" value="{{$case->message}}"  disabled/>
                            </div>
                            <br>

                            @if (!empty($case->image))
                            <div class="form-group">
                                <label>Hình ảnh</label>
                                <img src="{{$case->image}}" width="400" height="400" alt="">
                            </div>
                            <br>
                            @endif
                            
                            @if (!empty($case->sound))
                            <div class="form-group">
                                <label>Âm thanh</label>
                                <audio src="{{$case->sound}}"></audio>
                            </div>
                            <br>
                            @endif

                            <div class="form-group">
                                <label>Tin nhắn</label>
                                <input class="form-control" name="message" value="{{$case->message}}"  disabled/>
                            </div>
                            <br>

                            <div class="form-group">
                                <label>Trạng thái</label>
                                @switch($case->status)
                                    @case(0)
                                        <input class="form-control" name="status" value="Chưa xử lí"  disabled/>
                                        @break
                                    @case(1)
                                        <input class="form-control" name="status" value="Đang xử lí"  disabled/>
                                        @break
                                    @case(2)
                                        <input class="form-control" name="status" value="Thành công"  disabled/>
                                        @break
                                    @case(3)
                                        <input class="form-control" name="status" value="Thất bại"  disabled/>
                                        @break
                                    @case(4)
                                        <input class="form-control" name="status" value="Tạm hoãn"  disabled/>
                                        @break
                                    @case(5)
                                        <input class="form-control" name="status" value="Đã bị huỷ"  disabled/>
                                        @break
                                    @default
                                        
                                @endswitch
                            </div>
                            <br>

                            <div class="form-group">
                                <label>Phân loại</label>
                                @if ($case->type == 1)
                                    <input class="form-control" name="type" value="Cần liên lạc"  disabled/>
                                @else
                                    <input class="form-control" name="type" value="Tín hiệu khẩn cấp"  disabled/>
                                @endif
                                
                            </div>
                            <br>
                            {{-- {{ $case->caseDetail }} --}}
                            @if (!empty($case->caseDetail))
                            <div class="form-group">
                                <label>Các hiệp sĩ tham gia</label>
                            </div>
                            <br>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr align="center">                        
                                        <th>Tên Hiệp Sĩ</th>
                                        <th>Thời gian tham gia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($case->caseDetail as $detail)                                      
                                        @foreach ($detail->knight as $knight)
                                        <tr align="center">         
                                            <td>{{$knight->name}}</td>
                                            <td>{{$detail->created_at}}</td>             
                                        </tr>
                                        @endforeach
                                    @endforeach 
                            </table>
                            @endif
                            <br>

                            @if($case->status == 2 || $locationList != null)
                            <div class="form-group">
                                <label for="">Đoạn đường sự cố</label> 
                            </div>

                            <div id="googleMap" style="width:100%;height:450px;"></div>
                            <script>
                                var locationList = {!! json_encode($locationList) !!};

                                function myMap() {
                                    directionsService = new google.maps.DirectionsService();
                                    directionsRenderer = new google.maps.DirectionsRenderer();   
                                    var mapProp= {
                                        center:new google.maps.LatLng(locationList[0]['latitude'],locationList[0]['longitude']),
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

                                function drawPath(){
                                    var paths = [];
                                    for (let i = 0; i < locationList.length; i++) {
                                        var line = {
                                                    lat:locationList[i]['latitude'],
                                                    lng:locationList[i]['longitude']
                                                    };
                                        paths.push(line);
                                    }
                                    // console.log(paths);
                                    var drawP = new google.maps.Polyline({
                                        path: paths,
                                        geodesic: true,
                                        strokeColor: '#FF0000',
                                        strokeOpacity: 1.0,
                                        strokeWeight: 2
                                    });


                                    drawP.setMap(map);
                                }

                                function calcRoute() {
                                    var start = locationList[0]['latitude']+','+locationList[0]['longitude'];
                                    var end = locationList[locationList.length - 1]['latitude']+','+locationList[locationList.length - 1]['longitude'];
                                    var waypoints = [];
                                    for (let i = 1; i < locationList.length-1; i++) {
                                        locations = {location: locationList[i]['latitude']+','+locationList[i]['longitude']}
                                        waypoints.push(locations);
                                        
                                    }
                                    console.log('START: '+ start);
                                    console.log('END: '+ end);
                                    console.log('WAYPOINT: '+ waypoints);
                                    var request = {
                                        origin:start,
                                        destination:end,
                                        waypoints: waypoints,
                                        travelMode: 'DRIVING'
                                    };
                                    directionsService.route(request, function(response, status) {
                                        if (status == 'OK') {
                                        directionsRenderer.setDirections(response);
                                        }
                                    });

                                }

                                window.onload = function(){
                                    // drawPath();
                                    calcRoute();
                                }
                            </script>
                            @endif
                            <input type="button" onclick="goBack()" value="Trở về" class="btn btn-primary">
                            
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRJl0JFqHhM8jQ24VrJnzJE8HarKJ1qF0&callback=myMap"></script>
        
    @include('admin/footer')
</body>
</html>