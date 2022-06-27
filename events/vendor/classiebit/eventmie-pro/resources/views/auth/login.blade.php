
@extends('eventmie::auth.authapp')

@section('title')
@lang('eventmie-pro::em.login')
@endsection

@section('authcontent')
<style>
.-box-sd-effect1 {
    box-shadow: 0 4px 8px rgba(60, 61, 61, 0.2);
    background-color: #007BFF;
    color: #fff;
    padding: 2% 3%;
    font-size: 0.9em;
    margin: 1% 0%;
    width: 265px;
}

.-box-sd-effect2 {
    box-shadow: 0 4px 8px rgba(60, 61, 61, 0.2);
    background-color: #28A745;
    color: #fff;
    padding: 2% 3%;
    font-size: 0.9em;
    margin: 1% 0%;
    width: 265px;
}

.-box-sd-effect i {
    color: #fff;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    <div class="container lgx-schedule lgx-schedule-light" style="width:700px;">
    <div class="row">
        <div class="col-md-12 " align="center">
        
            <p style="font-weight:400; font-size:15px; margin-top:;"><b>Not a member yet?</b><a
                    style="font-weight:400; font-size: 20px; font-weight:bold; color:black;"
                    href="{{ route('eventmie.register') }}"> Sign up</a> </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mt-2">
            <h4 class="col-black">@lang('eventmie-pro::em.login_with_google')</h4>
            <a href="{{ route('eventmie.oauth_login', ['social' => 'google'])}}"
                class="lgx-btn lgx-btn-red btn-block"><i class="fab fa-google"></i> Google </a>

            <h4 style="color:black; margin-top:-0.1em;">Or Login with Phone <i class="fa fa-mobile fa-2x"
                    aria-hidden="true"></i></h4>
            <div class="alert alert-info" id="sentSuccess" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">×</button>
            </div>
            <div class="alert alert-success" id="showmymodales" style="display: none;"></div>
            <div class="alert alert-info" id="showmodals" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h3 style="color:black">Add verification code</h3>
                <div class="alert alert-success" id="successOtpAuth" style="display: none;"></div>
                <form>
                    <input style="background-color: #52595D" type="text" id="verificationCode" class="form-control"
                        placeholder="Enter verification code">
                    <br>
                    <button type="button" class="lgx-btn lgx-btn-white btn-block" onclick="codeverify();"><i
                            class="fas fa-sign-in-alt"></i> @lang('eventmie-pro::em.verifycode')</button>
                </form>
            </div>
            <div style="display: none;" id='shows'
                class="alert alert-success col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <b>@lang('eventmie-pro::em.otpsends')
                </b>
            </div>
            <div style="display: none;" id='showscode'
                class="alert alert-success col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <b>@lang('eventmie-pro::em.codeverification')
                </b>
            </div>
            @if(config('voyager.demo_mode'))
            <div class="alert alert-info">
                <a href="https://eventmie-pro-docs.classiebit.com/docs/1.4/demo-accounts" target="_blank">Visit here for
                    Demo
                    Accounts</a>
            </div>
            @endif
            <input type="hidden" value="{{Session::put('link', url()->previous())}}">

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"> Enter Verification code</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="alert alert-success" id="successRegsiter" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
            <div class="lgx-registration-form">
                <form class="form-horizontal mt-1" method="POST" action="{{ route('eventmie.login_post') }}">
                    {{ csrf_field() }}
                    <input type="text" id="number" name="mobile_no" style="color:black"
                        class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                        name="email" value="{{ old('email') }}" required autofocus
                        placeholder="@lang('eventmie-pro::em.enterphone')">
                    <div id="recaptcha-container"></div>
                    <button type="button" class="lgx-btn lgx-btn-red btn-block mt-3" onclick="phoneSendAuth();"><i
                            class="fas fa-sign-in-alt"></i>
                        @lang('eventmie-pro::em.sendcode')</button>
                    <div class="form-check text-left ">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked value="1">
                        <label class="form-check-label" style="color:black;"
                            for="remember">@lang('eventmie-pro::em.remember')</label>
                    </div>

                </form>
            </div>
        </div>

        <div class="col-md-6 mt-1" align="left" style="border-left:1px solid black;">
            <div class="lgx-registration-form">
                <h4>Log in</h4>
                <hr style=" color:black;">
                <form method="POST" action="{{ route('eventmie.login_post') }}">
                    <input type="hidden" id="user" name="user" value="publisher">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <h4 style="margin-top:2em;">Email</h4>
                    <input id="email" type="email" style="color:black" ;
                        class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                        name="email" value="{{ old('email') }}" required autofocus
                        placeholder="@lang('eventmie-pro::em.email')">
                    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif

                    <h4>Password</h4>
                    <input id="password" type="password" style="color:black" ;
                        class="wpcf7-form-control form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                        name="password" required placeholder="@lang('eventmie-pro::em.password')">
                    @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                    <button type="submit" class="lgx-btn lgx-btn-red btn-block"><i class="fas fa-sign-in-alt"></i>
                        @lang('eventmie-pro::em.login')</button>
                    <hr style="margin-top:-20px; solid #eee;">
                </form>
            </div>
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
        </div>
    </div>
</div>


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

</script>
<script>
$("#done").hide();
var firebaseConfig = {
    apiKey: "AIzaSyC-SKnw9Ax5KrfbV7xczR0teFG2AY_HuAQ",
    authDomain: "holiday-da63d.firebaseapp.com",
    projectId: "holiday-da63d",
    storageBucket: "holiday-da63d.appspot.com",
    messagingSenderId: "437553932771",
    appId: "1:437553932771:web:3bfbc02b6ce6cc1d33d8d6",
    measurementId: "G-0QYZGP2LKH"
};
firebase.initializeApp(firebaseConfig);
</script>
<script type="text/javascript">
window.onload = function() {
    render();
};

function render() {
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
    recaptchaVerifier.render();
}

function phoneSendAuth() {
    $("#recaptcha-container").hide();
    $("#number").hide();
    var number = $("#number").val();
    firebase.auth().signInWithPhoneNumber('+91' + number, window.recaptchaVerifier).then(function(confirmationResult) {
        window.confirmationResult = confirmationResult;
        coderesult = confirmationResult;
        console.log(coderesult);
        $("#sentSuccess").text("OTP Sent in your mobile Successfully.");
        $("#sentSuccess").show();
        $("#showmodals").show();
        $('#exampleModal').modal('show')
    }).catch(function(error) {
        $("#showmymodales").text("Try after 4 hour or use any other mobile.");
        $("#showmymodales").show();
        setTimeout(function() {
            $("#error").hide();
        }, 5000);
    });
}

function codeverify() {
    var code = $("#verificationCode").val();
    coderesult.confirm(code).then(function(result) {
        let user = result.user;
        console.log(user);
        $('#verificationCode').val(localStorage.verificationCode);
        console.log('yanha aata hain');
        $("#successRegsiter").text("OTP  Verified Successfully, Redirecting...");
        $("#successRegsiter").show();
        $('form.form-horizontal').submit();
    }).catch(function(error) {
        $("#error").text(error.message);
        $("#error").show();
    });
}
</script>
@stop