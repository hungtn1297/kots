<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <title>@yield('title')</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ URL::asset('admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{ URL::asset('admin/bower_components/metisMenu/dist/metisMenu.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ URL::asset('admin/dist/css/sb-admin-2.css') }} " rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ URL::asset('admin/bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link href="{{ URL::asset('admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ URL::asset('admin/bower_components/datatables-responsive/css/dataTables.responsive.css') }}" rel="stylesheet">

    {{-- CK Editor --}}
    <script src="{{ URL::asset('admin/ckeditor/ckeditor.js')}}"></script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Quản trị</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Thông tin cá nhân</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Đăng xuất</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Tìm kiếm...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                                <a href="{{url('admin/analytic')}}"><img src="{{URL::asset('admin/image/report.png')}}" width="20" height="20"> Thống kê</a>
                                
                                <!-- /.nav-second-level -->
                            </li>
                        <li>
                            <a href="#"><img src="{{URL::asset('admin/image/citizen.png')}}" width="20" height="20"> Người dân<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{url('admin/citizen/list')}}">Danh sách người dân</a>
                                </li>   
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><img src="{{URL::asset('admin/image/knight.png')}}" width="20" height="20"> Hiệp sĩ<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{url('admin/knight/list')}}">Danh sách hiệp sĩ</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><img src="{{URL::asset('admin/image/team.png')}}" width="20" height="20">Nhóm hiệp sĩ 
                                @yield('requestTeam')
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{url('admin/knightTeam/list')}}">Danh sách nhóm hiệp sĩ</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><img src="{{URL::asset('admin/image/warning.png')}}" width="20" height="20"> Sự cố<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{url('admin/case/list')}}">Danh sách sự cố</a>
                                </li>
                            </ul>
                                <!-- /.nav-second-level -->
                            </li>
                        <li>
                            <a href="#"><img src="{{URL::asset('admin/image/news.png')}}" width="20" height="20"> Tin tức<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{url('admin/news/list')}}">Danh sách tin tức</a>
                                </li>
                                <li>
                                    <a href="{{url('admin/news/create')}}">Tạo mới tin tức</a>
                                </li>
                                <li>
                                    <a href="{{url('admin/news/crawl')}}">Cào dữ liệu</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><img src="{{URL::asset('admin/image/robber.png')}}" width="20" height="20"> Tội phạm<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{url('admin/criminal/list')}}">Danh sách tội phạm</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><img src="{{URL::asset('admin/image/policeman.png')}}" width="20" height="20"> Thông tin liên lạc công an<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{url('admin/policeContact/list')}}">Danh sách thông tin</a>
                                </li>
                                <li>
                                    <a href="{{url('admin/policeContact/create')}}">Tạo mới thông tin</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><img src="{{URL::asset('admin/image/feedback.png')}}" width="20" height="20"> Phản hồi<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{url('admin/feedback/list')}}">Danh sách phản hồi</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="{{url('admin/dangerousStreets/')}}"><img src="{{URL::asset('admin/image/map.png')}}" width="20" height="20"> Đoạn đường nguy hiểm</a>
                            
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

    
    @yield('content')


    <script src="{{ URL::asset('admin/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ URL::asset('admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ URL::asset('admin/bower_components/metisMenu/dist/metisMenu.min.js') }}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ URL::asset('admin/dist/js/sb-admin-2.js') }}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{ URL::asset('admin/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        // $('#dataTables-example thead tr').clone(true).appendTo( '#dataTables-example thead' );
        // $('#dataTables-example thead tr:eq(1) th').each( function (i) {
        // var title = $(this).text();
        // $(this).html( '<input type="text" placeholder="Lọc" style="width: 60%"/>');
 
        // $( 'input', this ).on( 'keyup change', function () {
        //     if ( table.column(i).search() !== this.value ) {
        //         table
        //             .column(i)
        //             .search( this.value )
        //             .draw();
        //     }
        // } );
        // } );
        var table = $('#dataTables-example').DataTable({
                responsive: true,
                // "order": []
                // columnDefs: [ {
                //     'targets': [0], /* column index [0,1,2,3]*/
                //     'orderable': false, /* true or false */
                // }],
                "language": {
                    "decimal":        "",
                    "emptyTable":     "Không có dữ liệu",
                    "info":           "Hiển thị _START_ tới _END_ trong tổng _TOTAL_ dòng",
                    "infoEmpty":      "Hiển thị 0 tới 0 trong tổng 0 dòng",
                    "infoFiltered":   "(Lọc trong tổng _MAX_ dòng)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "Hiển thị _MENU_ dòng",
                    "loadingRecords": "Đang tải...",
                    "processing":     "Đang xử lí...",
                    "search":         "Tìm kiếm:",
                    "zeroRecords":    "Không tìm thấy kết quả",
                    "paginate": {
                        "first":      "Đầu",
                        "last":       "Cuối",
                        "next":       "Trang sau",
                        "previous":   "Trang trước"
                    },
                    "aria": {
                        "sortAscending":  ": Sắp xếp theo thứ tự tăng dần",
                        "sortDescending": ": Sắp xếp theo thứ tự giảm dần"
                    }
                },
                "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
            }
        });
    });


    function goBack(){
        window.history.back();
    }

    </script>
</body>
</html>