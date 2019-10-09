<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ ($pageTitle??'') . config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('assets/fonts/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/fonts/material-design-icons/material-icon.css') }}" rel="stylesheet" type="text/css" />
    <!--bootstrap -->
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/summernote/summernote.css') }}" rel="stylesheet">
    <!-- Material Design Lite CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/material/material.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/material_style.css') }}">
    <!-- inbox style -->
    <link rel="stylesheet" href="{{ asset('assets/css/pages/inbox.min.css') }}">
    <!-- Theme Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme/hover/theme_style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme/hover/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme/hover/theme-color.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-white white-sidebar-color logo-indigo">
    <div id="app" class="page-wrapper">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-blockui/jquery.blockui.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- bootstrap -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.js') }}"></script>
    <script src="{{ asset('assets/js/pages/sparkline/sparkline-data.js') }}"></script>
    <!-- Common js-->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <script src="{{ asset('assets/js/theme-color.js') }}"></script>
    <!-- material -->
    <script src="{{ asset('assets/plugins/material/material.min.js') }}"></script>
    <!-- chart js -->
    <script src="{{ asset('assets/plugins/chart-js/Chart.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/chart-js/utils.js') }}"></script>
    <script src="{{ asset('assets/js/pages/chart/chartjs/home-data.js') }}"></script>
    <!-- summernote -->
    <script src="{{ asset('assets/plugins/summernote/summernote.js') }}"></script>
    <script src="{{ asset('assets/js/pages/summernote/summernote-data.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
