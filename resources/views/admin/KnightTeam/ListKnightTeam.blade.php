@extends('admin/master')
@section('content')
@section('title')
    Danh sách nhóm hiệp sĩ
@endsection
<div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Nhóm hiệp sĩ
                        <small>Danh sách</small>
                    </h1>
                </div>
                @if (count($countTeam) != 0)
                    @section('requestTeam')
                    <span style="color: white; 
                                            border: 1px solid red; 
                                            background-color: red;
                                            display: inline-block;
                                            border-radius: 50%;
                                            width: 20px;
                                            height: 20px;
                                            text-align: center;
                                "><b>{{count($countTeam)}}</b>
                    </span>
                    @endsection
                @endif
                
                <!-- /.col-lg-12 -->
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr align="center">                        
                            <th>Tên đội</th>
                            <th>Đội trưởng</th>
                            <th>Trạng thái</th>
                            <th>Chi tiết</th>
                            <th>Thay đổi trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team)
                            <tr align="center">         
                                <td>{{$team->name}}</td>
                                <td>{{$team->leaderName}}</td>
                                @if ($team->status == 1)
                                    <td style="color: green">Đang hoạt động</td>
                                @endif
                                @if ($team->status == 0)
                                    <td style="color: gray">Đang chờ xét duyệt</td>
                                @endif
                                @if ($team->status == -1)
                                    <td style="color: gray">Khoá</td>
                                @endif
                                <td>
                                    <form action="{{url('admin/knightTeam/detail')}}" method="get">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$team->id}}">
                                        <input type="submit" class="btn btn-info" value="Chi tiết">
                                    </form>
                                </td> 
                                <td>
                                    <form action="{{url('admin/knightTeam/changeTeamStatus')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$team->id}}">
                                        @if ($team->status  == 0 || $team->status  == -1)
                                            <input type="hidden" name="status" value="1">
                                            <input type="submit" class="btn btn-primary" value="Duyệt">
                                        @endif
                                        @if ($team->status  == 1)
                                            <input type="hidden" name="status" value="-1">
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

