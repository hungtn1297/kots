<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <title>Admin</title>

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
                <a class="navbar-brand" href="admin">Admin</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
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
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                                <a href="#"><img src="{{URL::asset('admin/image/report.png')}}" width="20" height="20"> Thống kê</a>
                                
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
                            <a href="#"><img src="{{URL::asset('admin/image/team.png')}}" width="20" height="20">Nhóm hiệp sĩ<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{url('admin/knightTeam/list')}}">Danh sách nhóm hiệp sĩ</a>
                                </li>
                                <li>
                                    <a href="{{url('admin/knightTeam/create')}}">Tạo mới nhóm hiệp sĩ</a>
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
                                    <a href="{{url('admin/Feedback/list')}}">Danh sách phản hồi</a>
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