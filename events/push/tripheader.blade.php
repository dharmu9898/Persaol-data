
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
                <nav style="margin-top: -1.3em;" class=" navbar navbar-default lgx-navbar navbar-expand-lg">


                    <div id="navbar" class="navbar-collapse collapse">
                        <button style="margin-left:11%;" class="hamburger btn-link">
                            <span class="hamburger-inner"></span>
                        </button>
                        <div style="margin-top:-0.2em;" class="navbar-header">

                            <div class="lgx-logo">
                                <a target="_blank" href="https://www.facebook.com/holidaylandmark"><i
                                        class="fa fa-facebook mt-2 mr-4" style="font-size:20px; color:black;"></i></a>
                                <a target="_blank" href="https://twitter.com/HolidayLandmark"><i
                                        class="fa fa-twitter mt-2 mr-4" style="font-size:20px; color:black;"></i></a>
                                <a target="_blank" href="https://www.instagram.com/holidaylandmark/"><i
                                        class="fa fa-instagram mt-2 mr-4" style="font-size:20px;color:black;"></i></a>
                                <a target="_blank" href="https://www.linkedin.com/company/holidaylandmark/about/"><i
                                        class="fa fa-linkedin mt-2 mr-4" style="font-size:20px;color:black;"></i></a>        
                                <a target="_blank" href="http://www.youtube.com/user/HolidayLandmark"><i
                                        class="fa fa-youtube mt-2 mr-4" style="font-size:20px;color:black;"></i></a>
                                
                            </div>
                        </div>
                        <ul style="margin-top:-1.8em;" class="nav navbar-nav lgx-nav navbar-right ">
                            <!-- Authentication Links -->
                            @guest
                            <li>
                                <a class="lgx-scroll" href="{{ route('eventmie.login') }}"><i
                                        class="fas fa-fingerprint"></i> @lang('eventmie-pro::em.login')</a>
                            </li>
                            <li>
                                <a class="lgx-scroll" href="{{ route('eventmie.register') }}"><i
                                        class="fas fa-fingerprint"></i> @lang('eventmie-pro::em.register')</a>
                            </li>
                            @else



                            <li>


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
                                        <a class="dropdown-item" href='#'><i class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_trips')</a>
                                    </li>

                                    @endif

                                    @if(Auth::user()->hasRole('organiser'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i
                                                class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.myevents_index') }}"><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_events')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.obookings_index') }}"><i
                                                class="fas fa-money-check-alt"></i>
                                            @lang('eventmie-pro::em.manage_bookings')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.event_earning_index') }}"><i
                                                class="fas fa-wallet"></i> @lang('eventmie-pro::em.manage_earning')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href='{{eventmie_url("touroperator/dashboards")}}'><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_trips')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.tags_form') }}"><i
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
                                <a class="lgx-scroll" href="{{ route('eventmie.mybookings_index') }}">
                                    <i class="fas fa-money-check-alt"></i> @lang('eventmie-pro::em.mybookings')</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href='#'><i class="fas fa-calendar-alt"></i>
                                    @lang('eventmie-pro::em.manage_trips')</a>
                            </li>
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

                <nav class="navbar navbar-default lgx-navbar navbar-expand-lg" style="
    margin-right: 3%;">

                    <div style="margin-top:-0.2em;" class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#navbar" aria-expanded="false" aria-controls="navbar" @click="mobileMenu()">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                    </div>
                    <div style="margin-left:10%;" id="navbar" class="navbar-collapse collapse">


                        <ul style="margin-top:-2.5em;" class="nav navbar-nav lgx-nav navbar-right">
                            <!-- Authentication Links -->


                            <li>
                                <a class="lgx-scroll" href="https://www.holidaylandmark.com">Home</a>
                            </li>
                            <li>
                                <a class="lgx-scroll" href="https://www.holidaylandmark.com/trips">Trips</a>
                            </li>
                            <li>
                                <a class="lgx-scroll" href="https://www.holidaylandmark.com/events/">Events</a>
                            </li>
                            {{-- Common Header --}}
                            {{-- categories menu items --}}
                            @php $categoriesMenu = categoriesMenu() @endphp
                            @if(!empty($categoriesMenu))
                            <li>
                                <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-stream"></i> @lang('eventmie-pro::em.categories') <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu multi-level">
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
                                    <i class="fas fa-th"></i> @lang('eventmie-pro::em.more') <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu multi-level">
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
                            <li>
                                <a class="lgx-scroll" href="https://www.holidaylandmark.com/india/index.html">India</a>
                            </li>

                            <li>
                                <a class="lgx-scroll" href="https://www.holidaylandmark.com/blog/">Blogs</a>
                            </li>
                            @guest






                            @else



                          

                            {{-- If user is admin then show admin panel link --}}


                            {{-- If user is organiser then show create event link (only if multi-vendor is on) --}}




                            {{-- If user is customer then show my bookings link --}}
                            @if(Auth::user()->hasRole('customer'))
                            <li>
                                <a class="lgx-scroll" href="{{ route('eventmie.mybookings_index') }}">
                                    <i class="fas fa-money-check-alt"></i> @lang('eventmie-pro::em.mybookings')</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href='#'><i class="fas fa-calendar-alt"></i>
                                    @lang('eventmie-pro::em.manage_trips')</a>
                            </li>
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

                            <li>
                                <a class="lgx-scroll"
                                    href="https://www.holidaylandmark.com/cookbooks/">Cookbooks</a>
                            </li>

                            <li>
                                <a class="lgx-scroll" href="https://www.youtube.com/user/HolidayLandmark">Video</a>
                            </li>
                            <li>
                                <a class="lgx-scroll" href="">Create</a>

                                <ul class="dropdown-menu">

                                    <li>
                                        <a class="lgx-scroll" href='{{eventmie_url("touroperator/my-trips")}}'>Add
                                            Trip</a>
                                    </li>

                                    <li>
                                        <a class="lgx-scroll" href="{{ route('eventmie.myevents_form') }}">Add Event</a>
                                    </li>
                                    <li>
                                        <a class="lgx-scroll" href="https://www.holidaylandmark.com/events/login">Add
                                            Blog Post</a>
                                    </li>
                                </ul>
                            </li>
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
                                    <li>
                                        <a class="dropdown-item" href='#'><i class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_trips')</a>
                                    </li>

                                    @endif

                                    @if(Auth::user()->hasRole('organiser'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i
                                                class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.myevents_index') }}"><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_events')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.obookings_index') }}"><i
                                                class="fas fa-money-check-alt"></i>
                                            @lang('eventmie-pro::em.manage_bookings')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.event_earning_index') }}"><i
                                                class="fas fa-wallet"></i> @lang('eventmie-pro::em.manage_earning')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href='{{eventmie_url("touroperator/dashboards")}}'><i
                                                class="fas fa-calendar-alt"></i>
                                            @lang('eventmie-pro::em.manage_trips')</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('eventmie.tags_form') }}"><i
                                                class="fas fa-user-tag"></i> @lang('eventmie-pro::em.manage_tags')</a>
                                    </li>
                                    <li>
                                        <a class="lgx-scroll" href='{{eventmie_url("touroperator/my-trips")}}'>
                                            <i class="fas fa-qrcode"></i> @lang('eventmie-pro::em.create_trips')</a>
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
                            
                        </ul>
                    </div>
                </nav>
            </div>

        </div>
    </div>
</header>