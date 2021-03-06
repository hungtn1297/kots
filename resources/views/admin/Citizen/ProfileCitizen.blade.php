<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thông tin chi tiết người dân</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"> Người dân
                            <small>Thông tin chi tiết</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="col-lg-7" style="padding-bottom:120px">
                        
                            <div class="form-group">
                                <label>Tên</label>
                            <input class="form-control" name="name" value="{{$citizen->name}}" disabled/>
                            </div>
                            <br>
        
                            <div class="form-group">
                                <label>Địa chỉ</label>
                                <input class="form-control" name="address" value="{{$citizen->address}}"  disabled/>
                            </div>
                            <br>
        
                            <div class="form-group">
                                <label>Trạng thái tài khoản</label>
                                @if ($citizen->isDisable == 0)
                                    <input class="form-control" name="isDisable" value="Đang hoạt động" disabled/>
                                @else
                                    <input class="form-control" name="isDisable" value="Bị khoá" disabled/>
                                @endif
                                
                            </div>
                            <br>
        
                            <div class="form-group">
                                <label>Ảnh đại diện</label>
                            </div>   
                            <img src="{{URL::asset("$citizen->image")}}" alt="" width="200" height="200">
                            <br>
        
                            <br>
                            <div class="form-group">
                                <label>Những sự cố đã báo cáo</label> <br>
                            </div>
                            <br>
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