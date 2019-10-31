<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
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

                            <div class="form-group">
                                <label>Trạng thái</label>
                                <input class="form-control" name="status" value="{{$case->status}}"  disabled/>
                            </div>
                            <br>

                            <div class="form-group">
                                <label>Phân loại</label>
                                <input class="form-control" name="type" value="{{$case->type}}"  disabled/>
                            </div>
                            <br>
                            
                            <div class="form-group">
                                <label>Các hiệp sĩ tham gia</label>
                            </div>
                            <br>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr align="center">                        
                                        <th>Tên Hiệp Sĩ</th>
                                        <th>Mã Sự cố</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($case->caseDetail as $detail)                                      
                                        @foreach ($detail->knight as $knight)
                                        <tr align="center">         
                                            <td>{{$knight->name}}</td>
                                            <td>{{$case->id}}</td>             
                                        </tr>
                                        @endforeach
                                    @endforeach 
                            </table>
                            
                            <br>

                            <div class="form-group">
                                <label for="">Đoạn đường sự cố</label> 
                            </div>

                            <div class="">
                                <iframe id="gmap"
                                    style="width:100%;height:500px;"
                                    frameborder="0" style="border:0"
                                    src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyDRJl0JFqHhM8jQ24VrJnzJE8HarKJ1qF0
                                        &origin={{$case->startLatitude}},{{$case->startLongitude}}
                                        &destination={{$case->endLatitude}},{{$case->endLongitude}}" allowfullscreen disableDefaultUI: true>
                                </iframe>
                            </div>
                            <input type="button" onclick="goBack()" value="Trở về" class="btn btn-primary">
                            
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    @include('admin/footer')
</body>
</html>