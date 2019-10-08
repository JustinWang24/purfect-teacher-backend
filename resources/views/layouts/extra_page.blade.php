<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Smart University | Bootstrap Responsive Admin Template</title>
    <link href="{{ asset('assets/fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/iconic/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/smart_basic.css') }}">
    <link rel="stylesheet" href="{{ asset('css/extra_page.css') }}">
</head>

<body>
@yield('content')
<script src="{{ asset('js/smart_basic.js') }}"></script>
<script src="{{ asset('js/extra_page.js') }}"></script>
</body>
</html>