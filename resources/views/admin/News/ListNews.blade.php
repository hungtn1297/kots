<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Danh sách tin tức</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Tin tức
                            <small>Danh sách</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr align="center">                        
                                <th>Tiêu đề</th>
                                <th>Hình ảnh</th>
                                <th>Chỉnh sửa</th>
                                <th>Xoá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listNews as $news)
                                    <tr align="center"> 
                                        <td>{{$news->title}}</td>
                                        <td>
                                            <img src="{{URL::asset("$news->image")}}" alt="" width="80" height="80">
                                        </td>
                                        <td>
                                            <form action="{{url('admin/news/edit')}}" method="get">
                                                <input type="hidden" name="id" value="{{$news->id}}">
                                                <input type="submit" class="btn btn-info" value="Chỉnh sửa">
                                            </form>    
                                        </td>
                                        <td>
                                            <form action="{{url('admin/news/delete')}}" method="post">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" name="id" value="{{$news->id}}">
                                                <input type="submit" class="btn btn-danger" value="Xoá">
                                            </form>
                                        </td>                    
                                    </tr>
                            @endforeach 
                    </table>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    @include('admin/footer')
</body>
</html>


