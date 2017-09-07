<!DOCTYPE html>
<html lang="en">
    <head>
        <title> @yield('title') | teacher verihuo</title>
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

        <?php $cup = config('chaohun.admin_prefix', 'admin').'/'; ?>

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

                    @permission(['all', 'setting-shop'])
                        <li class="has-child {{Request::is($cup.'shop*') ? 'active' : ''}}">
                            <a href="#"> <i class="fa fa-cart-plus"></i>
                                <span>商城管理</span> <i class="fa fa-angle-left pull-right" ></i></a>
                            <ul class="treeview-menu">
                                <li class="{{Request::is($cup.'shop/product*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\Shop\ProductController@index') }}"> <i class="fa fa-leaf"></i> <span>产品管理</span> </a> </li>
                                <li class="{{Request::is($cup.'shop/order*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\Shop\OrderController@index') }}"> <i class="fa fa-leaf"></i> <span>订单管理</span> </a> </li>
                                <li class="{{Request::is($cup.'shop/category*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\Shop\CategoryController@index') }}"> <i class="fa fa-leaf"></i> <span>商品分类</span> </a> </li>
                                <li class="{{Request::is($cup.'shop/attribute*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\Shop\AttributeController@index') }}"> <i class="fa fa-leaf"></i> <span>商品属性</span> </a> </li>
                                <li class="{{Request::is($cup.'shop/brand*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\Shop\BrandController@index') }}"> <i class="fa fa-leaf"></i> <span>品牌管理</span> </a> </li>
                                <li class="{{Request::is($cup.'shop/coupon*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\Shop\CouponController@index') }}"> <i class="fa fa-leaf"></i> <span>促销管理</span> </a> </li>

                            </ul>
                        </li>
                        @endpermission
                        @permission(['all', 'setting-content'])

                        <li  class="has-child {{Request::is($cup.'content*') ? 'active' : ''}}">
                            <a href="#" > <i class="fa fa-book"></i>
                                <span>内容管理</span> <i class="fa fa-angle-left pull-right"></i> </a>
                            <ul class="treeview-menu">
                                <li class="{{Request::is($cup.'content',$cup.'content/*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\ContentController@index') }}"> <i class="fa fa-newspaper-o"></i> <span>资讯文章</span> </a> </li>
                                <li class="{{Request::is($cup.'contentcatalog') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\ContentCatalogController@index') }}"> <i class="fa fa-clone"></i> <span>资讯分类</span> </a> </li>
                                <li class="{{Request::is($cup.'contenttag') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\ContentTagController@index') }}"> <i class="fa fa-tags"></i> <span>资讯标签</span> </a> </li>
                                <li class="{{Request::is($cup.'othercontent') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\OtherContentController@index') }}"> <i class="fa fa-newspaper-o"></i> <span>其它文章</span> </a> </li>
                            </ul>
                        </li>
                        @endpermission

                        @permission(['all', 'setting-customer'])
                        <li > <a href="#"> <i class="fa fa-user"></i> <span>客户管理</span> </a> </li>
                        @endpermission

                        @permission(['all', 'visit-edit'])
                        <li  class="has-child {{Request::is($cup.'visit/*') ? 'active' : ''}}">
                            <a href="#" > <i class="fa fa-bullhorn"></i>
                                <span>访客系统</span> <i class="fa fa-angle-left pull-right"></i> </a>
                            <ul class="treeview-menu">
                                <li class="{{Request::is($cup.'visit/visitor', $cup.'visit/visitor/*') ? 'active' : ''}}">
                                    <a href="/{{Config('chaohun.admin_prefix')}}/visit/visitor "> <i class="fa fa-exclamation-circle"></i> <span>访客设置</span> </a> </li>
                            </ul>
                        </li>
                        @endpermission

                        @permission(['all', 'setting-ad'])
                        <li  class="has-child {{Request::is($cup.'adposition*', $cup.'node*') ? 'active' : ''}}">
                            <a href="#" > <i class="fa fa-bullhorn"></i>
                                <span>广告设置</span> <i class="fa fa-angle-left pull-right"></i> </a>
                            <ul class="treeview-menu">
                                <li class="{{Request::is($cup.'adposition', $cup.'adposition/*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\AdPositionController@index') }}"> <i class="fa fa-exclamation-circle"></i> <span>广告位设置</span> </a> </li>
                                <li class="{{Request::is($cup.'node', $cup.'node/*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\NodeAttrTypeController@index') }}"> <i class="fa fa-exclamation-circle"></i> <span>节点属性类型</span> </a> </li>
                                <li class="{{Request::is($cup.'node', $cup.'node/*') ? 'active' : ''}}">
                                    <a href="{{ action('Admin\NodeTypeController@index') }}"> <i class="fa fa-exclamation-circle"></i> <span>节点类型</span> </a> </li>
                            </ul>
                        </li>
                        @endpermission
                        @if (Auth::guard('admin')->user()->id === 1 || Auth::guard('admin')->user()->can('all', 'setting-perms'))
                            <li  class="has-child {{Request::is($cup.'role*', $cup.'userrole*') ? 'active' : ''}}">
                                <a href="#" > <i class="fa fa-user-secret"></i>
                                    <span>权限设置</span> <i class="fa fa-angle-left pull-right"></i> </a>
                                <ul class="treeview-menu">
                                    <li class="{{Request::is($cup.'userrole', $cup.'userrole/*') ? 'active' : ''}}">
                                        <a href="{{ action('Admin\UserRoleController@index') }}"> <i class="fa fa-user fa-lock"></i> <span>用户角色配置</span> </a> </li>
                                    <li class="{{Request::is($cup.'role',$cup.'role/*') ? 'active' : ''}}">
                                        <a href="{{ action('Admin\RoleController@index') }}"> <i class="fa fa-group"></i> <span>角色设置</span> </a> </li>
                                </ul>
                            </li>
                        @endif
                        @permission(['all', 'setting-sys'])
                        <li  class="has-child {{Request::is($cup.'generalsetting',$cup.'generalsetting/*') ? 'active' : ''}}">
                            <a href="#" > <i class="fa fa-cog"></i>
                                <span>系统设置</span> <i class="fa fa-angle-left pull-right"></i> </a>
                            <ul class="treeview-menu">
                                <!--<li> <a href=""> <i class="fa fa-heart"></i> <span>热搜配置</span> </a> </li>-->
                                <li class="{{Request::is($cup.'generalsetting',$cup.'generalsetting/*') ? 'active' : ''}}">  <a href="{{ action('Admin\GeneralSettingController@index') }}"> <i class="fa fa-gears"></i> <span>配置项</span> </a> </li>
                            </ul>
                        </li>
                        @endpermission

                    @permission(['all', 'seo-friend-link'])
                    <li class="{{Request::is($cup.'friendlink*') ? 'active' : ''}}">
                        <a href="{{ action('Admin\FriendLinkController@index') }}"> <i class="fa fa-leaf"></i> <span>友情链接</span> </a> </li>
                    @endpermission


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
