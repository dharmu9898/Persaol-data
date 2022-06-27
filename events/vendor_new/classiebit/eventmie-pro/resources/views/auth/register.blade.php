@extends('eventmie::auth.authapp')

@section('title')
    @lang('eventmie-pro::em.register')
@endsection

@section('authcontent')

<h2 class="title">@lang('eventmie-pro::em.register')</h2>
<div class="lgx-registration-form">

    <form method="POST" action="{{ route('eventmie.register') }}"  @submit.prevent="submit" v-if="!savingSuccessful">
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
        <input id="name" type="text" class="wpcf7-form-control form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus placeholder="@lang('eventmie-pro::em.name')">
        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif

        <input id="email" type="email" class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="@lang('eventmie-pro::em.email')">
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

       

       

        <button type="submit" class="lgx-btn lgx-btn-white btn-block"><i class="fas fa-door-open"></i> @lang('eventmie-pro::em.register')</button>

        
        
        <hr style="border-top: 2px solid #eee;">
        <div class="row">
            <div class="col-md-4 text-left">
                <h4 class="col-white">@lang('eventmie-pro::em.continue_with')</h4>
            </div>
            <div class="col-md-8 text-right">
            <!-- <a href="{{ route('eventmie.oauth_login', ['social' => 'facebook'])}}" class="lgx-btn lgx-btn-white lgx-btn-sm"><i class="fab fa-facebook-f"></i> Facebook</a> -->
                <a href="{{ route('eventmie.oauth_login', ['social' => 'google'])}}" class="lgx-btn lgx-btn-white lgx-btn-sm"><i class="fab fa-google"></i> Google</a>
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
       
@stop