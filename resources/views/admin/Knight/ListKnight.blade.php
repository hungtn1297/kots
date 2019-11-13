<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Danh sách hiệp sĩ</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Hiệp sĩ
                            <small>Danh sách</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr align="center">                        
                                <th>Tên</th>
                                <th>Địa Chỉ</th>
                                <th>Số Điện Thoại</th>
                                <th>Trạng Thái Tài Khoản</th>
                                <th>Ảnh đại diện</th>
                                <th>Chi tiết</th>
                                <th>Khoá tài khoản</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listKnights as $knight)
                                @if ($knight->isDisable == 1)
                                    <tr style="background-color: lightgray" align="center">
                                @else
                                    <tr align="center"> 
                                @endif        
                                        <td>{{$knight->name}}</td>
                                        <td>{{$knight->address}}</td>
                                        <td>{{substr_replace($knight->id,'***',strlen($knight->id)-3)}}</td>
                                        @if ($knight->isDisable == 1)
                                            <td style="color: gray">Bị Khoá</td>
                                        @else
                                            {{-- @if ($knight->status == 0)
                                                <td style="color: coral">Đang chờ xét duyệt</td>
                                            @elseif($knight->status == 1) --}}
                                                <td style="color: green">Đang hoạt động</td>
                                            {{-- @elseif($knight->status == 2)
                                                <td style="color: orange">Đang truy đuổi</td>
                                            @endif   --}}
                                        @endif
                                        <td>
                                            <img src="{{URL::asset("$knight->image")}}" alt="" width="80" height="80">
                                        </td>
                                        <td>
                                            <form action="{{url('admin/knight/viewprofile')}}" method="get">
                                                <input type="hidden" name="id" value="{{sprintf('%010d',$knight->id)}}">
                                                <input type="submit" class="btn btn-info" value="Chi tiết">
                                            </form>    
                                        </td>
                                        <td>
                                            @if ($knight->isDisable == 1)
                                                <form action="{{url('admin/knight/disable')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{sprintf('%010d',$knight->id)}}">
                                                    <input type="submit" class="btn btn-success" value="Kích hoạt">
                                                </form>
                                            @else
                                                <form action="{{url('admin/knight/disable')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{sprintf('%010d',$knight->id)}}">
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


