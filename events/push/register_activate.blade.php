@extends('eventmie::auth.authapp')

@section('title')
    @lang('eventmie-pro::em.activate')
@endsection

@section('authcontent')


<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- LOGO -->
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td bgcolor="ffffff" align="center" valign="top"
                        style="padding: px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400;  line-height: 48px;">

                        <h3 style="color:#C70039; margin-top:-1px;">Holiday Landmark</h3>

                        <h2 style="font-weight:400; font-size: 30px; margin-top:-4%;"><u>Activate</u>
                        </h2>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px; ">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td bgcolor="#ffffff" align="left"
                        style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <div class="lgx-registration-form">
                            <form method="POST" action="{{ route('eventmie.register_activate') }}"
                                @submit.prevent="submit" v-if="!savingSuccessful">
                                @if(session()->get('message'))
                                @if(session()->get('msg'))
                                @if(session()->get('success'))
                                <div class="alert alert-success col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <b> {{ session()->get('message') }}
                                        {{ session()->get('msg') }}</b><br><br>
                                    {{ session()->get('success') }}
                                </div>
                                @endif
                                @endif
                                @endif
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <h4 style="margin-top:-8%; font-size:14px;">Name</h4>
                                <input id="name" type="text" value="{{ $name }}"
                                    style="height:40px; color:black; margin-top:-2%;"
                                    class="wpcf7-form-control form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    name="name" value="{{ old('name') }}" required autofocus
                                    placeholder="@lang('eventmie-pro::em.name')">
                                @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                                <h4 style="margin-top:-3%; font-size:14px;">Email</h4>
                                <input id="email" type="email" value="{{ $email  }}"
                                    style="height:40px; color:black; margin-top:-2%;"
                                    class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    name="email" value="{{ old('email') }}" required
                                    placeholder="@lang('eventmie-pro::em.email')">
                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif

                                <h4 style="margin-top:-3%; font-size:14px;">Mobile Number</h4>
                                <input id="phone" type="text" style="color:black; height:40px; margin-top:-2%;"
                                    class="wpcf7-form-control form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    name="phone" value="{{ old('phone') }}" required autofocus
                                    placeholder="@lang('eventmie-pro::em.phone')">
                                @if ($errors->has('phone'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif

                                <h4 style="margin-top:-3%; font-size:14px;">Password</h4>
                                <input id="password" type="password" style="color:black; height:40px; margin-top:-2%;"
                                    class="wpcf7-form-control form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password" required placeholder="@lang('eventmie-pro::em.password')">
                                @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif

                                <h4 style="margin-top:-3%; font-size:14px;">Confirm Password</h4>
                                <input id="confirm_password" type="password"
                                    style="color:black; height:40px; margin-top:-2%;"
                                    class="wpcf7-form-control form-control{{ $errors->has('confirm_password') ? ' is-invalid' : '' }}"
                                    name="confirm_password" required
                                    placeholder="@lang('eventmie-pro::em.confirm_password')">
                                @if ($errors->has('confirm_password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('confirm_password') }}</strong>
                                </span>
                                @endif

                                <div class="form-check text-left" style="margin-top:-3%;">
                                    <input class="form-check-input" type="checkbox" name="accept" id="accept" checked
                                        value="1">
                                    <label class="form-check-label" for="accept" style="color:black;">
                                        @lang('eventmie-pro::em.accept') <a
                                            href="{{ route('eventmie.page', ['page'=>'terms']) }}">
                                            @lang('eventmie-pro::em.terms')</a>
                                    </label>
                                </div>

                                <button type="submit" class="lgx-btn lgx-btn-red btn-block"><i
                                        class="fas fa-door-open"></i>
                                    @lang('eventmie-pro::em.activates')</button>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="font-weight-bold">
                                            <a class="btn btn-red pull-left" style="font-weight:bold; color:black;"
                                                href="{{ route('eventmie.password.request') }}">@lang('eventmie-pro::em.forgot_password')</a>
                                            <a class="btn btn-red pull-right" style="font-weight:bold; color:black;"
                                                href="{{ route('eventmie.register_show') }}">@lang('eventmie-pro::em.register')</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@endsection