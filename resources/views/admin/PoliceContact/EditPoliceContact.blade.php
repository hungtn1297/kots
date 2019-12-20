@extends('admin/master')
@section('content')
@section('title')
    Chỉnh sửa thông tin công an
@endsection
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Thông tin công an
                            <small>Tạo mới</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <form action="{{url('admin/policeContact/create')}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$policeContact->id}}">
                        <div class="form-group">
                                <label>Tên</label>
                            <input class="form-control" name="name" required value="{{$policeContact->name}}" />
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input class="form-control" name="phone" required  value="{{$policeContact->phone}}"/>
                        </div>
                        <br>
                        <div class="form-group">
                                <label>Địa chỉ</label>
                                <input class="form-control" name="address" required value="{{$policeContact->address}}"/>
                        </div>
                        <br>
        
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <button onclick="goBack()" class="btn btn-warning">Trở về</button>
                    </form>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
@stop


