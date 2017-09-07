<!DOCTYPE html>
<html lang="en">
    <head>
        <title> @yield('title') | verihuo后台管理系统</title>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="IE=edge" http-equiv="X-UA-Compatible" />

        <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-ui/themes/smoothness/jquery-ui.min.css') }}" />


        <link media="all" type="text/css" rel="stylesheet" href="/packages/sleepingowl/default/libs/select2/css/select2.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="/packages/sleepingowl/default/libs/metisMenu/css/metisMenu.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="/packages/sleepingowl/default/css/common.css">
        <link media="all" type="text/css" rel="stylesheet" href="/packages/sleepingowl/default/libs/font-awesome/css/font-awesome.min.css">

        <link media="all" type="text/css" rel="stylesheet" href="/assets/libs/datatable/media/css/dataTables.bootstrap.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="/assets/libs/bootstrap-daterangepicker/daterangepicker.css">

        <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/css/bootstrap-switch.css') }}">
        <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/css/bootstrap-multiselect.css') }}">
        <!-- <link media="all" type="text/css" rel="stylesheet" href="/assets/libs/datatable/media/css/jquery.dataTables.min.css"> -->

        <link rel="stylesheet" href="{{ asset('assets/libs/jquery-confirm/css/jquery-confirm.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}" />

        <link rel="stylesheet" href="/assets/libs/lobibox/dist/css/lobibox.min.css"/>

        <link media="all" type="text/css" rel="stylesheet" href="/assets/admin/css/common.css">
        <link media="all" type="text/css" rel="stylesheet" href="/assets/admin/css/main.css">
        @stack('stylesheets')

        <?php $cup = config('constants.ADMIN_PREFIX', 'admin').'/'; ?>

        <script src="{{ asset('assets/libs/jquery-1.12.4.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jquery-ui-1.12.0-rc.2/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jquery.validate.min.js') }}"></script>
        <script src="/assets/libs/lobibox/dist/js/lobibox.min.js"></script>
        <script src="/assets/js/app.js"></script>
        @include('elements/js')

    </head>

    <body class="skin-blue sidebar-mini">
        <div class="wrappers">
            <header class="main-header">
                <a href="#" class="logo">
                    <span class="logo-lg"><img src="{{ asset('/assets/admin/img/logo-long.png') }}" style="margin-left:-25px;width:200px;height:40px"> <span class="pull-left">SleepingOwl</span></span>
                    <span class="logo-mini"></span>
                </a>

                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>

                    <!--<ul class="nav navbar-nav">
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                    </ul>-->

                    <div class="title">@yield('title')</div>
                    <div class="admin-logout">
                        {!! Form::open(['url' => action('Admin\Auth\AuthController@logout'), 'method' => 'get']) !!}
                        欢迎管理员：{{$user->name}}<button type="submit" class="btn btn-sm btn-primary">退出系统</button>
                        {!! Form::close() !!}
                    </div>
                </nav>
            </header>

            <aside class="main-sidebar"> <section class="sidebar">
                <ul class="sidebar-menu">
                    <li  class="{{Request::is($cup.'dashboard') ? 'active' : ''}}">
                        <a href="{{ action('Admin\DashboardController@index') }}"> <i class="fa fa-dashboard"></i> <span>控制面板</span> </a> </li>

                        <li  class="has-child {{Request::is($cup.'content*') ? 'active' : ''}}">
                            <a href="#" > <i class="fa fa-book"></i>
                                <span>博客管理</span> <i class="fa fa-angle-left pull-right"></i> </a>
                            <ul class="treeview-menu">
                                <li class="{{Request::is($cup.'content',$cup.'content/*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\ContentController@index') }}"> <i class="fa fa-newspaper-o"></i> <span>博客内容</span> </a> </li>
                                <li class="{{Request::is($cup.'contentcatalog') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\ContentCatalogController@index') }}"> <i class="fa fa-clone"></i> <span>博客分类</span> </a> </li>
                                <li class="{{Request::is($cup.'othercontent') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\OtherContentController@index') }}"> <i class="fa fa-newspaper-o"></i> <span>右侧博客</span> </a> </li>
                            </ul>
                        </li>

                        <li class="{{Request::is($cup.'customer*') ? 'active' : ''}}"> <a href="{{ action('Admin\CustomerController@index') }}"> <i class="fa fa-user"></i> <span>学生管理</span> </a> </li>

                        <li  class="has-child {{Request::is($cup.'adposition*', $cup.'node*') ? 'active' : ''}}">
                            <a href="#" > <i class="fa fa-bullhorn"></i>
                                <span>Verihuo设置</span> <i class="fa fa-angle-left pull-right"></i> </a>
                            <ul class="treeview-menu">
                                <li class="{{Request::is($cup.'adposition', $cup.'adposition/*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\AdPositionController@index') }}"> <i class="fa fa-exclamation-circle"></i> <span>Verihuo数据配置</span> </a> </li>
                                <li class="{{Request::is($cup.'node', $cup.'node/*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\NodeAttrTypeController@index') }}"> <i class="fa fa-exclamation-circle"></i> <span>数据节点属性类型</span> </a> </li>
                                <li class="{{Request::is($cup.'node', $cup.'node/*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\NodeTypeController@index') }}"> <i class="fa fa-exclamation-circle"></i> <span>数据节点类型</span> </a> </li>
                            </ul>
                        </li>

                    <li class="{{Request::is($cup.'class*') ? 'active' : ''}}">
                        <a href="{{ action('Admin\ClassController@index') }}"> <i class="fa fa-book"></i> <span>课程管理</span> </a> </li>

                    <li class="{{Request::is($cup.'school*') ? 'active' : ''}}">
                        <a href="{{ action('Admin\SchoolController@index') }}"> <i class="fa fa-gift"></i> <span>学校管理</span> </a> </li>


                    <li class="{{Request::is($cup.'teacher*') ? 'active' : ''}}">
                        <a href="{{ action('Admin\TeacherController@index') }}"> <i class="fa fa-gift"></i> <span>老师管理</span> </a> </li>


                </ul>
            </section></aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <h1>
                        @yield('title')
                    </h1>
                </div>

                <div class="content body">
                    @yield('content') 
                </div>
            </div>
        </div>

        <div style="display:none">

            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!--[if lt IE 9]>
                 <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                 <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                 <![endif]-->

            <script src="/assets/libs/jquery.print.js" ></script>
            <script src="/packages/sleepingowl/default/libs/AdminLTE/js/app.min.js"></script>
            <script src="/assets/libs/datatable/media/js/jquery.dataTables.min.js"></script>
            <script src="/assets/libs/datatable/media/js/dataTables.bootstrap.min.js"></script>
            <script src="/assets/libs/bootstrap-daterangepicker/moment.min.js"></script>
            <script src="/assets/libs/bootstrap-daterangepicker/daterangepicker.js"></script>

            <script src="/assets/libs/datatable/extensions/Responsive/js/dataTables.responsive.js"></script>
            <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
            <script src="{{ asset('assets/libs/jquery.jeditable.mini.js') }}"></script>
            <script src="{{ asset('assets/libs/underscore-min.js') }}"></script>
            <script src="{{ asset('assets/libs/jquery-confirm/dist/jquery-confirm.min.js') }}"></script>
            <script src="{{ asset('assets/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

            <script src="{{ asset('assets/js/bootstrap-switch.js') }}"></script>
            <script src="{{ asset('assets/js/bootstrap-multiselect.js') }}"></script>

            <script src="{{ asset('assets/admin/js/common.js') }}"></script>

            @stack('scripts')
        </div>
    </body>
</html>
