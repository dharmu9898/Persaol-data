

@extends('eventmie::layouts.app')

@section('title') @lang('eventmie-pro::em.home') @endsection

@section('content')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 
<!--Banner slider start-->
  
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
    <div>
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-12"style="margin-bottom:-10%; margin-top:-2%;">
                        <div class="main-search">
                            <form class="form-inline" type="GET" action="{{route('eventmie.tour_index')}}">
                                <div class="form-group input-group event-search">
                                    <span class="input-group-addon"><i class="fas fa-calendar-day"></i></span>
                                    <input type="text" class="form-control" name="search" placeholder="@lang('eventmie-pro::em.search_trip_by')">
                                </div>
                                <button type="submit" class="lgx-btn lgx-btn-black"><i class="fas fa-search"></i> @lang('eventmie-pro::em.search_trip')</button>
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
<!--cities_events-->
@if(!empty($latest_trips))
<section>
<div id="lgx-schedule" class="lgx-schedule lgx-schedule-dark mt-5" >
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
     
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="lgx-heading" style="margin-top:-10%;">
                            <h2 class="heading" style="color:white;" ><i class="fas fa-hourglass-half"></i> @lang('eventmie-pro::em.explore_trip')</h2>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-top:-5%;">
                    <div class="offset-1 col-10 col-lg-offset-1 col-lg-10">
                        <trip-listing 
                            :events="{{ json_encode($latest_trips, JSON_HEX_APOS) }}" 
                            :currency="{{ json_encode($currency, JSON_HEX_APOS) }}">
                        </trip-listing>
                    </div>
                </div>

                <div style="margin-bottom:-9%;" class="section-btn-area">
                    <a class="lgx-btn lgx-btn-red" href="{{ route('eventmie.tour_index') }}"  target="_blank"><i class="fas fa-calendar-day"></i> @lang('eventmie-pro::em.view_all_trips')</a>
                </div>
                <!-- <div style="margin-bottom:-9%;" class="section-btn-area">
                    <a class="lgx-btn lgx-btn-red" href="{{ route('eventmie.tour_myindex') }}"  target="_blank"><i class="fas fa-calendar-day"></i>Booking</a>
                </div> -->
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif
<!--Event Upcoming Start-->
@if(!empty($upcomming_events))
<section>
    <div>
        <div class="lgx-inner">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12" id="faphead1">
                        <div class="lgx-heading" style="margin-top:-5%;">
                            <h2 class="heading"><i class="fas fa-hourglass-half"></i> @lang('eventmie-pro::em.upcoming_trip')
                           </h2>
                        </div>
                    </div>
                </div>
                <div  class="row mb-5" style="margin-top:-3%;">
                    <div class="offset-1 col-10 col-lg-offset-1 col-lg-10">
                        <trip-listing
                            :events="{{ json_encode($upcomming_events, JSON_HEX_APOS) }}"
                            :currency="{{ json_encode($currency, JSON_HEX_APOS) }}">
                        </trip-listing>
                    </div>
                </div>
                <div style="margin-bottom:-9%;" class="section-btn-area">
                    <a class="lgx-btn lgx-btn-red" href="{{ route('eventmie.tour_index') }}"  target="_blank"><i class="fas fa-calendar-day"></i> @lang('eventmie-pro::em.view_all_trips')</a>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif

<!--cities_events-->
@if(!empty($cities_events))
<section>
    <div id="lgx-schedule" class="lgx-schedule lgx-schedule-light">
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
            <div class="container">
                <div class="row"style="">
                    <div class="col-12">
                        <div class="lgx-heading">
                            <h2 class="heading">@lang('eventmie-pro::em.cities_trip')  
                                </h2>
                        </div>
                    </div>
                </div>
                <!--//main row-->
                <div  class="row " >
                    <div class="col-12" >
                        <div class="sponsors-area sponsors-area-border sponsors-area-col3" >
                            @foreach($cities_events as $key => $item)
                            <div class="single">
                                <a href="{{route('eventmie.tour_index', ['search' => urlencode($item->city)])}}" target="_blank">
                                    <img src="https://www.holidaylandmark.com/events/storage/{{ $item->poster }}" alt="{{ $item->city }}" />
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
<!--Event Upcoming END-->
<!--Event Featured END-->
@if(!empty($featured_events))
<section>
<div id="lgx-schedule" class="lgx-schedule lgx-schedule-light"style="margin-top:-13%;">
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
        <div class="container-fluid accordion" id="faq" >
                <div class="row">
                    <div class="col-12" id="faqhead1">
                        <div style="margin-top:-10%;" class="lgx-heading collapsed" data-toggle="collapse" data-target="#collapseOne"  aria-expanded="true" aria-controls="faq1" >
                            <h2 class="heading"><i class="fas fa-star"></i> @lang('eventmie-pro::em.featured_trips')
                            <i  class="far fa-hand-point-right float-right" >click here</i></h2>
                        </div>
                    </div>
                </div>
                <div id="collapseOne" class="row collapse mb-5" aria-labelledby="faqhead1" data-parent="#faq" style="margin-top:-5%;">
                    <div class="offset-1 col-10 col-lg-offset-1 col-lg-10">
                    <trip-listing :events="{{ json_encode($featured_events, JSON_HEX_APOS) }}"
                                        :currency="{{ json_encode($currency, JSON_HEX_APOS) }}" >
                        </trip-listing>
                    </div>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif
<!--Event Top-selling Start-->
@if(!empty($top_selling_events))
<section>
    <div id="lgx-schedule" class="lgx-schedule lgx-schedule-dark">
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="lgx-heading lgx-heading-white">
                            <h2 class="heading"><i class="fas fa-bolt"></i> @lang('eventmie-pro::em.top_selling_trips')</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-1 col-10 col-lg-offset-1 col-lg-10">
                        <trip-listing :events="{{ json_encode($top_selling_events, JSON_HEX_APOS) }}"
                            :currency="{{ json_encode($currency, JSON_HEX_APOS) }}">
                        </trip-listing>
                    </div>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif
<!--Event Top-selling END-->
@if(!empty($latest_events))
<section>
<div id="lgx-schedule" class="lgx-schedule lgx-schedule-light" style="margin-top:-13%;" >
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
     
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="lgx-heading" style="margin-top:-10%;">
                            <h2 class="heading"><i class="fas fa-hourglass-half"></i> @lang('eventmie-pro::em.explore_events')</h2>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-top:-5%;">
                    <div class="offset-1 col-10 col-lg-offset-1 col-lg-10">
                        <event-listing 
                            :events="{{ json_encode($latest_events, JSON_HEX_APOS) }}" 
                            :currency="{{ json_encode($currency, JSON_HEX_APOS) }}"
>
                        </event-listing>
                    </div>
                </div>

                <div style="margin-bottom:-9%;" class="section-btn-area">
                    <a class="lgx-btn lgx-btn-red" href="http://hl-events/allevents"><i class="fas fa-calendar-day"></i> @lang('eventmie-pro::em.view_all_events')</a>
                </div>
                
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif
@if(!empty($top_selling_events))
<section>
    <div id="lgx-schedule" class="lgx-schedule lgx-schedule-dark">
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="lgx-heading lgx-heading-white">
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
                <div class="section-btn-area">
                    <a class="lgx-btn lgx-btn-red" href="{{ route('eventmie.events_index') }}"><i class="fas fa-calendar-day"></i> @lang('eventmie-pro::em.view_all_events')</a>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif




@if(!empty($upcomming_trip))
<section>
    <div>
        <div class="lgx-inner">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-12" >
                        <div class="lgx-heading collapsed" 
                        style="margin-top:-9%; margin-bottom:-10%;">
                            <h2 class="heading"> @lang('eventmie-pro::em.find_upcoming_events')
                           </h2>
                        </div>
                    </div>
                </div>
                <div  class="row  mb-5"   style="margin-top:-5%;">
                    <div class="offset-1 col-10 col-lg-offset-1 col-lg-10">
                        <event-listing
                            :events="{{ json_encode($upcomming_trip, JSON_HEX_APOS) }}"
                            :currency="{{ json_encode($currency, JSON_HEX_APOS) }}">
                        </event-listing>
                    </div>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
@endif
<!--cities_events-->
@if(!empty($events_cities))
<section>
  <div id="lgx-schedule" class="lgx-schedule lgx-schedule-light " >
    <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
      <div class="container"  style="margin-top:-10%; margin-bottom:;">
        <div class="row" style="margin-top:;">
          <div class="col-12" >
            <div class="lgx-heading">
              <h2 class="heading">@lang('eventmie-pro::em.cities_event')
             
              </h2>
            </div>
          </div>
        </div>
        <!--//main row-->
        <div class="row" style="margin-top:%; margin-bottom:;">
          <div class="col-12">
            <div  class="sponsors-area sponsors-area-border sponsors-area-col3 ">
              @foreach($events_cities as $key => $item)
              <div class="single">
                <a href="{{route('eventmie.events_index', ['search' => urlencode($item->city)])}}"target="_blank">
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

<!--cities_events END-->
<!--Categories-->

  
<!--Categories END-->
@if(!empty($eventcategories))
<section>
    <div id="lgx-schedule" class="lgx-schedule lgx-schedule-dark" >
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
            <div class="container">
                <div class="row" style="margin-top:-10%;">
                    <div class="col-12">
                        <div class="lgx-heading lgx-heading-white" >
                            <h2 class="heading">@lang('eventmie-pro::em.events_categories')</h2>
                        </div>
                    </div>
                </div>
                <!--//main row-->
                <div class="row" style="margin-bottom:-10%;">
                    <div class="col-12">
                        <div class="sponsors-area sponsors-area-border sponsors-area-col3">
                            @foreach($eventcategories as $key => $item)
                            <div class="single">
                           
                                <a href="https://www.holidaylandmark.com/events/allevents?category={{ $item['name'] }}" target="_blank">
                                <img src="https://www.holidaylandmark.com/storage/{{ $item['thumb'] }}" alt="{{ $item['slug'] }}"/>
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
<!--cities_events-->


    
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
                <div class="row"style="margin-top:-2%; margin-bottom:-2%;">
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
<!--Blogs-->
  
<!--Blogs END-->

<!--Organiser section-->
<section>
    <div id="lgx-schedule" class="lgx-schedule lgx-schedule-dark" >
        <div class="lgx-inner" style="background-image: url({{ eventmie_asset('img/bg-pattern.png') }});">
            <div class="container" style="margin-bottom:-7%;">
                <div class="row" style="margin-top:-10%;">
                    <div class="col-xs-12">
                        <div class="lgx-heading lgx-heading-white">
                            <h3 class="subheading">@lang('eventmie-pro::em.how_it_works')</h3>
                            <h2 class="heading"><i class="fas fa-person-booth"></i> @lang('eventmie-pro::em.for_organisers')</h2>
                        </div>
                    </div>
                    <!--//main COL-->
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-travelinfo-content lgx-content-white">
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-calendar-plus fa-4x"></i>
                                <h3 class="title">1. @lang('eventmie-pro::em.organisers_4')</h3>
                                <p class="info">@lang('eventmie-pro::em.organisers_4_info')</p>
                            </div>
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-calendar-check fa-4x"></i>
                                <h3 class="title">2. @lang('eventmie-pro::em.organisers_5')</h3>
                                <p class="info">@lang('eventmie-pro::em.organisers_5_info')</p>
                            </div>
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-money-check-alt fa-4x"></i>
                                <h3 class="title">3. @lang('eventmie-pro::em.organisers_6')</h3>
                                <p class="info">@lang('eventmie-pro::em.organisers_6_info')</p>
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
            <div class="container"style="margin-bottom:-7%;">
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
                                <h3 class="title">1. @lang('eventmie-pro::em.customers_4')</h3>
                                <p class="info">@lang('eventmie-pro::em.customers_4_info')</p>
                            </div>
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-ticket-alt fa-4x"></i>
                                <h3 class="title">2. @lang('eventmie-pro::em.customers_5')</h3>
                                <p class="info">@lang('eventmie-pro::em.customers_5_info')</p>
                            </div>
                            <div class="lgx-travelinfo-single">
                                <i class="fas fa-walking fa-4x"></i>
                                <h3 class="title">3. @lang('eventmie-pro::em.customers_6')</h3>
                                <p class="info">@lang('eventmie-pro::em.customers_6_info')</p>
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
<div class="jumbotron text-center" id="apppromotion" style="margin-bottom:0px; background-color:#00192F">
   <div class="row">
      <div class="col-md-5 col-lg-5 text-center">
         <div class="column mt-5">
            <div class="col-sm-12">
               <h2 style="font-size: 40px;font-weight: 600;line-height: 1;letter-spacing: -.55px;color:white;"><b>Download the
                     holidaylandmark App</b></h2>
            </div><br>
            <!-- <div class="col-sm-12 mt-2" ></div><br> -->
            <div class="row mt-3">
               <div class="col-2"></div>
               <div class="col-sm-8" style="font-size:20px;color:white; ">
                  Search, Select & Create Your Tour and Event for All your Holiday On The Go.
               </div>
               <div class="col-2"></div>
            </div>
            <div class="row mt-5 mb-5">
               <div class="col-2"></div>
               <div class="col-sm-4">
             
                  <a href="https://play.google.com/store/apps/details?id=com.cotocus.holidaylandmark.holidaylandmark" target="_blank">
                     <img src="{{ asset('../image/play_store.png') }}" alt="get it on Google Play" width="179"
                        height="54"></a>
               </div>
               <div class="col-sm-4">
                  <a href="#">
                     <img src=" {{ asset('../image/iOS_store.png') }}" alt="download on the App Store" width="179"
                        height="54">
                  </a>
               </div>
               <div class="col-2"></div>
            </div>
         </div>
      </div>
      <div class="col-md-7 col-lg-7">
         <div class="row">
            <div class="col-sm-6 col-xs-6" >
               <img src="{{ asset('../image/holidaylandmark user.webp') }}" alt="" width="320" height="580">
            </div>
            <div class="col-sm-6 col-xs-6 mt-5" >
               <img src="{{ asset('../image/holidaylandmark login.webp') }}" alt="" width="320" height="580">
            </div>
         </div>
      </div>
   </div>
</div>
<!--TRAVEL INFO END-->


@endsection

@section('javascript')

<script type="text/javascript">
    var google_map_key = {!! json_encode(setting('apps.google_map_key')) !!};
</script>
<script type="text/javascript" src="{{ eventmie_asset('js/welcome_v1.6.js') }}"></script>
@stop