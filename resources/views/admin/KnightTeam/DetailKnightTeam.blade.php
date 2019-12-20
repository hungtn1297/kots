@extends('admin/master')
@section('content')
@section('title')
    Chi tiết nhóm hiệp sĩ
@endsection
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"> Nhóm hiệp sĩ
                            <small>Thông tin chi tiết</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="col-lg-7" style="padding-bottom:120px">
                        
                            <div class="form-group">
                                <label>Tên nhóm</label>
                            <input class="form-control" name="name" value="{{$knightTeam->name}}" disabled/>
                            </div>
                            <br>
        
                            <div class="form-group">
                                <label>Địa chỉ hoạt động</label>
                                <input class="form-control" name="address" value="{{$knightTeam->address}}"  disabled/>
                            </div>
                            <br>
                            
                            <div class="form-group">
                                <label>Trạng thái</label>
                                @if ($knightTeam->status == 1)
                                    <input class="form-control" name="isDisable" value="Đang hoạt động" disabled/>
                                @else
                                    <input class="form-control" name="isDisable" value="Đang chờ duyệt" disabled/>
                                @endif
                                
                            </div>
                            <br>
        
                            <br>
                            <div class="form-group">
                                <label>Những thành viên trong nhóm</label> <br>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr align="center">                        
                                        <th>Tên</th>
                                        <th>Địa Chỉ</th>
                                        <th>Số Điện Thoại</th>
                                        <th>Trạng Thái Tài Khoản</th>
                                        <th>Ảnh đại diện</th>
                                        <th>Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teamMembers as $knight)
                                        @if ($knight->isDisable == 1)
                                            <tr style="background-color: lightgray" align="center">
                                        @else
                                            <tr align="center"> 
                                        @endif        
                                                <td>{{$knight->name}}</td>
                                                <td>{{$knight->address}}</td>
                                                <td>{{substr_replace($knight->id,'****',strlen($knight->id)-4)}}</td>
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
                                            </tr>
                                    @endforeach 
                            </table>
                            <br>
                            <input type="button" onclick="goBack()" value="Trở về" class="btn btn-primary">
                            
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->


@stop