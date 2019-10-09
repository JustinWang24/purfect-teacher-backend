<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts/desktop/head')
<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-white white-sidebar-color logo-indigo">
<div id="app" class="page-wrapper">
    @include('layouts/desktop/top_nav')
    <div class="page-container">
    @include('layouts/desktop/sidebar')
        <div class="page-content-wrapper">
        @yield('content')
        </div>
    @include('layouts/desktop/sidebar_chat')
    </div>
    @include('layouts/desktop/footer')
</div>
@include('layouts/desktop/js')
</body>
</html>
