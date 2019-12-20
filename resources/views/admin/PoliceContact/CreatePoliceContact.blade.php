@extends('admin/master')
@section('content')
@section('title')
    Tạo mới thông tin công an
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
                        <div class="form-group">
                                <label>Tên</label>
                                <input class="form-control" name="name" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input class="form-control" name="phone" required />
                        </div>
                        <br>
                        <div class="form-group">
                                <label>Địa chỉ</label>
                                <input class="form-control" name="address" required />
                        </div>
                        <br>
        
                            <button type="submit" class="btn btn-success">Tạo mới</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                    </form>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
@stop


