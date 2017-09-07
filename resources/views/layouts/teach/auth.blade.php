<!DOCTYPE html>
<html lang="en">
    <head>
        <title> @yield('title') | teacher in verihuo</title>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="IE=edge" http-equiv="X-UA-Compatible" />


        <link media="all" type="text/css" rel="stylesheet" href="/packages/sleepingowl/default/css/common.css">
        <link media="all" type="text/css" rel="stylesheet" href="/assets/admin/css/common.css">
        @stack('stylesheets')

    </head>

    <body>
        <div class="wrappers">
            <div class="content-wrapper">
                <div class="content body">
                    @yield('content') 
                </div>
            </div>
        </div>

        <div style="display:none">

            <script src="{{ asset('assets/libs/jquery-1.12.4.min.js') }}"></script>
            <script src="{{ asset('assets/libs/jquery-ui-1.12.0-rc.2/jquery-ui.min.js') }}"></script>

            <!-- <script src="/packages/sleepingowl/default/libs/jquery/js/jquery.min.js"></script> -->
            <!-- <script src="/packages/sleepingowl/default/js/libraries.js"></script> -->
            <!-- <script src="/packages/sleepingowl/default/libs/flow.js/js/flow.min.js"></script> -->
            <!-- <script src="/packages/sleepingowl/default/libs/Sortable/js/Sortable.min.js"></script> -->
            <!-- <script src="/packages/sleepingowl/default/libs/select2/js/select2.full.min.js"></script> -->
            <!-- <script src="/packages/sleepingowl/default/libs/metisMenu/js/metisMenu.min.js"></script> -->
            <script src="/packages/sleepingowl/default/libs/AdminLTE/js/app.min.js"></script>
            <!-- <script src="/packages/sleepingowl/default/libs/Sortable/js/jquery.binding.js"></script> -->

            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!--[if lt IE 9]>
                 <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                 <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                 <![endif]-->
            <script src="{{ asset('assets/admin/js/common.js') }}"></script>

            @stack('scripts')
        </div>
    </body>
</html>
