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

                            @if($case->endLatitude != null)
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
                            @endif
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