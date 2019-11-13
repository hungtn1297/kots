<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Tạo mới nhóm hiệp sĩ</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Nhóm hiệp sĩ
                            <small>Tạo mới</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <form action="{{url('admin/knightTeam/create')}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="form-group" >
                                <label>Tên nhóm</label>
                                <input class="form-control" name="name" required style="width: 50%"/>
                        </div>
                        <br>

                        <div class="form-group" >
                            <label>Địa chỉ</label>
                            <input class="form-control" name="address" required style="width: 50%"/>
                        </div>
                        <br>

                        <div class="form-group">
                            <label>Đội trưởng</label>
                        @if (!empty($listKnight))
                            
                            <select name="leaderId" id="">
                                @foreach ($listKnight as $knight)
                                    <option value="{{$knight->id}}">
                                        {{substr_replace($knight->id, '*****', 0, strlen($knight->id)-5)}} - {{$knight->name}}
                                    </option>
                                @endforeach
                            </select>
                            
                        @else
                            <label>Hiện không có hiệp sĩ nào có thể làm đội trưởng</label>
                        @endif
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


