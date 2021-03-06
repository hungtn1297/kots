<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Danh sách người dân</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Người dân
                            <small>Danh sách</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr align="center">                        
                                <th>Tên</th>
                                <th>Địa Chỉ</th>
                                <th>Trạng Thái Tài Khoản</th>
                                <th>Ảnh đại diện</th>
                                <th>Chi tiết</th>
                                <th>Khoá tài khoản</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listCitizens as $citizen)
                                @if ($citizen->isDisable == 1)
                                    <tr style="background-color: lightgray" align="center">
                                @else
                                    <tr align="center"> 
                                @endif        
                                        <td>{{$citizen->name}}</td>
                                        <td>{{$citizen->address}}</td>
                                        @if ($citizen->isDisable == 1)
                                            <td style="color: gray">Bị Khoá</td>
                                        @else
                                            <td style="color: green">Đang hoạt động</td>
                                        @endif
                                        <td>
                                            <img src="{{URL::asset("$citizen->image")}}" alt="" width="80" height="80">
                                        </td>
                                        <td>
                                            <form action="{{url('admin/citizen/viewprofile')}}" method="get">
                                                <input type="hidden" name="id" value="{{sprintf('%010d',$citizen->id)}}">
                                                <input type="submit" class="btn btn-info" value="Chi tiết">
                                            </form>    
                                        </td>
                                        <td>
                                            @if ($citizen->isDisable == 1)
                                                <form action="{{url('admin/citizen/disable')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{sprintf('%010d',$citizen->id)}}">
                                                    <input type="submit" class="btn btn-success" value="Kích hoạt">
                                                </form>
                                            @else
                                                <form action="{{url('admin/citizen/disable')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{sprintf('%010d',$citizen->id)}}">
                                                    <input type="submit" class="btn btn-danger" value="Khoá">
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


