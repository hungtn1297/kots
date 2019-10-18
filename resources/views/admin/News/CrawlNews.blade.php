<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tin tức</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"> Tin tức
                            <small></small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="col-lg-7" style="padding-bottom:120px">
                        <form action="{{url('admin/news/crawl')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>Trang web</label>
                                <br>
                                <select name="website" id="" class="form-group">
                                    <option value="nld">nguoilaodong.com</option>
                                    <option value="dantri">dantri.vn</option>
                                </select>
                            </div>
                            
                            <input type="submit" value="Cào dữ liệu" class="btn btn-success">
                            <br>
                            <br>
                            <input type="button" onclick="goBack()" value="Trở về" class="btn btn-primary">
                        </form>    
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