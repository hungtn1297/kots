<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Danh sách tội phạm</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Tội phạm
                            <small>Danh sách</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr align="center">                        
                                <th>Tên</th>
                                <th>Tuổi</th>
                                <th>Hình ảnh</th>
                                <th>Chi tiết</th>
                                <th>Hiển thị/Ẩn đi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listCriminals as $criminal)
                                    <tr align="center">    
                                        <td>{{$criminal->name}}</td>
                                        <td>{{$criminal->age}}</td>
                                        <td>
                                            <img src="{{URL::asset("$criminal->image")}}" alt="" width="80" height="80">
                                        </td>
                                        <td>
                                            <form action="{{url('admin/criminal/viewprofile')}}" method="get">
                                                <input type="hidden" name="id" value="{{$criminal->id}}">
                                                <input type="submit" class="btn btn-info" value="Chi tiết">
                                            </form>    
                                        </td>
                                        <td>
                                            @if ($criminal->status == 1)
                                                <form action="{{url('admin/criminal/disable')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$criminal->id}}">
                                                    <input type="submit" class="btn btn-default" value="Ẩn đi">
                                                </form>
                                            @else
                                                <form action="{{url('admin/criminal/disable')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$criminal->id}}">
                                                    <input type="submit" class="btn btn-success" value="Hiển thị">
                                                </form>
                                            @endif 
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


