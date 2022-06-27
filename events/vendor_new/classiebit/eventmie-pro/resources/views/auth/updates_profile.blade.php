@extends('eventmie::auth.authapp')

@section('title')
    @lang('eventmie-pro::em.Update_Profile')
@endsection

@section('authcontent')

<h2  style="margin-top:-1.5em;" class="title">@lang('eventmie-pro::em.Update_Profile')</h2>
<div class="lgx-registration-form">

    <form method="POST" action="{{ route('eventmie.update_profile') }}"  @submit.prevent="submit" v-if="!savingSuccessful">
    @if($msg)
  
     <div class="alert alert-success col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12">
     <button type="button" class="close" data-dismiss="alert">×</button>
    <b> {{$msg}}
        
    </div>
   
    @endif
    
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       
        <input id="name" type="text"  class="wpcf7-form-control form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus placeholder="@lang('eventmie-pro::em.name')">
        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
        <input id="phone" type="text" value="{{$phone}}"  class="wpcf7-form-control form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required autofocus placeholder="@lang('eventmie-pro::em.phone')">
       
        <input id="email" type="email"   class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="@lang('eventmie-pro::em.email')">
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <input id="password" type="password"   class="wpcf7-form-control form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="@lang('eventmie-pro::em.password')">
        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif

        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" name="accept" id="accept" checked value="1">
            <label class="form-check-label" for="accept">
                @lang('eventmie-pro::em.accept') &nbsp;<a href="{{ route('eventmie.page', ['page'=>'terms']) }}"> @lang('eventmie-pro::em.terms')</a>
            </label>
        </div>

        <button type="submit" class="lgx-btn lgx-btn-white btn-block"><i class="fas fa-door-open"></i> @lang('eventmie-pro::em.activates')</button>

        <div class="row">
            <div class="col-md-12">
                <div class="lgx-links">
                    <a class="btn btn-link pull-left" href="{{ route('eventmie.password.request') }}">
                        @lang('eventmie-pro::em.forgot_password')
                    </a>
                    <a class="btn btn-link pull-right" href="{{ route('eventmie.login') }}">
                        @lang('eventmie-pro::em.login')
                    </a>
                </div>
            </div>
        </div>
        
        <hr style="border-top: 2px solid #eee;">
        <div class="row">
            <div class="col-md-4 text-left">
                <h4 class="col-white">@lang('eventmie-pro::em.continue_with')</h4>
            </div>
            <div class="col-md-8 text-right">
                <a href="{{ route('eventmie.oauth_login', ['social' => 'facebook'])}}" class="lgx-btn lgx-btn-white lgx-btn-sm"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="{{ route('eventmie.oauth_login', ['social' => 'google'])}}" class="lgx-btn lgx-btn-white lgx-btn-sm"><i class="fab fa-google"></i> Google</a>
            </div>
        </div>
    </form>
</div>

@endsection