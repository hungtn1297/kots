@extends('admin/master')
@section('content')
@section('title')
    Chỉnh sửa tin tức
@endsection
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Tin tức
                            <small>Chỉnh sửa</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <form action="{{url('admin/news/create')}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$news->id}}">
                        <div class="form-group">
                                <label>Tiêu đề</label>
                            <input class="form-control" name="title" value="{{$news->title}}" required />
                        </div>
                        <br>

                        <div class="form-group">
                                <label>Hình ảnh</label>
                                <input type="file" name="image" />
                        </div>
                        <br>
                        <img src="{{URL::asset("$news->image")}}" alt="">
                        <br>

                        <div class="form-group">
                                <label>Nội dung</label>
                                <textarea name="content" id="content" rows="10" cols="150">
                                    {{$news->content}}
                                </textarea>
                        </div>
                        <br>
                        
                            <button type="submit" class="btn btn-success" onsubmit="submitDelete();">Cập nhật</button>
                            <br>
                            <br>
                    </form>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    <script>
        // Thay thế <textarea id="post_content"> với CKEditor
        CKEDITOR.replace( 'content' );// tham số là biến name của textarea
    </script>
@stop


