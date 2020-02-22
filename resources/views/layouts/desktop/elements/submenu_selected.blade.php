<?php
$currentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();
?>
<li class="nav-item {{ $routeName === $currentRouteName ? 'active' : null }}">
    <a href="{{ route($routeName,$routeParams??[]) }}" class="nav-link">
        <span class="title">{{ $name }}</span>
        @if($routeName === $currentRouteName)
            <span class="selected"></span>
        @endif
    </a>
</li>