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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <h4 style="margin-top:-30px;color:white;">Login</h4>
  <div style="margin-top:-10px;" class="lgx-registration-form">
                        <form method="POST" action="{{ route('eventmie.login_post') }}">
                       

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input id="email" type="email"
                                class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                name="email" value="{{ old('email') }}" required autofocus
                                placeholder="@lang('eventmie-pro::em.email')">
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif

                            <input id="password" type="password"
                                class="wpcf7-form-control form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                name="password" required placeholder="@lang('eventmie-pro::em.password')">
                            @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif



                            <button type="submit" class="lgx-btn lgx-btn-white btn-block"><i
                                    class="fas fa-sign-in-alt"></i> @lang('eventmie-pro::em.login')</button>



                            <hr style="margin-top:-20px; solid #eee;">



                        </form>
                    </div>
  <h4 style="color:white;">Continue with Phone</h4>
<div class="alert alert-info" id="sentSuccess" style="display: none;">
<button type="button" class="close" data-dismiss="alert">×</button>
</div>
<div class="alert alert-success" id="showmymodales" style="display: none;"></div>
<div class="alert alert-info" id="showmodals" style="display: none;">
<button type="button" class="close" data-dismiss="alert">×</button>
<h3 style="color:black" >Add verification code</h3>
<div class="alert alert-success" id="successOtpAuth" style="display: none;"></div>
<form>
 <input style="background-color: #52595D" type="text" id="verificationCode" class="form-control"
    placeholder="Enter verification code">
    <br>
    <button type="button" class="lgx-btn lgx-btn-white btn-block" onclick="codeverify();"><i class="fas fa-sign-in-alt"></i> @lang('eventmie-pro::em.verifycode')</button>
</form>
</div>
<div  style="display: none;" id='shows' class="alert alert-success col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12">
     <button type="button" class="close" data-dismiss="alert">×</button>
    <b>@lang('eventmie-pro::em.otpsends')
        </b>
    </div>
    <div  style="display: none;" id='showscode' class="alert alert-success col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12">
     <button type="button" class="close" data-dismiss="alert">×</button>
    <b>@lang('eventmie-pro::em.codeverification')
        </b>
    </div>
@if(config('voyager.demo_mode'))
<div class="alert alert-info">
    <a href="https://eventmie-pro-docs.classiebit.com/docs/1.4/demo-accounts" target="_blank">Visit here for Demo Accounts</a>    
</div>
@endif
<input type="hidden" value="{{Session::put('link', url()->previous())}}">
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <input type="text" id="number" name="mobile_no" class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="@lang('eventmie-pro::em.enterphone')">
        <div id="recaptcha-container"></div>
        <button type="button" class="lgx-btn lgx-btn-white btn-block" onclick="phoneSendAuth();"><i class="fas fa-sign-in-alt"></i> @lang('eventmie-pro::em.sendcode')</button>
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" checked value="1">
            <label class="form-check-label" for="remember">@lang('eventmie-pro::em.remember')</label>
        </div>
        <hr style="margin-top: -10px solid #eee;">
        <div style="margin-top:-30px" class="row">
            <div >
                <h4 class="col-white">@lang('eventmie-pro::em.continue_with')</h4>
            </div>
        </div>
         <!-- <a href="{{ route('eventmie.oauth_login', ['social' => 'facebook'])}}" class="lgx-btn lgx-btn-white lgx-btn-sm"><i class="fab fa-facebook-f"></i> Facebook</a> -->
         <a    href="{{ route('eventmie.oauth_login', ['social' => 'google'])}}" class="lgx-btn lgx-btn-white btn-block"><i class="fab fa-google"></i> Google</a>
        <div class="row">
            <div class="col-md-12">
                <div class="lgx-links">
                    <a class="btn btn-link pull-left" href="{{ route('eventmie.password.request') }}">@lang('eventmie-pro::em.forgot_password')</a>
                    <a class="btn btn-link pull-right"  href="{{ route('eventmie.register_show') }}">@lang('eventmie-pro::em.register')</a>
                   
                </div>
            </div>
        </div>
    </form>
</div>    
@endsection
@section('javascript')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
        <script type="text/javascript">
     $(document).ready(function(){
   console.log('data yanha script me hai dekho');
   localStorage.removeItem("logout");
   localStorage.clear();
        });
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
            var number = $("#number").val();
            firebase.auth().signInWithPhoneNumber('+91'+number, window.recaptchaVerifier).then(function(confirmationResult) {
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
               setTimeout(function() { $("#error").hide(); }, 5000);
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