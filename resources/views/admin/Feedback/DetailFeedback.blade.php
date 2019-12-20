@extends('admin/master')
@section('content')
@section('title')
    Chi tiết phản hồi
@endsection
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"> Phản hồi
                            <small>Thông tin chi tiết</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="col-lg-7" style="padding-bottom:120px">
                        
                            <div class="form-group">
                                <label>Người gửi</label>
                                @if ($feedback->isAnonymous == 0)
                                    <input class="form-control" name="name" value="Ẩn danh" disabled/>
                                @else
                                    <input class="form-control" name="name" value="{{$feedback->user->name}}" disabled/>
                                @endif
                            </div>
                            <br>
        
                            <div class="form-group">
                                <label>Nội dung</label>
                                <textarea class="form-control" name="address" id="" cols="30" rows="10" disabled>{{$feedback->content}}</textarea>
                            </div>
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