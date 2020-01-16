<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Auth::user()->api_token ?? null }}">
    <meta name="school" content="{{ session('school.uuid') }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ ($pageTitle??'')}}-{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('assets/fonts/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/fonts/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/fonts/material-design-icons/material-icon.css') }}" rel="stylesheet" type="text/css" />
    <!--bootstrap -->
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/summernote/summernote.css') }}" rel="stylesheet">
    <!-- Material Design Lite CSS -->
    <!-- inbox style -->
    <link rel="stylesheet" href="{{ asset('assets/css/pages/inbox.min.css') }}">
    <!-- Theme Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme/hover/theme_style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme/hover/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/formlayout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme/hover/theme-color.css') }}">
    <!-- select 2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2-bootstrap.min.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- dropzone -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/dropzone.css') }}">

    <!-- redactor -->
    @if($redactor)
    <link rel="stylesheet" href="{{ asset('redactor/redactor.min.css') }}">
    @endif
</head>
