@extends('eventmie::layouts.app')

@section('title', $event->title)
@section('meta_title', $event->meta_title)
@section('meta_keywords', $event->meta_keywords)
@section('meta_description', $event->meta_description)
@section('meta_image', '/storage/'.$event['thumbnail'])
@section('meta_url', url()->current())

    
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/1.0.0/flickity.pkgd.js"></script>
  {!! NoCaptcha::renderJs() !!}
<!--BANNER-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<!--BANNER-->
<section>
    <div class="lgx-banner event-poster background-tint" style="background-image: url({{ '/events/storage/'.$event['poster'] }});">
        <div class="lgx-banner-style">
            <div class="lgx-inner lgx-inner-fixed">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="lgx-banner-info-area">
                                <div class="lgx-banner-info text-center">
                                    <h2 class="title">{{$event->title}}</h2>
                                    <h3 class="location"><i class="fas fa-map-marked-alt"></i> {{$event->venue}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="lgx-banner-info-area">
                                <div class="lgx-banner-info text-center">
                                    <div class="message">
                                        {{-- messsage pop up open --}}
                                        @if(session()->get('success'))
                                        <div class="alert alert-success col-xl-10 col-10 col-sm-10 col-lg-10 col-md-10"
                                            style="padding:20px;">
                                            <button type="button" class="close" data-dismiss="alert">??</button>
                                            <center> {{ session()->get('success') }}</center>
                                        </div>
                                        @endif
                                        <div class="col-xl-10 col-12 col-sm-10 col-lg-10 col-md-10">
                                            @if(session()->get('danger'))
                                            <div class="alert alert-danger">
                                                <button type="button-danger" class="close"
                                                    data-dismiss="alert">??</button>
                                                {{ session()->get('danger') }}
                                            </div>
                                            @endif
                                        </div>
                                        @if(count($errors) > 0)
                                        <div class="row">
                                            <div class="col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12 error">
                                                <ul>
                                                    @foreach($errors->all() as $error)
                                                    <div class="alert alert-danger">
                                                        <button type="button" class="close"
                                                            data-dismiss="alert">??</button>
                                                        <p class="text-center"> {{$error}}</p>
                                                    </div>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="lgx-banner-info-area">
                                    <div class="lgx-banner-info text-center">
                                        <button type="button" data-toggle="modal" data-target="#myModal"
                                            class="lgx-btn lgx-btn-red btn btn-block">
                                            <i class="fas fa-ticket-alt"></i> Confirm|RVSP|I Would attend</button>
                                    </div>
                                    <div class="modal fade" id="myModal" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content-->
                                            <div class="modal-content" style="width:105%;">
                                                <div class="row  ml-1">
                                                    <div class="col-md-6" style="border-right:1px solid black">
                                                        <div class="modal-header">
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">New User Sign Up</h4>
                                                        </div>
                                                        <form class="form-horizontal"
                                                            action="{{ route('eventmie.rvsp')}}" method="post">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label style="color:black">Email</label>
                                                                    <input type="email" name="emailid"
                                                                        class="form-control" id="emailid" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label style="color:black">Name</label>
                                                                    <input type="text" name="Name" class="form-control"
                                                                        id="Name" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label style="color:black">Phone No</label>
                                                                    <input type="mobile" name="Phoneno"
                                                                        value="{{ old('Phoneno') }}"
                                                                        pattern="[123456789][0-9]{9}"
                                                                        title="Phone number with 6-9 and remaing 9 digit with 0-9"
                                                                        class="form-control" id="Phoneno" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label style="color:black">Address</label>
                                                                    <input type="text" name="Address"
                                                                        class="form-control" id="Address" required>
                                                                    <input type="hidden" style="color:black"
                                                                        name="TripTitle" id="TripTitle"
                                                                        value="{{$event->id}}" />
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}"
                                                                    required>
                                                                    <div class="col-md-3" style="margin-left:-9%; "
                                                                        required>{!! app('captcha')->display() !!}
                                                                        @if ($errors->has('g-recaptcha-response'))<span
                                                                            class="help-block">
                                                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong></span>
                                                                        @endif
                                                                    </div>
                                                                </div>


                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-secondary">Submit</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-6" style="border-right:1px solid black">
                                                        <div class="modal-header">
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">If you are already user Login</h4>
                                                        </div>
                                                        <form class="form-horizontal"
                                                            action="{{ route('eventmie.rvspstore')}}" method="post">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label style="color:black">Email</label>
                                                                    <input type="email" name="useremail"
                                                                        class="form-control" id="useremail" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label style="color:black">Password</label>
                                                                    <input type="password" name="userpassword"
                                                                        class="form-control" id="userpassword" required>
                                                                    <input type="hidden" style="color:black"
                                                                        name="tourtitle" id="tourtitle"
                                                                        value="{{$event->id}}" />
                                                                </div>
                                                                <div class=" form-group row {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}"
                                                                    required>
                                                                    <div class="col-md-3" style="margin-left:-10%;"
                                                                        required>{!! app('captcha')->display() !!}
                                                                        @if ($errors->has('g-recaptcha-response'))
                                                                        <span class="help-block">
                                                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-secondary">Submit</button>
                                                                    <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- end Modal content-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--//.ROW-->
                            </div>
                            <!-- //.CONTAINER -->
                        </div>
                        <!-- //.INNER -->
                    </div>
                    <!--//.ROW-->
                    <!--//.ROW-->
                </div>
                <!-- //.CONTAINER -->
            </div>
            <!-- //.INNER -->
        </div>
    </div>
</section>
<!--BANNER END-->

<!--ABOUT-->
<section>
    <div id="lgx-about" class="lgx-about">
        <div class="lgx-inner reduce-gap-mobile">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-10 offset-1">
                        <div class="lgx-about-content-area">
                            <div class="lgx-heading" style="margin-top:-10%;">
                                <h2 class="heading text-center visible-lg visible-md">{{ $event['title'] }}</h2>
                                <h3 class="subheading text-center">
                                    @if(!empty($event['online_location']))
                                        <span class="lgx-badge lgx-badge-online"><i class="fas fa-signal"></i>&nbsp; @lang('eventmie-pro::em.online_event')</span>
                                    @endif
                                    
                                    <span class="lgx-badge lgx-badge-info">{{ $category['name'] }}</span>

                                    @if(!empty($free_tickets))
                                        <span class="lgx-badge lgx-badge-success">@lang('eventmie-pro::em.free_tickets')</span>
                                    @endif

                                    @if($event->repetitive)
                                        @if($event->repetitive_type == 1)
                                            <span class="lgx-badge lgx-badge-primary">
                                                @lang('eventmie-pro::em.repetitive_daily_event')
                                            </span>
                                        @elseif($event->repetitive_type == 2)    
                                            <span class="lgx-badge lgx-badge-primary">
                                                @lang('eventmie-pro::em.repetitive_weekly_event')
                                            </span>
                                        @elseif($event->repetitive_type == 3)    
                                            <span class="lgx-badge lgx-badge-primary">
                                                @lang('eventmie-pro::em.repetitive_monthly_event')
                                            </span>
                                        @endif    
                                        
                                    @endif
                                    
                                    @if($ended)   
                                        <span class="lgx-badge lgx-badge-danger">@lang('eventmie-pro::em.event_ended')</span>
                                    @endif
                                </h3>

                                <h3 class="subheading mt-5 text-center">
                                    <span><strong>@lang('eventmie-pro::em.share_event') &nbsp;</strong></span>

                                    <span><a class="btn btn-sm" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"><i class="fab fa-facebook-square fa-2x"></i></a></span>
                                    <span><a class="btn btn-sm" target="_blank" href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ url()->current() }}"><i class="fab fa-twitter fa-2x"></i></a></span>
                                    <span><a class="btn btn-sm" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ urlencode($event->title) }}"><i class="fab fa-linkedin fa-2x"></i></a></span>
                                    <span><a class="btn btn-sm" target="_blank" href="https://wa.me/?text={{ url()->current() }}"><i class="fab fa-whatsapp fa-2x"></i></a></span>
                                    <span><a class="btn btn-sm" target="_blank" href="https://www.reddit.com/submit?title={{ urlencode($event->title) }}&url={{ url()->current() }}"><i class="fab fa-reddit fa-2x"></i></a></span>
                                </h3>
                            </div>
                            <div class="lgx-about-content">{!! $event['description'] !!}</div>
                        </div>
                    </div>

                </div>
                <br><br>
                <div class="row" style="margin-top:-1%;">
                    <div class="col-12 col-sm-5 col-md-5 offset-md-1">
                        <div class="lgx-about-service">
                            <div class="lgx-single-service lgx-single-service-color">
                                <div class="text-area">
                                    <span class="icon"><i class="fas fa-map-marked-alt" aria-hidden="true"></i></span>
                                    <h2 class="title">@lang('eventmie-pro::em.where')</h2>
                                    <p>
                                        @if(!empty($event['online_location']))
                                            <strong>@lang('eventmie-pro::em.online_event')</strong> <br>
                                        @endif

                                        <strong>{{$event->venue}}</strong> <br>
                                        
                                        @if($event->address)
                                        {{$event->address}} {{ $event->zipcode }} <br>
                                        @endif
                                        
                                        @if($event->city)
                                        {{ $event->city }}, 
                                        @endif
                                        
                                        @if($event->state)
                                        {{ $event->state }}, 
                                        @endif
                                        
                                        @if($country)
                                            {{ $country->get('country_name') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-5 col-md-5">
                        <div class="lgx-about-service">
                             <div class="lgx-single-service lgx-single-service-color">
                                 <div class="text-area">
                                    <span class="icon"><i class="fas fa-stopwatch" aria-hidden="true"></i></span>
                                    <h2 class="title">@lang('eventmie-pro::em.when')</h2>
                                    
                                    @if(!$event->repetitive)
                                    <p>
                                        {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat(format_carbon_date(true)) }}, 
                                        {{ \Carbon\Carbon::parse($event->start_date.' '.$event->start_time)->translatedFormat('h:i A') }}

                                        <br>@lang('eventmie-pro::em.till')<br>

                                        {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat(format_carbon_date(true)) }}, 
                                        {{ \Carbon\Carbon::parse($event->end_date.' '.$event->end_time)->translatedFormat('h:i A') }}
                                    </p>
                                    @else
                                    <p>
                                        {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat(format_carbon_date(true)) }}
                                        
                                        <br>@lang('eventmie-pro::em.till')<br>
                                        
                                        {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat(format_carbon_date(true)) }}
                                    </p>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<!--ABOUT END-->

<!--SCHEDULE-->
<section>
    <div id="lgx-schedule" class="lgx-schedule" style="margin-top:-9%;">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-registration-area-simple">
                            <div class="lgx-heading lgx-heading-white" style="margin-top:-10%;">
                                <h2 class="heading">@lang('eventmie-pro::em.get_tickets')</h2>
                                <h3 class="subheading">@lang('eventmie-pro::em.select_schedule')</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-bottom:-10%;"> 
                    <select-dates 
                        :event="{{ json_encode($event, JSON_HEX_APOS) }}" 
                        :max_ticket_qty="{{ json_encode($max_ticket_qty, JSON_HEX_APOS) }}"
                        :login_user_id="{{ json_encode(\Auth::id(), JSON_HEX_APOS) }}"
                        :is_customer="{{ Auth::id() ? (Auth::user()->hasRole('customer') ? 1 : 0) : 1 }}"
                        :is_organiser="{{ Auth::id() ? (Auth::user()->hasRole('organiser') ? 1 : 0) : 0 }}"
                        :is_admin="{{ Auth::id() ? (Auth::user()->hasRole('admin') ? 1 : 0) : 0 }}"
                        :is_paypal="{{ $is_paypal }}"
                        :is_offline_payment_organizer="{{ setting('booking.offline_payment_organizer') ? 1 : 0 }}"
                        :is_offline_payment_customer="{{ setting('booking.offline_payment_customer') ? 1 : 0}}"
                        :tickets="{{ json_encode($tickets, JSON_HEX_APOS) }}"
                        :booked_tickets="{{ json_encode($booked_tickets, JSON_HEX_APOS) }}"
                        :currency="{{ json_encode($currency, JSON_HEX_APOS) }}"
                        :total_capacity="{{ $total_capacity }}"
                    >
                    </select-dates>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
        <!-- //.INNER -->
    </div>
</section>
<!--SCHEDULE END-->

<!--Event FAQ-->
@if($event['faq'])
<section>
    <div id="lgx-about" class="lgx-about">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12" style="margin-top:-10%;">
                        <div class="lgx-heading">
                            <h2 class="heading">@lang('eventmie-pro::em.event_info')</h2>
                        </div>
                    </div>
                    <!--//main COL-->
                </div>
                <div class="row">
                    <div class="col-xs-12" style="margin-top:-5%;">
                        <div class="lgx-about-content-area text-center">
                            <div class="lgx-about-content">{!! $event['faq'] !!}</div>
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
    </div>
</section>
@endif
<!--Event FAQ END-->

<!--TAGS-->
@php $i = 0; @endphp
@foreach($tag_groups as $key => $group)
@php $i++; @endphp
<section>
    <div id="lgx-schedule" class="{{ ($i%2) ? 'lgx-schedule lgx-schedule-dark' : '' }}">
        <div class="lgx-inner" style="{{ ($i%2) ? 'background-image: url('.eventmie_asset('img/bg-pattern.png').');' : '' }}">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading {{ ($i%2) ? 'lgx-heading-white' : '' }}">
                            <h2 class="heading"> {{ ucfirst($key) }}</h2>
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
                <div class="row justify-content-center">
                @foreach($group as $key1 => $value)
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="lgx-single-speaker">
                                
                                @if($value['is_page'] > 0)
                                <a href="{{ route('eventmie.events_tags',[$event->slug, str_replace(' ', '-', $value['title'])] ) }}">
                                @elseif($value['website'])
                                <a href="{{ $value['website'] }}" target="_blank">
                                @endif

                                    @if($value['image'])
                                    <img src="/storage/{{ $value['image'] }}" alt="{{ $value['title'] }}"/>
                                    @else
                                    <img src="{{ eventmie_asset('img/512x512.jpg') }}" alt="{{ $value['title'] }}"/>
                                    @endif

                                @if($value['is_page'] > 0 || $value['website'])
                                </a>
                                @endif
                            <figure>    
                                <figcaption>
                                    @if($value['is_page'] > 0)
                                    <div class="social-group">
                                        <a class="sp-tw" href="{{ $value['twitter'] }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                        <a class="sp-fb" href="{{ $value['facebook'] }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                        <a class="sp-insta" href="{{ $value['instagram'] }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                        <a class="sp-in" href="{{ $value['linkedin'] }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                                        <a class="sp-in" href="{{ $value['website'] }}" target="_blank"><i class="fas fa-globe"></i></a>
                                    </div>
                                    @endif

                                    <div class="speaker-info">
                                        <h3 class="title">
                                            @if($value['is_page'] > 0)
                                                <a href="{{ route('eventmie.events_tags',[$event->slug, str_replace(' ', '-', $value['title'])] ) }}">{{$value['title']}}</a>
                                            @elseif($value['website'])
                                                <a href="{{ $value['website'] }}" target="_blank">{{$value['title']}}</a>
                                            @else
                                                {{$value['title']}}
                                            @endif
                                        </h3>

                                        @if($value['sub_title'])
                                        <h4 class="subtitle">{{$value['sub_title']}}</h4>
                                        @endif
                                    </div>

                                </figcaption>

                            </figure>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            <!-- //.CONTAINER -->
        </div>
        <!-- //.INNER -->
    </div>
</section>
@endforeach
<!--Tags END-->


<!--PHOTO GALLERY-->
@if(!empty($event->images))
<section>
    <div id="lgx-photo-gallery" class="lgx-gallery-popup lgx-photo-gallery-normal lgx-photo-gallery-black" style="margin-top:-8%;"> 
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12" style="margin-top:-10%;">
                        <div class="lgx-heading lgx-heading-white">
                            <h2 class="heading">@lang('eventmie-pro::em.event_gallery')</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12" style="margin-bottom:-9%;">
                        <gallery-images :gimages="{{ $event->images }}" ></gallery-images>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--PHOTO GALLERY END-->

<!--Event Video-->
@if(!empty($event->video_link))
<section>
    <div id="lgx-travelinfo" class="lgx-travelinfo" style="margin-top:-9%;">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h2 class="heading">@lang('eventmie-pro::em.watch_trailer')</h2>
                        </div>
                    </div>
                    <!--//main COL-->
                </div>
                <div class="row">
                    <div class="col-md-offset-1 col-md-10" style="margin-top:-3%;">
                        <iframe src="{{ $event->video }}" allowfullscreen style="width: 100%; height: 500px; border-radius: 16px; border: none;"></iframe>
                    </div>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
    </div>
</section>
@endif
<!--Event Video END-->


<!--GOOGLE MAP-->
@if($event->latitude && $event->longitude)
<div class="innerpage-section g-map-wrapper" style="margin-top:-8%; margin-bottom:-6%;">
    <div class="lgxmapcanvas map-canvas-default"> 
        
        <g-component :lat="{{ json_encode($event->latitude, JSON_HEX_APOS) }}" :lng="{{ json_encode($event->longitude, JSON_HEX_APOS) }}" >
        </g-component>

    </div>
</div>
@endif
<!--GOOGLE MAP END-->

@endsection

@section('javascript')
<script type="text/javascript" src="{{ eventmie_asset('js/events_show_v1.6.js') }}"></script>
<script type="text/javascript">
    var google_map_key = {!! json_encode( $google_map_key) !!};
    
</script>
@stop
