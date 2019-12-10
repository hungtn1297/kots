<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Danh sách nhóm hiệp sĩ</title>
</head>
<body>
    @include('admin/header')
    <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Nhóm hiệp sĩ
                            <small>Danh sách</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr align="center">                        
                                <th>Tên đội</th>
                                <th>Đội trưởng</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teams as $team)
                                <tr align="center">         
                                    <td>{{$team->name}}</td>
                                    <td>{{$team->leaderName}}</td>
                                    <td>
                                        <form action="{{url('admin/knightTeam/edit')}}" method="get">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$team->id}}">
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
    @include('admin/footer')
</body>
</html>


