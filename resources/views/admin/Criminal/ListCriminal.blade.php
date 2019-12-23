@extends('admin/master')
@section('content')
@section('title')
    Danh sách tội phạm
@endsection
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
                                            
                                                <form action="{{url('admin/criminal/disable')}}" method="post" onsubmit="submitDelete();">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$criminal->id}}">
                                                    @if ($criminal->status == 1)
                                                        <input type="submit" class="btn btn-default" value="Ẩn đi">
                                                    @else
                                                        <input type="submit" class="btn btn-success" value="Hiển thị">
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


