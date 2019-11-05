<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Thông tin công an</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Thông tin công an
                            <small>Tạo mới</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <form action="{{url('admin/news/create')}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="form-group">
                                <label>Tên</label>
                                <input class="form-control" name="name" required />
                        </div>
                        <br>

                        <div class="form-group">
                                <label>Địa chỉ</label>
                                <input type="file" name="address" required />
                        </div>
                        <br>
        
                            <button type="submit" class="btn btn-success">Tạo mới</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                    </form>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    @include('admin/footer')
   
</body>
</html>


