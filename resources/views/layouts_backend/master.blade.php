<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Barcode Administrator Page</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7-->
        <link rel="stylesheet" href="{{asset('template/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('template/bower_components/font-awesome/css/font-awesome.min.css')}}">

        {{-- link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"> --}}

        
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{asset('template/bower_components/Ionicons/css/ionicons.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('template/dist/css/AdminLTE.min.css')}}">
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
            page. However, you can choose any other skin. Make sure you
            apply the skin class to the body tag so the changes take effect. -->
        <link rel="stylesheet" href="{{asset('template/dist/css/skins/skin-blue.min.css')}}">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Google Font -->
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style-admin.css') }}">
        
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/jquery.combobox.js') }}"></script>
        <script src="{{ asset('js/plugin/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset('js/function.js') }}"></script>
        <script src="{{ asset('js/checkbox.js') }}"></script>
        <script src="{{ asset('js/tableHeadFixer.js') }}"></script>
        <script src="https://unpkg.com/sweetalert2@7.12.16/dist/sweetalert2.all.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        {{-- <script src="{{ asset('js/demo.js') }}"></script>             --}}
    </head>
    
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header">
                <!-- Logo -->
                <a href="#" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>B-</b>AD</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Barcode-</b>Admin</span>
                </a>
                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    @if(empty(Auth::user()->avatar))
                                    <img src="{{ asset('images/dashboard/avatar.png') }}" class="user-image" style="width:64px;height:64px;border-radius:100%;float: left;margin-right:5px;"  alt="User Image">
                                    @else
                                    <img style="border-radius:100%;float: left;margin-right:5px;" src="{{ asset('uploads/users/'.Auth::user()->avatar) }} " class="user-image" alt="User Image">
                                    @endif
                                    <span class="hidden-xs" style="word-wrap: break-word;"> {{ str_limit(Auth::user()->name, $limit = 30, $end = '...') }}</span><span class="add-caret">&nbsp;</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header">
                                        <img src="{{ asset('uploads/users/'.Auth::user()->avatar) }} " class="img-circle" alt="User Image">
                                        <p>
                                            {{Auth::user()->name}} <br>
                                            <small>{{Auth::user()->email}}</small>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <!-- Nothing -->
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="{{ route('getTaikhoanInfo',['id'=>Auth::user()->id ])}}" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ url('logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <!-- <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                                </li> -->
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="{{ asset('uploads/users/'.Auth::user()->avatar) }}" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p>{{Auth::user()->name}}</p>
                            <!-- Status -->
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    
                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree">
                       
                        <li class="treeview @if ( Request::is('admin/user*') || Request::is('admin/roles*') ) active @endif">
                        
                            <a href=""><i class="fa fa-users"></i> <span>Accounts</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            </a>
                            <ul class="treeview-menu ">
                                <li class="@if ( Request::is('admin/user*') ) active @endif"><a href="{{ route('getUserList') }}">- Account management</a></li>
                                <li class="@if ( Request::is('admin/roles*') ) active @endif"><a href="{{ route('getRoleList') }}">- Roles</a></li>
                            </ul>
                            </li>
                            
                        <li class="treeview @if( Request::is('admin/page/*')) active @endif">
                            <a href="#"><i class="fa fa-file-archive-o"></i> <span>Sub-pages</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="@if( Request::is('admin/page/contactus*')) active @endif"><a href="{{route('getContactUsPage')}}">- Contact Us</a></li>
                                <li class="@if( Request::is('admin/page/edit*') || Request::is('admin/page/add*') || Request::is('admin/page/list*')) active @endif"><a href="{{route('getPageList')}}">- More sub-pages</a></li>
                            </ul>
                        </li>
                     
                        <li class="treeview @if( Request::is('admin/setting/getSettingPriceBarCode') || Request::is('admin/setting/getSettingBarCodeFree')) active @endif">
                            <a href="#"><i class="fa fa-barcode"></i> <span>Barcode Settings</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="@if( Request::is('admin/setting/getSettingPriceBarCode*')) active @endif"><a href="{{route('getSettingPriceBarCode')}}">- Barcode Price</a></li>
                                <li class="@if( Request::is('admin/setting/getSettingBarCodeFree*')) active @endif"><a href="{{route('getSettingBarCodeFree')}}">- Amount of Free Barcode</a></li>
                            </ul>
                        </li>
                        <li class="@if( Request::is('admin/setting/getSettingMessage*')) active @endif"><a href="{{route('getSettingMessage')}}"><i class="fa fa-language"></i> <span>Language Settings</span></a></li>
                        <li class="@if( Request::is('admin/setting/emailConfig*')) active @endif"><a href="{{route('emailConfig')}}"><i class="fa fa-newspaper-o"></i> <span>Email Settings</span></a></li>
                        <li><a href="/admin/stats?page=summary&days=7"><i class="fa fa-pie-chart"></i> <span>Statistics</span></a></li>
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>
            <!-- Content Wrapper. Contains page content -->
            <div class="container-fluid content-wrapper">
                @yield('content')
            </div>
            <!-- /.content-wrapper -->
            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <div class="pull-right hidden-xs">
                   
                </div>
                <!-- Default to the left -->
                <strong>Copyright &copy; 2018 <a href="http://tohsoft.com">TOHsoft Co., Ltd</a>.</strong> All rights reserved.
            </footer>
        </div>

        <!-- ./wrapper -->

        <script src="{{asset('template/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <script src="{{ asset('js/jquery-ui.js') }}"></script>
        <script src="{{ asset('js/general.js') }}"></script>        
        <script src="{{asset('dist/ajsr-js-confirm.min.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('dist/ajsr-confirm.css')}}">
        <!-- REQUIRED JS SCRIPTS -->
        <!-- AdminLTE App -->
        <script src="{{asset('template/dist/js/adminlte.min.js')}}"></script>
        <!-- Optionally, you can add Slimscroll and FastClick plugins.
            Both of these plugins are recommended to enhance the
            user experience. -->
        <script>
        var baseURL="<?php echo URL::to('/'); ?>";
        </script>
    </body>
</html>