@extends('eventmie::layouts.app')

@section('title') @lang('eventmie-pro::em.home') @endsection

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

<!--Banner slider start-->
<section>
    <div class="lgx-slider welcome-slider">
        <div class="lgx-banner-style">
            <div class="lgx-inner">
                <div id="lgx-main-slider" class="owl-carousel lgx-slider-navbottom">
                    <!--Vue slider-->
                   
                </div>
            </div>
        </div>
    </div>
</section>
<!--Banner slider end-->

<!--Event Search start-->
<section class="main-search-container">
    <div >
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-12" style="margin-top:-2%;">
                        <div class="main-search">
                            <form class="form-inline" type="GET" action="{{route('eventmie.events_index')}}">
                                <div class="form-group input-group event-search">
                                    <span class="input-group-addon"><i class="fas fa-calendar-day"></i></span>
                                    <input type="text" class="form-control" name="search" placeholder="@lang('eventmie-pro::em.search_event_by')">
                                </div>
                                <button type="submit" class="lgx-btn lgx-btn-black"><i class="fas fa-search"></i> @lang('eventmie-pro::em.search_event')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Event Search end-->





<!--Event Featured Start-->
@if(!empty($featured_events))
<section>
    <div >
        <div class="lgx-inner">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div  style="margin-top: -10%;"class="lgx-heading">
                            <h2 class="heading"><i class="fas fa-star"></i> @lang('eventmie-pro::em.featured_events')</h2>
                        </div>
                    </div>
                </div>

                <div  style="margin-top: -5%;" class="row">
                    <div class="offset-1 col-10 col-lg-offset-1 col-lg-10">
                        <event-listing :events="{{ json_encode($featured_events, JSON_HEX_APOS) }}"
                                        :currency="{{ json_encode($currency, JSON_HEX_APOS) }}"
                        >
                        </event-listing>
                    </div>
                </div>

                <div class="section-btn-area">
                    <a class="lgx-btn lgx-btn-red" href="{{ route('eventmie.events_index') }}"><i class="fas fa-calendar-day"></i> @lang('eventmie-pro::em.view_all_events')</a>
                </div>

            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif
<!--Event Featured END-->

<!--Event Top-selling Start-->
@if(!empty($top_selling_events))
<section>
    <div style="margin-top:7%;" id="lgx-schedule" class="lgx-schedule lgx-schedule-dark">
        <div style="margin-top:-8%;margin-bottom:-8%;" class="lgx-inner" style="background-image: url({{ asset('img/bg-pattern.png') }});">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div  style="margin-top:-8%;" class="lgx-heading lgx-heading-white">
                            <h2 class="heading"><i class="fas fa-bolt"></i> @lang('eventmie-pro::em.top_selling_events')</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="offset-1 col-10 col-lg-offset-1 col-lg-10">
                        <event-listing :events="{{ json_encode($top_selling_events, JSON_HEX_APOS) }}"
                            :currency="{{ json_encode($currency, JSON_HEX_APOS) }}" 
                        >
                        </event-listing>
                    </div>
                </div>

                <div style="margin-bottom:-8%" class="section-btn-area">
                    <a class="lgx-btn lgx-btn-red" href="{{ route('eventmie.events_index') }}"><i class="fas fa-calendar-day"></i> @lang('eventmie-pro::em.view_all_events')</a>
                </div>

            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif
<!--Event Top-selling END-->

<!--Event Upcoming Start-->
@if(!empty($upcomming_events))
<section>
    <div>
        <div class="lgx-inner">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="lgx-heading" >
                            <h2 class="heading"><i class="fas fa-hourglass-half"></i> @lang('eventmie-pro::em.upcoming_events')</h2>
                        </div>
                    </div>
                </div>
                
                <div  class="row">
                    <div class="offset-1 col-10 col-lg-offset-1 col-lg-10">
                        <event-listing 
                            :events="{{ json_encode($upcomming_events, JSON_HEX_APOS) }}" 
                            :currency="{{ json_encode($currency, JSON_HEX_APOS) }}"
                        >
                        </event-listing>
                    </div>
                </div>

                <div class="section-btn-area">
                    <a class="lgx-btn lgx-btn-red" href="{{ route('eventmie.events_index') }}"><i class="fas fa-calendar-day"></i> @lang('eventmie-pro::em.view_all_events')</a>
                </div>
                
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif
<!--Event Upcoming END-->


<!--Categories-->
@if(!empty($categories))
<section>
    <div id="lgx-schedule" class="lgx-schedule lgx-schedule-dark" style="margin-top:-2%;">
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
            <div class="container">
                <div class="row" style="margin-top:-10%;">
                    <div class="col-12">
                        <div class="lgx-heading lgx-heading-white">
                            <h2 class="heading">@lang('eventmie-pro::em.event_categories')</h2>
                        </div>
                    </div>
                </div>
                <!--//main row-->
                <div class="row" style="margin-bottom:-10%;">
                    <div class="col-12">
                        <div class="sponsors-area sponsors-area-border sponsors-area-col3">
                            @foreach($categories as $key => $item)
                            <div class="single">
                                <a href="{{route('eventmie.events_index', ['category' => urlencode($item['name'])])}}">
                                    <img src="/events/storage/{{ $item['thumb'] }}" alt="{{ $item['slug'] }}"/>
                                    <span class="single-name">{{ $item['name'] }}</span>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!--//col-->
                </div>
            </div>
            <!--//container-->
        </div>
    </div>
</section>
@endif   
<!--Categories END-->

<!--cities_events-->
@if(!empty($cities_events))
<section>
    <div id="lgx-schedule" class="lgx-schedule lgx-schedule-light">
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
            <div class="container">
                <div class="row" style="margin-top:-10%;">
                    <div class="col-12">
                        <div class="lgx-heading">
                            <h2 class="heading">@lang('eventmie-pro::em.cities_events')</h2>
                        </div>
                    </div>
                </div>
                <!--//main row-->
                <div class="row" style="margin-top:-2%; margin-bottom:-10%;">
                    <div class="col-12">
                        <div class="sponsors-area sponsors-area-border sponsors-area-col3">
                            @foreach($cities_events as $key => $item)
                            <div class="single">
                                <a href="{{route('eventmie.events_index', ['search' => urlencode($item->city)])}}">
                                    <img src="/events/storage/{{ $item->poster }}" alt="{{ $item->city }}"/>
                                    <span class="single-name">{{ $item->city }}</span>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!--//col-->
                </div>
            </div>
            <!--//container-->
        </div>
    </div>
</section>
@endif   
<!--cities_events END-->

    
<!--Blogs-->
@if(!empty($posts))
<section>
    <div>
        <div class="lgx-inner" style="margin-bottom:-8%;">
            <div class="container">
                <div class="row" style="margin-top:-10%;">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h2 class="heading"><i class="fas fa-blog"></i> @lang('eventmie-pro::em.blogs')</h2>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:-2%; margin-bottom:-2%;">
                    @foreach ($posts as $item)
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="lgx-single-news">
                            <figure>
                                <a href="{{route('eventmie.post_view', $item['slug'])}}">
                                    <img src="/storage/{{ $item['image'] }}" alt="">
                                </a>
                            </figure>
                            
                            <div class="single-news-info">
                                <div class="meta-wrapper hidden">
                                    <span>{{\Carbon\Carbon::parse($item['updated_at'])->translatedFormat(format_carbon_date())}}</span>
                                </div>
                                <h3 class="title"><a href="{{route('eventmie.post_view', $item['slug'])}}">{{$item['title']}}</a></h3>
                                <div class="meta-wrapper">
                                    <span>{{ $item['excerpt'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>    
                    @endforeach
                </div>
                <div class="section-btn-area">
                    <a class="lgx-btn" href="{{route('eventmie.get_posts')}}"><i class="fas fa-blog"></i> @lang('eventmie-pro::em.view_all_blogs')</a>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif    
<!--Blogs END-->

<!--Organiser section-->
<section>
    <div id="lgx-schedule" class="lgx-schedule lgx-schedule-dark">
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
            <div class="container" style="margin-bottom:-7%;">
                <div class="row" style="margin-top:-10%;">
                    <div class="col-xs-12">
                        <div class="lgx-heading lgx-heading-white">
                            <h3 class="subheading">@lang('eventmie-pro::em.how_it_works')</h3>
                            <h2 class="heading"><i class="fas fa-person-booth"></i> @lang('eventmie-pro::em.for_event_organisers')</h2>
                        </div>
                    </div>
                    <!--//main COL-->
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-travelinfo-content lgx-content-white">
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-calendar-plus fa-4x"></i>
                                <h3 class="title">1. @lang('eventmie-pro::em.organiser_1')</h3>
                                <p class="info">@lang('eventmie-pro::em.organiser_1_info')</p>
                            </div>
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-calendar-check fa-4x"></i>
                                <h3 class="title">2. @lang('eventmie-pro::em.organiser_2')</h3>
                                <p class="info">@lang('eventmie-pro::em.organiser_2_info')</p>
                            </div>
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-money-check-alt fa-4x"></i>
                                <h3 class="title">3. @lang('eventmie-pro::em.organiser_3')</h3>
                                <p class="info">@lang('eventmie-pro::em.organiser_3_info')</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
    </div>
</section>
<!--TRAVEL INFO END-->

<!--TRAVEL INFO-->
<section>
    <div id="lgx-travelinfo" class="lgx-travelinfo">
        <div class="lgx-inner">
            <div class="container" style="margin-bottom:-7%;">
                <div class="row" style="margin-top:-9%;">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h3 class="subheading">@lang('eventmie-pro::em.how_it_works')</h3>
                            <h2 class="heading">@lang('eventmie-pro::em.for_customers')</h2>
                        </div>
                    </div>
                    <!--//main COL-->
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-travelinfo-content">
                             <div class="lgx-travelinfo-single">
                                <i class="fas fa-calendar-alt fa-4x"></i>
                                <h3 class="title">1. @lang('eventmie-pro::em.customer_1')</h3>
                                <p class="info">@lang('eventmie-pro::em.customer_1_info')</p>
                            </div>
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-ticket-alt fa-4x"></i>
                                <h3 class="title">2. @lang('eventmie-pro::em.customer_2')</h3>
                                <p class="info">@lang('eventmie-pro::em.customer_2_info')</p>
                            </div>
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-walking fa-4x"></i>
                                <h3 class="title">3. @lang('eventmie-pro::em.customer_3')</h3>
                                <p class="info">@lang('eventmie-pro::em.customer_3_info')</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
    </div>
</section>
<!--TRAVEL INFO END-->


@endsection

@section('javascript')

<script type="text/javascript">
  $(document).ready(function(){
   console.log('data yanha script me');
   var value = localStorage.getItem("emails"); 

   var logout= localStorage.getItem("logout");
 
   console.log(value); 
   $('#logouts').html(value)
  
   if ($('#logouts').html() == "") {
    $("#loginmodals").show();
   }else{
       
    $("#logoutmodals").show();
    var loggedIn={!! json_encode(Auth::check()) !!};
         if(loggedIn){
        var    email = $('#operator_auth_email').val();
            console.log('all data is here');
            console.log(email);
       
            localStorage.setItem('emails', email);

            var value = localStorage.getItem("emails"); 
   console.log(value); 
   $('#logouts').html(value)
  
   if ($('#logouts').html() == "") {
    $("#loginmodals").show();
   }else{
       
    $("#logoutmodals").show();
   }
   localStorage.setItem('captcha', _grecaptcha);

var cap = localStorage.getItem("captcha"); 
console.log(cap); 

   
            }
            else{
                localStorage.removeItem("emails");
                      console.log('rakesh and sushant data is here');

            }
   }
        
        });
       
    var google_map_key = {!! json_encode(setting('apps.google_map_key')) !!};
</script>
<script type="text/javascript" src="{{ eventmie_asset('js/welcome_v1.6.js') }}"></script>
@stop