
@extends('eventmie::auth.authapp')
@section('title')
@lang('eventmie-pro::em.em.register')
@endsection
@section('authcontent')
<div
    style="display: none; font-size: 1px; color: #FEFEFE; line-height: 1px; font-family: 'Lato', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
    We're thrilled to have you here! Get ready to dive into your new account. </div>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-left:22%;">
    <!-- LOGO -->
    <tr>
        <td bgcolor="#F4F4F4" align="center" style="padding: 0px 10px 0px 10px;">
             <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td bgcolor="ffffff" align="center" valign="top"
                        style="padding: px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400;  line-height: 48px;">
                       
                       
                        <p style="font-weight:400; font-size: 15px; margin-top:;"><b>Already a member ds?</b>
                            <a  style="font-weight:400; font-size: 20px; font-weight:bold; color:black;" href="{{ route('eventmie.login') }}">Log in</a>
                        </p>
                        <h6 style="font-weight:400; font-size: 30px; margin-top:;">Register</h6>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#F4F4F4" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td bgcolor="#FFFFFF" align="left"
                        style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <div class="lgx-registration-form">
                        <form method="POST" action="{{ route('eventmie.register') }}" @submit.prevent="submit"
                                v-if="!savingSuccessful">
                                @if(session()->get('message'))
                                @if(session()->get('msg'))
                                @if(session()->get('success'))
                                <div class="alert alert-success col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <b> {{ session()->get('message') }}
                                        {{ session()->get('msg') }}</b>
                                    {{ session()->get('success') }}
                                </div>
                                @endif
                                @endif
                                @endif
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <h4 style="margin-top:-1em;">Name</h4>
                                <input id="name" type="text" style="color:black";
                                    class="wpcf7-form-control form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    name="name" value="{{ old('name') }}" required autofocus
                                    placeholder="@lang('eventmie-pro::em.name')">
                                @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif

                                <h4 style="margin-top:-1em;">Email</h4>
                                <input id="email" type="email" style="color:black";
                                    class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    name="email" value="{{ old('email') }}" required
                                    placeholder="@lang('eventmie-pro::em.email')">
                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif

                                <button type="submit" class="lgx-btn lgx-btn-red btn-block"><i
                                        class="fas fa-door-open"></i> @lang('eventmie-pro::em.register')</button>

                                <hr style="border-top: 2px solid #eee;">


                                <div class="row" style="margin-top:-1em;">
                                    <div>
                                        <h4 class="col-black">@lang('eventmie-pro::em.register_with_google')</h4>
                                    </div>
                                </div>
                                <a href="{{ route('eventmie.oauth_login', ['social' => 'google'])}}"
                                    class="lgx-btn lgx-btn-red btn-block"><i class="fab fa-google"></i>
                                    Google</a>
                            </form>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
@section('javascript')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
    integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
</script>
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    console.log('data yanha script me hai dekho');
    localStorage.removeItem("logout");
    localStorage.clear();
});
</script>
@stop