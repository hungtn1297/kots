@extends('admin/master')
@section('content')
@section('title')
    Danh sách phản hồi
@endsection
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Phản hồi
                            <small>Danh sách</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr align="center">                        
                                <th>Người gửi</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listFeedbacks as $feedback)
                                <tr align="center">
                                    @if ($feedback->isAnonymous == 1)
                                        <td>Ẩn danh</td>
                                    @else
                                        <td>{{$feedback->user->name}}</td>
                                    @endif      
                                    
                                    <td>
                                        <form action="{{url('admin/feedback/detail')}}" method="get">
                                            <input type="hidden" name="id" value="{{$feedback->id}}">
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


