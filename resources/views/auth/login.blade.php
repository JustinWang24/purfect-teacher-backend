<?php //use Illuminate\Support\Facades\Session;Session::flush(); ?>
@extends('layouts.extra_page')

@section('content')
    <div class="limiter"  style="position: relative;">
        <div class="container-login100 page-background">

        </div>
        <div class="wrap-login100" style="position: absolute; z-index: 9999; top: 20%; left: 36%;">
            <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                @csrf
                <span class="login100-form-logo">
						<img alt="" src="{{ asset('assets/img/logo-2.png') }}">
					</span>
                <span class="login100-form-title p-b-34 p-t-27">
						用户登录
					</span>
                <div class="wrap-input100 validate-input" data-validate="请输入手机号码">
                    <input id="email" placeholder="请输入手机号码" type="text" class="input100 @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile">
                    @error('mobile')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                    <span class="focus-input100" data-placeholder="&#xf207;"></span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="请输入密码">
                    <input id="password" placeholder="请输入密码" type="password" class="input100 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>
                <div class="contact100-form-checkbox">
                    <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="label-checkbox100" for="ckb1">
                        记住我
                    </label>
                </div>
                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
                        登录
                    </button>
                </div>
                <div class="text-center p-t-30">
                    <a class="txt1" href="{{ route('password.request') }}">
                        忘记密码?
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
