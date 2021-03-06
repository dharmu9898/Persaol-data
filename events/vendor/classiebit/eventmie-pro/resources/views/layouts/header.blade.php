

<script src="{{asset('js/jquery.min.js')}}"> </script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<header>
    <div id="eventmie_auth_app" class="lgx-header">
        <div id="navbar_vue" class="lgx-header-position lgx-header-position-white lgx-header-position-fixed">
            <div class="lgx-container-fluid">
                <!-- GDPR -->
                <cookie-law theme="gdpr" button-text="@lang('eventmie-pro::em.accept')">
                    <div slot="message">
                        <gdpr-message></gdpr-message>
                    </div>
                </cookie-law>
                <!-- GDPR -->

                <!-- Vue Alert message -->
                @if ($errors->any())
                <alert-message :errors="{{ json_encode($errors->all(), JSON_HEX_APOS) }}"></alert-message>
                @endif

                @if (session('status'))
                <alert-message :message="'{{ session('status') }}'"></alert-message>
                @endif
                <!-- Vue Alert message -->
                <nav class=" navbar navbar-default lgx-navbar navbar-expand-lg">
                <button   type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" onclick="document.getElementById('navbar').classList.toggle('in')">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>


                    <div style="margin-top:-0.2em;" class="navbar-header">
                        <div class="lgx-logo">
                            <a target="_blank" href="https://www.facebook.com/holidaylandmark"><i
                                    class="fa fa-facebook mt-2 mr-4" style="font-size:20px; color:white;"></i></a>
                            <a target="_blank" href="https://twitter.com/HolidayLandmark"><i
                                    class="fa fa-twitter mt-2 mr-4" style="font-size:20px; color:white;"></i></a>
                            <a target="_blank" href="https://www.instagram.com/holidaylandmark/"><i
                                    class="fa fa-instagram mt-2 mr-4" style="font-size:20px;color:white;"></i></a>
                            <a target="_blank" href="https://www.linkedin.com/company/holidaylandmark/about/"><i
                                        class="fa fa-linkedin mt-2 mr-4" style="font-size:20px;color:white;"></i></a>
                            <a target="_blank" href="http://www.youtube.com/user/HolidayLandmark"><i
                                    class="fa fa-youtube mt-2 mr-4" style="font-size:20px;color:white;"></i></a>
                        </div>
                    </div>
                    
                    <div id="navbar" class="navbar-collapse collapse">
                        <div  class="lgx-nav-right navbar-right">
                            <div class="lgx-cart-area">
                                <a target="_blank" class="lgx-btn lgx-btn-sm" href="{{ eventmie_url('allevents') }}"><i
                                        class="fas fa-calendar-day"></i> @lang('eventmie-pro::em.browse_events')</a>
                            </div>
                        </div>

                        <div  class="lgx-nav-right navbar-right">
                            <div class="lgx-cart-area">
                                <a target="_blank" class="lgx-btn lgx-btn-sm" href="https://www.holidaylandmark.com/trips"><i
                                        class="fas fa-calendar-day"></i> @lang('eventmie-pro::em.browse_trips')</a>
                            </div>
                        </div>
                            

                        <ul class="nav navbar-nav lgx-nav navbar-right ">
                            <!-- Authentication Links -->
                            @guest
                            <li class="mt-4 form-inline">
                         <form class="form-inline"  type="GET" action="{{route('eventmie.events_index')}}">
                            <div class="form-group input-group event-search">  
                                <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search" aria-label="Search"
                                style=" height:3.2em; width:100%;  margin-left:-45%; border-radius: 8px;">
                             <button class="btn btn-primary my-2 my-sm-0"
                            style="margin-left:-10%; height:3.5em; width:40%; background:#1b89ef; font-size:13px; border-radius: 8px;"
                            type="submit"><i class="fas fa-search"></i> </button></div>
                            </form>
                        </li> 
							
                        <span id="logouts"  style="display: none;"></span>
                        
                        <li style="margin-top:-2.5%;">
                        <a class="lgx-scroll"data-toggle="modal" data-target="#post_class"><i
                                        class="fas fa-fingerprint"></i> @lang('eventmie-pro::em.login_or_create_account')</a>          
                            </li>
                            
                            <!-- <li>
                                <a target="_blank" class="lgx-scroll" href="{{ route('eventmie.register') }}"><i
                                        class="fas fa-fingerprint"></i> @lang('eventmie-pro::em.register')</a>
                            </li> -->
                           
                            @else
                            <li>
                                
					 {{-- Common Header --}}
                            {{-- categories menu items --}}
                            @php $categoriesMenu = categoriesMenu() @endphp
                            @if(!empty($categoriesMenu))
                            <li>
                                <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-stream"></i> @lang('eventmie-pro::em.events_categories') <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu multi-level" style="margin-top:-2em;">
                                    @foreach($categoriesMenu as $val)
                                    <li>
                                        <a class="lgx-scroll" href="{{route('eventmie.events_index', ['category' => urlencode($val->name)])}}">
                                            {{ $val->name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                            {{-- additional header menu items --}}
                            @php $headerMenuItems = headerMenu() @endphp
                            @if(!empty($headerMenuItems))
                            <li>
                                <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-th"></i> @lang('eventmie-pro::em.popular_and_latest') <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu multi-level" style="margin-top:-2em;">
                                    @foreach($headerMenuItems as $parentItem)
                                        @if(!empty($parentItem->submenu)) 
                                        <li class="dropdown-submenu">
                                            <a disabled class="dropdown-toggle disabled" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="{{ $parentItem->icon_class }}"></i> {{ $parentItem->title }} &nbsp;&nbsp;<i class="fas fa-angle-right"></i></a>
                                            <ul class="dropdown-menu">
                                                @foreach($parentItem->submenu as $childItem)
                                                <li>
                                                    <a target="{{ $childItem->target }}" href="{{ $childItem->url }}">
                                                        <i class="{{ $childItem->icon_class }}"></i> {{ $childItem->title }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        @else
                                        <li>
                                            <a class="lgx-scroll" target="{{ $parentItem->target }}" href="{{ $parentItem->url }}">
                                                <i class="{{ $parentItem->icon_class }}"></i> {{ $parentItem->title }}
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                                <ul class="dropdown-menu multi-level">
                                    {{-- for customers --}}
                                    @if(Auth::user()->hasRole('customer'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i
                                                class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
                                    </li>
                                    @if(setting('multi-vendor.multi_vendor'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i
                                                class="fas fa-person-booth"></i>
                                            @lang('eventmie-pro::em.become_organiser')</a>
                                    </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.mybookings_index') }}"><i
                                                class="fas fa-money-check-alt"></i>
                                            @lang('eventmie-pro::em.mybookings')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href='{{eventmie_url("admin/dashboard")}}'><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_trips')</a>
                                    </li>


                                    @endif

                                    @if(Auth::user()->hasRole('organiser'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.profile') }}" target="_blank"><i
                                                class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.myevents_index') }}" target="_blank"><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_events')</a>
                                    </li>
                                    <li>
                                    <a class="dropdown-item" href="{{ route('eventmie.bookingsmy_index') }}" target="_blank"><i
                                            class="fas fa-money-check-alt"></i>
                                        @lang('eventmie-pro::em.mybookings')</a>
                                     </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.obookings_index') }}" target="_blank"><i
                                                class="fas fa-money-check-alt"></i>
                                            @lang('eventmie-pro::em.manage_bookings')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.event_earning_index') }}" target="_blank"><i
                                                class="fas fa-wallet"></i> @lang('eventmie-pro::em.manage_earning')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href='{{eventmie_url("touroperator/dashboards")}}' target="_blank"><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_trips')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.tags_form') }}" target="_blank"><i
                                                class="fas fa-user-tag"></i> @lang('eventmie-pro::em.manage_tags')</a>
                                    </li>

                                    @endif
                                        
                  
                                    

                            
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> @lang('eventmie-pro::em.logout')
                                        </a>
                                        <form id="logout-form" action="{{ route('eventmie.logout') }}" method="POST"
                                            style="display: none;">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </form>
                                    </li>

                                </ul>
                            </li>

                            
                            {{-- If user is admin then show admin panel link --}}
                            @if(Auth::user()->hasRole('admin'))
                           
                            <li>
                                <a class="dropdown-item" href="{{ route('eventmie.logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> @lang('eventmie-pro::em.logout')
                                </a>
                                <form id="logout-form" action="{{ route('eventmie.logout') }}" method="POST"
                                    style="display: none;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            </li>

                            @endif


                            {{-- If user is organiser then show create event link (only if multi-vendor is on) --}}


                            @if(Auth::user()->hasRole('organiser') && setting('multi-vendor.multi_vendor'))
                          
                            <li>
                                <a class="dropdown-item" href="{{ route('eventmie.logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> @lang('eventmie-pro::em.logout')
                                </a>
                                <form id="logout-form" action="{{ route('eventmie.logout') }}" method="POST"
                                    style="display: none;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            </li>

                            @endif
                            

                            {{-- If user is customer then show my bookings link --}}
                            @if(Auth::user()->hasRole('customer'))
                            
                            <li>
                                <a class="dropdown-item" href="{{ route('eventmie.logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> @lang('eventmie-pro::em.logout')
                                </a>
                                <form id="logout-form" action="{{ route('eventmie.logout') }}" method="POST"
                                    style="display: none;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            </li>
                            @endif

                            @endguest



                        </ul>
                    </div>
                </nav>

                <nav class="navbar navbar-default lgx-navbar navbar-expand-lg">
                    <div style="margin-top:-1em; margin-left:-2.3em;" class="navbar-header">

                        <div style="margin-top:-1.2em;" class="lgx-logo">
                            <a href="{{ eventmie_url() }}" class="lgx-scroll">
                                <img style="margin-right:-90%;" src="https://www.holidaylandmark.com/storage/settings/July2021/5Lb2gZAoOvuTZ636ly7M.png" />
                                <span style="margin-top:-2em; margin-left:-12px;"
                                    class="brand-name">{{ setting('site.site_name') }}</span>
                                <span style="margin-top:-1.4em;"
                                    class="brand-slogan">{{ setting('site.site_slogan') }}</span>
                            </a>
                        </div>
                    </div>
                    
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul style="margin-top:-2em;" class="nav navbar-nav lgx-nav navbar-right">
                            <!-- Authentication Links -->
                            <li>
                                <a class="lgx-scroll" href="http://www.holidaylandmark.com/" >Home</a>
                            </li>
                            <li>
                                <a class="lgx-scroll" href="{{ route('eventmie.trips') }}" >Trips</a>
                            </li>
                            <li>
                                <a class="lgx-scroll" href="{{ route('eventmie.events') }}" >Events</a>
                            </li>
                           
                            
							
                           

                            <li>
                                <a class="lgx-scroll" href=""><i class="fas fa-calendar-plus"></i> Create Trips / Events</a>
                                <ul class="dropdown-menu">
                                <li id="logintrip" style="display: none;">
                                        <a  class="lgx-scroll" href="https://www.holidaylandmark.com/events/login" >Add
                                            Trip</a>
                                    </li>
                                   
                                    <li id="welcomemytrip"  style="display: none;">
                                        <a class="lgx-scroll" href="https://www.holidaylandmark.com/events/profile/" >Add
                                            Trip</a>
                                    </li>
                                    <li id="welcometrip"  style="display: none;">
                                        <a class="lgx-scroll" href="https://www.holidaylandmark.com/events/mytour/manage/" >Add
                                            Trip</a>
                                    </li>
                                  
                                    <li id="loginevent"  style="display: none;" >
                                        <a class="lgx-scroll" href="https://www.holidaylandmark.com/events/login" >Add Event</a>
                                    </li>
                                    <li id="welcomemyevent"  style="display: none;">
                                        <a class="lgx-scroll" href="https://www.holidaylandmark.com/events/profile/" >Add Event</a>
                                    </li>
                                    <li id="welcomeevent"  style="display: none;">
                                        <a class="lgx-scroll" href="https://www.holidaylandmark.com/events/myevents/manage#/" >Add Event</a>
                                    </li>
                                    <li>
                                        <a class="lgx-scroll" href="https://www.holidaylandmark.com/blog/wp-admin/" >Add
                                            Blog Post</a>
                                    </li>
                                </ul>
                            </li>
                        
                         
                            <li>
                                <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown"
                                    role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                     <img src="{{ asset('../image/india.png') }}" width="28" height="20" style="margin-left:-5px;" />
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach(lang_selector() as $val)
                                    <li>
                                        <a class="dropdown-item {{ $val == config('app.locale') ? 'active' : '' }}"
                                            href="{{ route('eventmie.change_lang', ['lang' => $val]) }}">@lang('eventmie-pro::em.lang_'.$val)</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @guest
								{{-- additional header menu items --}}
                            @php $headerMenuItems = headerMenu() @endphp
                            @if(!empty($headerMenuItems))
                            <li>
                                <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-th"></i> @lang('eventmie-pro::em.popular_and_latest') <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu multi-level">
                                    @foreach($headerMenuItems as $parentItem)
                                        @if(!empty($parentItem->submenu)) 
                                        <li class="dropdown-submenu">
                                            <a disabled class="dropdown-toggle disabled" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="{{ $parentItem->icon_class }}"></i> {{ $parentItem->title }} &nbsp;&nbsp;<i class="fas fa-angle-right"></i></a>
                                            <ul class="dropdown-menu">
                                                @foreach($parentItem->submenu as $childItem)
                                                <li>
                                                    <a target="_blank" target="{{ $childItem->target }}" href="{{ $childItem->url }}">
                                                        <i class="{{ $childItem->icon_class }}"></i> {{ $childItem->title }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        @else
                                        <li>
                                            <a target="_blank" class="lgx-scroll" target="{{ $parentItem->target }}" href="{{ $parentItem->url }}">
                                                <i class="{{ $parentItem->icon_class }}"></i> {{ $parentItem->title }}
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                            @endif

                            {{-- Common Header --}}
                            {{-- categories menu items --}}
                            @php $categoriesMenu = categoriesMenu() @endphp
                            @if(!empty($categoriesMenu))
                            <li>
                                <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-stream"></i> @lang('eventmie-pro::em.events_categories') <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu multi-level">
                                    @foreach($categoriesMenu as $val)
                                    <li>
                                        <a target="_blank" class="lgx-scroll" href="{{route('eventmie.events_index', ['category' => urlencode($val->name)])}}">
                                            {{ $val->name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                            
                            @else

                            @if(!\Auth::user()->hasRole('admin'))
                            <li>
                                @php
                                $data = notifications();
                                @endphp

                                <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown"
                                    role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-bell"> </i>

                                    <span class="badge bg-red">{{$data['total_notify']}}</span>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @if(!empty($data['notifications']))
                                    @foreach ($data['notifications'] as $notification)
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{route('eventmie.notify_read', [$notification->n_type])}}">
                                            {{ $notification->total    }}
                                            {{ $notification->n_type    }}
                                        </a>
                                    </li>
                                    @endforeach
                                    @else
                                    <li>
                                        <a class="dropdown-item"> @lang('eventmie-pro::em.no_notifications')</a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            <li>
                                <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown"
                                    role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if(Auth::user()->hasRole('customer'))
                                    <i class="fas fa-user-circle"></i>
                                    @elseif(Auth::user()->hasRole('organiser'))
                                    <i class="fas fa-person-booth"></i>
                                    @else
                                    <i class="fas fa-user-shield"></i>
                                    @endif
                                    <input type="hidden" name="operator_auth_email" id="operator_auth_email"
                                    value="{{Auth::user()->email}}">
                                    <input type="hidden" name="operator_auth_name" id="operator_auth_name"
                                    value="{{Auth::user()->name}}">
                                    <input type="hidden" name="operator_auth_id" id="operator_auth_id"
                                    value="{{Auth::user()->role_id}}">
                                    {{Str::limit( Auth::user()->name,12) }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu multi-level">

                                    {{-- for customers --}}
                                    @if(Auth::user()->hasRole('customer'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i
                                                class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
                                    </li>


                                    @if(setting('multi-vendor.multi_vendor'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i
                                                class="fas fa-person-booth"></i>
                                            @lang('eventmie-pro::em.become_organiser')</a>
                                    </li>


                                    @endif

                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.mybookings_index') }}"><i
                                                class="fas fa-money-check-alt"></i>
                                            @lang('eventmie-pro::em.mybookings')</a>
                                    </li>
                                   

                                    @endif

                                    @if(Auth::user()->hasRole('organiser'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.profile') }}" target="_blank"><i
                                                class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.visit_index') }}" target="_blank"><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.my_dashboard')</a>
                                    </li>
                                    <li>
                                    <a class="dropdown-item" href="{{ route('eventmie.bookingsmy_index') }}" target="_blank"><i
                                            class="fas fa-money-check-alt"></i>
                                        @lang('eventmie-pro::em.mybookings')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.myevents_index') }}" target="_blank"><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_events')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.obookings_index') }}" target="_blank"><i
                                                class="fas fa-money-check-alt"></i>
                                            @lang('eventmie-pro::em.manage_bookings')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.event_earning_index') }}" target="_blank"><i
                                                class="fas fa-wallet"></i> @lang('eventmie-pro::em.manage_earning')</a>
                                    </li>
                                    
                                    <li>
 
                                    <a class="dropdown-item" href="{{ route('eventmie.trips_index') }}" target="_blank"><i
                                            class="fas fa-calendar-alt"></i>
                                        @lang('eventmie-pro::em.manage_trips')</a>
                                    </li>
                                   
                                    
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.tags_form') }}" target="_blank"><i
                                                class="fas fa-user-tag"></i> @lang('eventmie-pro::em.manage_tags')</a>
                                    </li>
                                  
                                    <li>
                                        <a class="lgx-scroll" href="{{ route('eventmie.ticket_scan') }}" target="_blank">
                                            <i class="fas fa-qrcode"></i> @lang('eventmie-pro::em.scan_ticket')</a>
                                    </li>
                                    <li>
                                        <a class="lgx-scroll" href="{{ route('eventmie.myevents_form') }}" target="_blank">
                                            <i class="fas fa-calendar-plus"></i>
                                            @lang('eventmie-pro::em.create_event')</a>
                                    </li>
                                    <li>
                                        <a class="lgx-scroll" href="{{ route('eventmie.mytrips_form') }}" target="_blank">
                                            <i class="fas fa-calendar-plus"></i>
                                            @lang('eventmie-pro::em.create_trips')</a>
                                    </li>

                                 @endif

                                  

                                    @if(Auth::user()->hasRole('admin'))
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ eventmie_url().'/'.config('eventmie.route.admin_prefix') }}">
                                            <i class="fas fa-tachometer-alt"></i>
                                            @lang('eventmie-pro::em.admin_panel')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href='{{eventmie_url("admins/dashboard")}}'><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_trips')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i
                                                class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
                                    </li>
                                    <li>
                                        <a class="lgx-scroll" href="{{ route('eventmie.ticket_scan') }}">
                                            <i class="fas fa-qrcode"></i> @lang('eventmie-pro::em.scan_ticket')</a>
                                    </li>
                                    <li>
                                        <a class="lgx-scroll" href="{{ route('eventmie.myevents_form') }}">
                                            <i class="fas fa-calendar-plus"></i>
                                            @lang('eventmie-pro::em.create_event')</a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            {{-- If user is admin then show admin panel link --}}


                            {{-- If user is organiser then show create event link (only if multi-vendor is on) --}}


                            {{-- If user is customer then show my bookings link --}}
                            @if(Auth::user()->hasRole('customer'))
                           
                            @endif
                            @endguest
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Login Modal Start -->
<!--Modal: Login / Register Form Demo-->
 <div class="modal fade mainmodalhide" id="post_class" tabindex="-1" role="dialog"
            aria-labelledby="examplesModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="width:700px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p style="font-weight:400; font-size:15px; text-align:center;"><b>Not a member yet?</b><a
                                style="font-weight:400; font-size: 20px; font-weight:bold; color:black;"
                                href="{{ route('eventmie.register') }}"> Sign up</a> </p>

                    </div>
                    <div class="modal-body">
                        <div class=" " style="width:670px;">
                            <div class="row">
                                <div class="col-md-6" style="margin-top:-2%;">
                                    <h4 class="col-black">@lang('eventmie-pro::em.login_with_google')</h4>
                                    <a onclick="googleverify();" class="lgx-btn lgx-btn-red btn-block"><i
                                            class="fab fa-google"></i> Google
                                    </a>

                                    <h4 style="color:black; margin-top:-0.1em;">Or Login with Phone <i
                                            class="fa fa-mobile fa-2x" aria-hidden="true"></i></h4>
                                    <div class="alert alert-info" id="sentsSuccess" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert">??</button>
                                    </div>
                                    <div class="alert alert-success" id="showmymodales" style="display: none;">
                                    </div>
                                    <div class="alert alert-info" id="showmodals" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert">??</button>
                                        <h3 style="color:black">Add verification code</h3>
                                        <div class="alert alert-success" id="successOtpAuth" style="display: none;">
                                        </div>
                                        <form>
                                            <input style="background-color: #52595D" type="text" id="verificationCode"
                                                class="form-control" placeholder="Enter verification code">
                                            <br>
                                            <button type="button" class="lgx-btn lgx-btn-white btn-block"
                                                onclick="phcodeverify();"><i class="fas fa-sign-in-alt"></i>
                                                @lang('eventmie-pro::em.verifycode')</button>
                                        </form>
                                    </div>
                                    <div style="display: none;" id='shows'
                                        class="alert alert-success col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12">
                                        <button type="button" class="close" data-dismiss="alert">??</button>
                                        <b>@lang('eventmie-pro::em.otpsends')
                                        </b>
                                    </div>
                                    <div style="display: none;" id='showscode'
                                        class="alert alert-success col-xl-12 col-12 col-sm-12 col-lg-12 col-md-12">
                                        <button type="button" class="close" data-dismiss="alert">??</button>
                                        <b>@lang('eventmie-pro::em.codeverification')
                                        </b>
                                    </div>
                                    @if(config('voyager.demo_mode'))
                                    <div class="alert alert-info">
                                        <a href="https://eventmie-pro-docs.classiebit.com/docs/1.4/demo-accounts"
                                            target="_blank">Visit here for
                                            Demo
                                            Accounts</a>
                                    </div>
                                    @endif
                                    <input type="hidden" value="{{Session::put('link', url()->previous())}}">

                                    <div class="modal fade" id="examplesModal" tabindex="-1" role="dialog"
                                        aria-labelledby="examplesModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="examplesModalLabel"> Enter
                                                        Verification code</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card-body">
                                                        <div class="alert alert-success" id="successRegsiters"
                                                            style="display: none;"></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lgx-registration-form">
                                        <form id="mytripphone_form" role="form" enctype="multipart/form-data">
                                            <div class="alert alert-success" id="myphonesucc" style="display: none;">
                                                <button type="button" class="close" data-dismiss="alert">??</button>
                                            </div>
                                            <input type="text" id="number" name="mobiles_no" style="color:black"
                                                class="wpcf7-form-control form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                name="email" value="{{ old('email') }}" required autofocus
                                                placeholder="@lang('eventmie-pro::em.enterphone')">
                                            <div id="recaptcha-container1"></div>
                                            <button type="button" class="lgx-btn lgx-btn-red btn-block mt-3"
                                                onclick="phoneSendsAuth();"><i class="fas fa-sign-in-alt"></i>
                                                @lang('eventmie-pro::em.sendcode')</button>
                                            <div class="form-check text-left mt-2">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" checked value="1">
                                                <label class="form-check-label" style="color:black;"
                                                    for="remember">@lang('eventmie-pro::em.remember')</label>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                                <div class="col-md-6" align="left" style="border-left:1px solid black; margin-top:-2%;">
                                    <div class="lgx-registration-form">
                                        <h4 style="text-align: center;"> <u>Log in</u> </h4>

                                        <form class="form-horizontal" method="POST" action="{{ route('eventmie.login') }}">
                {{ csrf_field() }}
                <div
                  class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <div class="col-md-12">
                    <input id="email" type="email" class="form-control" placeholder="E-Mail Address" name="email"
                      required autofocus>

                    @if($errors->has('email'))
                      <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>

                <div
                  class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                  <div class="col-md-12">
                    <input id="password" type="password" class="form-control" placeholder="Password" name="password"
                      required autofocus>

                    @if($errors->has('password'))
                      <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>
                <input id="cl_login_token" type="hidden" class="form-control" name="cl_login_token" required>
               
              
                <div class="form-group">
                  <div class="col-md-12">
                  <button type="submit" id="login_submit_btn" class="lgx-btn lgx-btn-red btn-block mt-3"
                                               ><i class="fas fa-sign-in-alt"></i>
                                                @lang('eventmie-pro::em.login')</button>
                 

                   
                  </div>
                </div>
              </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>


        </div>
</header>