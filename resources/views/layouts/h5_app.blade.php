<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Auth::user()->api_token ?? null }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ $pageTitle }}</title>
    <link rel="stylesheet" href="{{ asset('/css/h5/'.$appName.'.css') }}">
</head>
<body>
<div class="app-page-wrapper">
    @yield('content')
</div>
<script src="{{ asset('/js/h5/'.$appName.'.js') }}"></script>
</body>
</html>