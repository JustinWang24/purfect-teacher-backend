<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ $api_token }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ $pageTitle }}</title>
    <link rel="stylesheet" href="{{ asset('/css/h5/user.css') }}">
</head>
<body>
<div class="app-page-wrapper">
    @yield('content')
</div>
<script src="{{ asset('/js/h5/teacher.js') }}"></script>
</body>
</html>