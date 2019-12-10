<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Tạo mới tin tức</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Tin tức
                            <small>Tạo mới</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <form action="{{url('admin/news/create')}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="form-group">
                                <label>Tiêu đề</label>
                                <input class="form-control" name="title" required />
                        </div>
                        <br>

                        <div class="form-group">
                                <label>Hình ảnh</label>
                                <input type="file" name="image" required />
                        </div>
                        <br>

                        <div class="form-group">
                                <label>Nội dung</label>
                                <textarea name="content" id="content" rows="10" cols="200"></textarea>
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
    @include('admin/footer')
    <script>
        // Thay thế <textarea id="post_content"> với CKEditor
        CKEDITOR.replace( 'content' );// tham số là biến name của textarea
    </script>
</body>
</html>


