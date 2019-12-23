@extends('admin/master')
@section('content')
@section('title')
    Danh sách người dân
@endsection
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
                                <th>Số Điện Thoại</th>
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
                                        <td>{{substr_replace($citizen->id,'****',strlen($citizen->id)-4)}}</td>
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
                                                <form action="{{url('admin/citizen/disable')}}" method="post" onsubmit="submitDelete();">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{sprintf('%010d',$citizen->id)}}">
                                                    
                                                    @if ($citizen->isDisable == 1)
                                                        <input type="submit" class="btn btn-success" value="Kích hoạt">
                                                    @else
                                                        <input type="submit" class="btn btn-danger" value="Khoá">
                                                    @endif 
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
@stop


