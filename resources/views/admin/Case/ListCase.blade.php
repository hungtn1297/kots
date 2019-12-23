@extends('admin/master')
@section('content')
@section('title')
    Danh sách sự cố
@endsection
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Sự cố
                            <small>Danh sách</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr align="center">                        
                                <th>Người gửi</th>
                                <th>Tin nhắn</th>
                                <th>Trạng thái</th>
                                <th>Phân loại</th>
                                <th>Thời gian khởi tạo</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listCases as $case)            
                                <tr align="center">         
                                    <td>
                                        <a href="#" data-toggle="tooltip" data-placement="right" title="{{$case->user->id . "/n". $case->user->address}}">
                                            {{$case->user->name}}
                                        </a>
                                    </td>
                                    <td>{{$case->message}}</td>
                                    @if ($case->status == 0)
                                        <td style="color: gray">Đã được gửi</td>
                                    @elseif($case->status == 1)
                                        <td style="color: gray">Đang xử lí</td>
                                    @elseif($case->status == 2)
                                        <td style="color: green">Thành công</td>
                                    @elseif($case->status == 3)
                                        <td style="color: red">Thất bại</td> 
                                    @elseif($case->status == 4)
                                        <td style="color: gray">Đang chờ</td>
                                    @elseif($case->status == 5)
                                        <td style="color: orange">Bị huỷ</td>
                                    @elseif($case->status == 6)
                                        <td style="color: darkblue">Sự cố giả</td>              
                                    @else
                                        <td style="color: green">Thua =))</td>
                                    @endif
                                    @if ($case->type == 1)
                                        <td>Cần Liên Lạc</td>
                                    @else
                                        <td>Khẩn cấp</td>
                                    @endif
                                    <td>{{$case->created_at}}</td>
                                    <td>
                                        <form action="{{url('admin/case/detail')}}" method="get">
                                            <input type="hidden" name="id" value="{{$case->id}}">
                                            <input type="submit" class="btn btn-info" value="Chi tiết">
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


