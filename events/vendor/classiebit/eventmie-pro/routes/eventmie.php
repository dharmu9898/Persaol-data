<?php

use Classiebit\Eventmie\Facades\Eventmie;

/*
|
| Package Routes
|
*/

/* Eventmie-pro package namespace */
$namespace = !empty(config('eventmie.controllers.namespace')) ? '\\'.config('eventmie.controllers.namespace') : '\Classiebit\Eventmie\Http\Controllers';

/* Localization */
Route::get('/assets/js/eventmie_lang', function () {
    // user lang
    if(session('my_lang'))
        \App::setLocale(session('my_lang'));
    
    $strings['em'] = \Lang::get('eventmie-pro::em');
        
    header('Content-Type: text/javascript; charset=UTF-8');
    echo('window.i18n = ' . json_encode($strings) . ';');
    
    exit();
})->name('eventmie.eventmie_lang');

// Lang selector
Route::get('/lang/{lang?}', $namespace.'\EventmieController@change_lang')->name('eventmie.change_lang');

// Package Asset
Route::get('frontend-assets', $namespace.'\EventmieController@assets')->name('eventmie.eventmie_assets');


/* Auth */
Auth::routes();

// Login
Route::get('login', $namespace.'\Auth\LoginController@showLoginForm')->name('eventmie.login');
Route::post('login', $namespace.'\Auth\LoginController@login')->name('eventmie.login_post');

// Logout
Route::match(['get', 'post'], '/logout', $namespace.'\EventmieController@logout')->name('eventmie.logout');

// Registration
Route::get('register', $namespace.'\Auth\RegisterController@showRegistrationForm')->name('eventmie.register_show');
Route::post('register', $namespace.'\Auth\RegisterController@register')->name('eventmie.register')->middleware(Spatie\Honeypot\ProtectAgainstSpam::class);
Route::get('register/activate-user/{name}/{activate}/{validToken}', $namespace.'\Auth\RegisterController@showProfileForm')->name('eventmie.register_activates');
Route::post('activate', $namespace.'\Auth\RegisterController@submitregisteration')->name('eventmie.register_activate');
Route::post('updatepro', $namespace.'\Auth\RegisterController@submitprofile')->name('eventmie.update_profile');



// Forgot password
Route::get('password/reset',  $namespace.'\Auth\ForgotPasswordController@showLinkRequestForm')->name('eventmie.password.request');
Route::post('password/email', $namespace.'\Auth\ForgotPasswordController@sendResetLinkEmail')->name('eventmie.password.email');
Route::get('forgot/password/reset/{token}', $namespace.'\Auth\ResetPasswordController@showResetForm')->name('eventmie.password.reset');
Route::post('forgot/password/reset/post',   $namespace.'\Auth\ResetPasswordController@reset')->name('eventmie.password.reset_post');

// Email Verify
Route::get('email/verify',  $namespace.'\Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}',  $namespace.'\Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend',  $namespace.'\Auth\VerificationController@resend')->name('verification.resend');


/* Voyager */
Route::group([
    'namespace' => $namespace.'\Voyager',
    'prefix' => config('eventmie.route.prefix').'/'.config('eventmie.route.admin_prefix'),
], function () use ($namespace) {
    $controller     = $namespace.'\Voyager\DashboardController';
    
    \Voyager::routes();
    
    /* Override Voyager Default Routes */
    Route::get('/', "$controller@index")->name('voyager.dashboard');  
    Route::post('sales/report', "$controller@sales_report")->name('voyager.sales_report');
    Route::post('export/sales/report', "$controller@export_sales_report")->name('voyager.export_sales_report');
    Route::post('event/total/sales_price', "$controller@EventTotalBySalesPrice")->name('voyager.event_total_by_sales_price');
    Route::post('get/event', "$controller@getEvent")->name('voyager.get_event');

    // Override menus route
   

   
});

  
/* Eventmie-pro package routes */
Route::group([
    'prefix' => config('eventmie.route.prefix'),
    'as'    => 'eventmie.',
    'middleware' => ['verified'],
], function() use($namespace) {

    /* Welcome */
    Route::get('/', $namespace."\WelcomeController@index")->name('welcome');

    Route::get('/home', function() {
        return redirect()->route('eventmie.welcome');
    });
    
    /* Tags */
    Route::prefix('/mytags')->group(function () use ($namespace)  {
        $controller = $namespace.'\TagsController';

        Route::get('/', "$controller@form")->name('tags_form');

        // API
        Route::post('/api', "$controller@tags")->name('tags');
        Route::post('/api/add', "$controller@store")->name('tags_store');
        Route::post('/api/delete', "$controller@delete")->name('tags_delete');
        Route::post('/api/selected/tags', "$controller@selected_event_tags")->name('selected_tags');
        Route::post('/api/search/tags', "$controller@search_tags")->name('search_tags');
    });
    
    /* Tickets */
    Route::prefix('/tickets')->group(function () use ($namespace)  {
        $controller = $namespace.'\TicketsController';

        // API
        Route::post('/api', "$controller@tickets")->name('tickets');
        Route::get('/api/taxes', "$controller@taxes")->name('tickets_taxes');
        Route::post('/api/store', "$controller@store")->name('tickets_store');   
        Route::post('/api/delete', "$controller@delete")->name('tickets_delete');
    });
    
    /* Schedules */
    Route::prefix('/schedules')->group(function () use ($namespace)  {
        $controller = $namespace.'\SchedulesController';

        // API
        Route::post('/api', "$controller@schedules")->name('schedules');
        Route::post('/api/event_schedule', "$controller@event_schedule")->name('event_schedule');
    });
    
 
    /* Events */
    Route::prefix('/allevents')->group(function () use ($namespace) {
        $controller = $namespace.'\EventsController';
        $tourcontroller = $namespace.'\TourDetailController';
        Route::get('/', "$controller@index")->name('events_index');
        
        // Wildcard
        Route::get('/{event}', "$controller@show")->name('events_show');
        Route::get('/{event}/tag/{tag_title}', "$controller@tag")->name('events_tags');
        
        // API
        Route::get('/api/getcategory', "$tourcontroller@getcategory")->name('mytrips_categories');
    
       
        Route::get('/api/get_events', "$controller@events")->name('events');
        Route::get('/api/categories', "$controller@categories")->name('myevents_categories');
        Route::post('/api/check/session', "$controller@check_session")->name('check_session');
    });
    
    /* Bookings */
    Route::prefix('/bookings')->group(function () use ($namespace)  {
        $controller = $namespace.'\BookingsController';

        // Paypal Checkout
        Route::match(['get', 'post'], '/paypal/callback', "$controller@paypal_callback")->name('bookings_paypal_callback');  

        // Redirect back to event
        Route::get('/login-first', "$controller@login_first")->name('login_first');
        Route::get('/signup-first', "$controller@signup_first")->name('signup_first');

        // API
        Route::post('/api/get_tickets', "$controller@get_tickets")->name('bookings_get_tickets');
        Route::post('/api/book_tickets', "$controller@book_tickets")->name('bookings_book_tickets');
    });
    
    /* My Bookings (customers) */
    Route::prefix('/mybookings')->group(function () use($namespace) {
        $controller = $namespace.'\MyBookingsController';

        Route::get('/', "$controller@index")->name('mybookings_index');
        
        // API
        Route::get('/api/get_mybookings', "$controller@mybookings")->name('mybookings');
        Route::post('/api/cancel', "$controller@cancel")->name('mybookings_cancel');
    });
    
    /* My Bookings (organizer) */
    Route::prefix('/bookings')->group(function () use ($namespace)  {
        $controller = $namespace.'\OBookingsController';
        
        Route::get('/', "$controller@index")->name('obookings_index');  
        Route::get('/booking/{id}', "$controller@organiser_bookings_show")->name('obookings_organiser_bookings_show');     
        Route::get('/delete/{id}', "$controller@delete_booking")->name('obookings_organiser_booking_delete'); 
        
        // API
        Route::get('/api/organiser_bookings', "$controller@organiser_bookings")->name('obookings_organiser_bookings');
        Route::post('/api/organiser_bookings_edit', "$controller@organiser_bookings_edit")->name('obookings_organiser_bookings_edit');

        Route::post('/api/booking_customers', "$controller@get_customers")->name('get_customers');
    });
    
    /* My Earnings (organiser) */
    Route::prefix('/myearning')->group(function () use ($namespace)  {
        $controller     = $namespace.'\MyEarningsController';
        
        Route::get('/', "$controller@index")->name('event_earning_index');
        Route::get('/organiser/earning', "$controller@organiser_event_earning")->name('organiser_event_earning');
        Route::get('/organiser/total/earning', "$controller@organiser_total_earning")->name('organiser_total_earning');
    });
    
    /* My Events (organiser) */
    Route::prefix('/myevents')->group(function () use ($namespace) {
        $controller = $namespace.'\MyEventsController';
        
        Route::get('/', "$controller@index")->name('myevents_index');
        Route::get('/manage/{slug?}', "$controller@form")->name('myevents_form');  
        Route::get('/delete/{slug}', "$controller@delete_event")->name('delete_event');
        Route::get('/export_attendees/{slug}', "$controller@export_attendees")->name('export_attendees');
        Route::get('/get_myevents', "$controller@get_myevents")->name('myevents'); 
        Route::get('/api/get_all_myevents', "$controller@get_all_myevents")->name('all_myevents');
        Route::post('/api/store', "$controller@store")->name('myevents_store');
        Route::post('/api/store_media', "$controller@store_media")->name('myevents_store_media');
        Route::post('/api/store_location', "$controller@store_location")->name('myevents_store_location');
        Route::post('/api/store_timing', "$controller@store_timing")->name('myevents_store_timing');
        Route::post('/api/store_event_tags', "$controller@store_event_tags")->name('myevents_store_event_tags');
        Route::post('/api/store_seo', "$controller@store_seo")->name('myevents_store_seo');
        Route::get('/api/countries', "$controller@countries")->name('myevents_countries'); 
        Route::get('/api/mycountries', "$controller@mycountries")->name('mytrips_countries'); 
      
        Route::get('/api/mystates', "$controller@mystates")->name('mytrips_states'); 
        Route::get('/api/mycities', "$controller@mycities")->name('mytrips_cities'); 
        Route::get('/api/tripdays', "$controller@tripdays")->name('trip_days'); 
        Route::post('/api/get_myevent', "$controller@get_user_event")->name('get_myevent');
        Route::post('/api/publish_myevent', "$controller@event_publish")->name('publish_myevent');

        Route::post('/api/myevent_organizers', "$controller@get_organizers")->name('get_organizers');

        //delete multiple images
        Route::post('delete/image', "$controller@delete_image")->name('delete_image');
    });
    Route::prefix('/mytour')->group(function () use ($namespace)  {
        $controller = $namespace.'\MyEventsController';
        $tourcontroller = $namespace.'\TourDetailController';  
        Route::get('/', "$controller@tripindex")->name('trips_index');
        Route::post('/api/get_myiternary', "$controller@getiternary")->name('getiternary');
        Route::get('/api/get_mytrips', "$controller@get_mytrips")->name('mytour'); 
        Route::get('/api/get_date', "$controller@get_date")->name('get_date');
        Route::post('/api/store', "$tourcontroller@storetrip")->name('mytrip_store');
      
       
        Route::post('/api/store_media', "$controller@storetrip_media")->name('mytrips_store_media');
        Route::post('/api/iternary_delete', "$tourcontroller@iternary_delete")->name('iternary_delete');
        Route::post('/api/store_time', "$tourcontroller@storetrip_time")->name('mytrip_store_time');
        Route::post('/api/store_iternary', "$tourcontroller@storetrip_iternary")->name('mytrip_store_iternary');
        Route::post('/api/store_location', "$tourcontroller@storetrip_location")->name('mytrip_store_location');
        Route::get('/api/getdetil/{slug}', "$tourcontroller@gettrip")->name('mytrip_getdetail');
        Route::get('/manage/{slug?}', "$controller@tripform")->name('mytrips_form');
        Route::post('/api/publish_mytrip', "$controller@trip_publish")->name('publish_mytrip');
    }); 
    /* Notification */
    Route::prefix('/notifications')->group(function () use ($namespace)  {
        
        // read notification
        Route::get('/read/{n_type}', function($n_type) {
            if($n_type) {
                $id   = \Auth::id();
                $user = \Classiebit\Eventmie\Models\User::find($id);
                $user->unreadNotifications->where('n_type', $n_type)->markAsRead();
            }
            
            // Admin: redirect to admin-panel
            if(\Auth::user()->hasRole('admin')) {
                if($n_type == "user")
                    return redirect()->route('voyager.users.index');
                else if($n_type == "bookings" || $n_type == "cancel")
                    return redirect()->route('voyager.bookings.index');
                else if($n_type == "events")
                    return redirect()->route('voyager.events.index');
                else
                    return redirect()->route('voyager.dashboard');
            }

            // Organizer: redirect to notification related page
            if(\Auth::user()->hasRole('organiser')) {
                // create events notification
                if($n_type == "events")
                    return redirect()->route('eventmie.myevents_index');
                
                // create booking notification
                if($n_type == "bookings" || $n_type == "cancel" )
                    return redirect()->route('eventmie.obookings_index');    
            }

            // Customer: redirect to notification related page
            if(\Auth::user()->hasRole('customer')) {
                // create events notification
                if($n_type == "user")
                    return redirect()->route('eventmie.profile');
                
                // create booking notification
                if($n_type == "bookings" || $n_type == "cancel" )
                    return redirect()->route('eventmie.mybookings_index');    
            }
            
            // Default: redirect to homepage
            return redirect()->route('eventmie.welcome');
        })->name('notify_read');
        
    });
   
    /* Profile */
    Route::prefix('/profile')->group(function () use ($namespace) {
        $controller = $namespace.'\ProfileController';
        
        Route::get('/', "$controller@index")->name('profile');
        Route::post('/updateAuthUser',"$controller@updateAuthUser")->name('updateAuthUser');
        Route::post('/updateAuthUserRole',"$controller@updateAuthUserRole")->name('updateAuthUserRole');
    });
    
    /* Blogs */
    Route::prefix('/blogs')->group(function () use ($namespace) {
        $controller = $namespace.'\BlogsController';
        
        Route::get('/', "$controller@get_posts")->name('get_posts');
        
        // Wildcard
        Route::get('/{slug}',"$controller@view")->name('post_view');
    });
    
    /* Contact */
    Route::prefix('/contact')->group(function () use ($namespace) {
        $controller = $namespace.'\ContactController';
        
        Route::get('/', "$controller@index")->name('contact');
        Route::post('/save', "$controller@store_contact")->name('store_contact')->middleware(Spatie\Honeypot\ProtectAgainstSpam::class);
    });

    /* OAuth login */
  Route::get('/login/{social}', $namespace.'\Auth\LoginController@socialLogin')->where('social', 'facebook|google')->name('oauth_login');
    Route::get('/login/{social}/callback', $namespace.'\Auth\LoginController@handleProviderCallback')->where('social', 'facebook|google')->name('oauth_callback');
    Route::get('auth/{provider}', 'Auth\RegisterController@redirectToProvider');
    Route::get('auth/{provider}/callback', $namespace.'\Auth\LoginController@handleProviderCallbacks');
    
  
    /* Download Ticket */
    Route::prefix('/download')->group(function () use ($namespace)  {
        $controller = $namespace.'\DownloadsController';
        
        Route::get('/ticket/{id}/{order_number}', "$controller@index")->name('downloads_index');  
    });
    Route::prefix('/touroperator')->group(function () use ($namespace) {
   
        $controller = $namespace.'\TourDashboardController';
        $tourcontroller = $namespace.'\TourDetailController';  
        $profilecontroller = $namespace.'\AdminProfileController';  
        Route::get('dashboards', "$controller@index")->name('dashboards');
        Route::get('Iternary', "$controller@iternary")->name('Iternary');
        Route::get('Image', "$controller@image")->name('Image');
        Route::get('addtrip', "$controller@trip")->name('addtrip');
        Route::get('detailUsers/{id}',"$tourcontroller@getuserdetail");
        Route::get('iternary_day/{iternary_title}',"$tourcontroller@getiternary_day");
        
        Route::get('alldetailUsers/{id}',"$tourcontroller@allgetuserdetail");
        Route::get('toursdetail/{email}',"$tourcontroller@gettrip");
        Route::get('my-iternary', "$controller@iternary")->name('my-iternary');
        Route::get('my-image', "$controller@image")->name('my-image');
        Route::get('my-trips', "$controller@trip")->name('my-trips');
        Route::get('publishtrip/{id}', "$tourcontroller@publishtrip")->name('publishtrip');
        Route::get('unpublishtrip/{id}', "$tourcontroller@unpublishtrip")->name('unpublishtrip');
        
        Route::get('activate-event-user/{CoursesName?}/{email}/{validToken}',"$profilecontroller@storeprofile");
    
        Route::get('usersdetail/{email}',"$tourcontroller@getusers");
        Route::post('addtour/{email}', "$tourcontroller@addtrip");
        Route::get('iternarydetail/{email}',"$tourcontroller@getiternary");
        Route::post('additernary/{email}', "$tourcontroller@addsiternary");
    
        Route::get('imagedetail/{email}',"$tourcontroller@getimage");
        Route::post('addimage/{email}', "$tourcontroller@addsimage");
    
        Route::get('destroytrip/{id}', "$tourcontroller@destroytrips")->name('destroytrip');
        Route::get('/detailtrip/{id}', "$tourcontroller@detailtrips")->name('detailtrip');
        Route::get('/edittrip/{id}', "$tourcontroller@edittrips")->name('edittrip');
        Route::post('/updatetrip', "$tourcontroller@updatetrip")->name('updatetrip');
       
        Route::post('/replishubtrip', "$tourcontroller@repubsTrip")->name('replishubtrip');
    
        Route::get('destroyiter/{id}', "$tourcontroller@destroyiternary")->name('destroyiter');
        Route::get('/detailiter/{id}', "$tourcontroller@detailiternary")->name('detailiter');
        Route::get('/edititer/{id}', "$tourcontroller@edititernary")->name('edititer');
        Route::post('/updateiternary', "$tourcontroller@updateiterna")->name('updateiternary');
        Route::get('destroyimage/{id}', "$tourcontroller@destroyimages")->name('destroyimage');
        Route::get('detailimage/{id}', "$tourcontroller@detailimages")->name('detailimage');
        Route::get('editimage/{id}', "$tourcontroller@editimages")->name('editimage');
        Route::post('/updateimage', "$tourcontroller@updateimages")->name('updateimage');
        Route::post('listtour/{email}', "$profilecontroller@addsublist")->name('listtour');
        Route::get('subscriberdetail/{id}',"$tourcontroller@getlist");
        Route::post('addcategories/{email}', "$tourcontroller@addcategories")->name('addcategories');
        Route::get('my-category', "$controller@category")->name('my-category'); 
    });
   
    
    Route::prefix('/admins')->group(function () use ($namespace) {
       
        $controller = $namespace.'\AdminController';
        $admincontroller = $namespace.'\AdminTPController';  
        $profilescontroller = $namespace.'\AdminProfilesController';  
       
    
        Route::get('dashboard', "$admincontroller@index")->name('dashboard');
        Route::get('tourcategories/{email}',"$controller@categories")->name('tourcategories');;
        Route::get('addcategory', "$admincontroller@category")->name('addcategory');
        Route::post('addcategories/{email}', "$controller@addcategories")->name('addcategories');
        Route::get('/deletecategories/{id}', "$controller@destroy")->name('deletecategories');
        Route::get('/editcat/{id}', "$controller@editcat")->name('editcat');
        Route::post('/updatecategories', "$controller@updatecategories")->name('updatecategories');
        Route::get('/detailcategories/{id}', "$controller@detailcategories")->name('detailcategories');
        Route::get('internationaltour', "$admincontroller@international")->name('internationaltour');
        Route::post('addinternational/{email}', "$controller@addinternational")->name('addinternational');
        Route::get('tourinternational/{email}',"$controller@international");
        Route::get('/editinter/{id}', "$controller@editinter")->name('editinter');
        Route::post('/updateinternal', "$controller@updateinterna")->name('updateinternal');
        Route::get('/deleteinternaltour/{id}', "$controller@destroyinter")->name('deleteinternaltour');
        Route::get('/detailinternal/{id}', "$controller@detailinternal")->name('detailinternal');
        Route::get('statestour', "$admincontroller@states")->name('statestour');
        Route::post('addstatestour/{email}', "$controller@addstatestour")->name('addstatestour');
        Route::get('tourstates/{email}',"$controller@states");
        Route::get('/editstate/{id}', "$controller@editstate")->name('editstate');
        Route::post('/updatestate', "$controller@updatestates")->name('updatestate');
        Route::get('/deletestatetour/{id}', "$controller@destroystate")->name('deletestatetour');
        Route::get('/detailstates/{id}', "$controller@detailstates")->name('detailstates');
        Route::get('citiestour', "$admincontroller@cities")->name('citiestour');
        Route::post('addcitytour/{email}', "$controller@addcitytour")->name('addcitytour');
        Route::get('tourcity/{email}',"$controller@city");
        Route::get('/editcity/{id}', "$controller@editcity")->name('editcity');
        Route::post('/updatecity', "$controller@updatecitys")->name('updatecity');
        Route::get('/deletecitytour/{id}', "$controller@destroycity")->name('deletecitytour');
        Route::get('/detailcity/{id}', "$controller@detailcity")->name('detailcity');
        Route::get('tourprofile', "$admincontroller@profiles")->name('tourprofile');
        Route::get('toursprofile/{email}',"$profilescontroller@tourprofile");
        Route::post('addprofile/{email}', "$profilescontroller@addprofile")->name('addprofile');
        Route::get('/profileedit/{id}', "$profilescontroller@editprofile")->name('profileedit');
        Route::post('/updateprofile',"$profilescontroller@updateprofiles")->name('updateprofile');
        Route::get('/deleteprofile/{id}', "$profilescontroller@destroyprofile")->name('deleteprofile');
        Route::get('/mytrip', "$admincontroller@tourdetails")->name('mytrip');
        Route::get('toursdetail/{email}',"$controller@gettrip");
        Route::get('/permission', "$admincontroller@tourpermission")->name('permission');
        Route::post('permissiontour/{email}', "$controller@addpermission")->name('permissiontour');
        Route::get('/detailprofile/{id}', "$profilescontroller@detailprofiles")->name('detailprofile');
        Route::get('/list', "$admincontroller@subscriberlist")->name('list');

        Route::get('userdetail/{email}',"$controller@getuser")->name('userdetail');
    Route::post('upermissiontour/{email}', "$controller@addupermission")->name('upermissiontour');
    Route::get('/userpermission', "$admincontroller@webuserpermission")->name('userpermission');
    });
      
    /* Commission */
    Route::post('/commission/update', $namespace.'\Voyager\CommissionsController@commission_update')->name('commission_update');
    Route::post('/commission/settlement_update', $namespace.'\Voyager\CommissionsController@settlement_update')->name('settlement_update');

    /* QrCode Scanner */
    Route::prefix('/ticket-scan')->group(function () use ($namespace)  {
        $controller = $namespace.'\TicketScanController';
       
        Route::get('/', "$controller@index")->name('ticket_scan');
        Route::post('/verify-ticket', "$controller@verify_ticket")->name('verify_ticket');
        Route::post('/get-booking', "$controller@get_booking")->name('get_booking');
    });

    /* Send Email */
    Route::get('/send/email', $namespace.'\SendEmailController@send_email')->name('send_email');

    /* Organiser Dashboard */
    Route::prefix('/dashboard')->group(function () use ($namespace)  {
        $controller     = $namespace.'\ODashboardController';
        
        Route::get('/organizer', "$controller@index")->name('organizer_dashboard');
        Route::post('sales/report', "$controller@sales_report")->name('sales_report');
        Route::post('export/sales/report', "$controller@export_sales_report")->name('export_sales_report');

        Route::post('event/total/sales_price', "$controller@EventTotalBySalesPrice")->name('event_total_by_sales_price');

        Route::post('get/event', "$controller@getEvent")->name('get_event');
    });

    /* ============================= ALL OTHER ROUTES ABOVE ============================= */
    /* Wildcard routes (add all other routes above) */
    /* Static Pages */
    Route::get('/{page}', $namespace."\PagesController@view")->name('page'); 
    /* ============================= NO ROUTES BELOW THIS ============================= */


});