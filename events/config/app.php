<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Anhskohbo\NoCaptcha\NoCaptchaServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Laravel\Passport\PassportServiceProvider::class,
        Laravel\Socialite\SocialiteServiceProvider::class,
        

    ],

    'HOLIDAY_M_BASE_URL' => env('HOLIDAY_M_BASE_URL', 'not found in .env file'),
    'HOLIDAY_M_OAUTH_TOKEN_URL' => env('HOLIDAY_M_OAUTH_TOKEN_URL', 'not found in .env file'),
    'HOLIDAY_M_GRANT_TYPE' => env('HOLIDAY_M_GRANT_TYPE', 'not found in .env file'),
    'HOLIDAY_M_CLIENT_ID' => env('HOLIDAY_M_CLIENT_ID', 'not found in .env file'),
    'HOLIDAY_M_CLIENT_SECRET' => env('HOLIDAY_M_CLIENT_SECRET', 'not found in .env file'),
    'GET_MYHOLIDAY_URL' => env('GET_MYHOLIDAY_URL', 'not found in .env file'),
    'ADD_MYHOLIDAY_URL' => env('ADD_MYHOLIDAY_URL', 'not found in .env file'),
    'GET_MYHOLIDAY_EDITCATEGORIES' => env('GET_MYHOLIDAY_EDITCATEGORIES', 'not found in .env file'),
    'DELETE_CAREGORY_URL' => env('DELETE_CAREGORY_URL', 'not found in .env file'),
    'EDIT_CAREGORY_URL' => env('EDIT_CAREGORY_URL', 'not found in .env file'),
    'UPDATE_CATEGORY_URL' => env('UPDATE_CATEGORY_URL', 'not found in .env file'),
    'DETAIL_CAREGORY_URL' => env('DETAIL_CAREGORY_URL', 'not found in .env file'),
   // STATE COMMANT 
    'ADD_STATES_TOUR_URL' => env('ADD_STATES_TOUR_URL', 'not found in .env file'),
    'GET_MYSTATES_URL' => env('GET_MYSTATES_URL', 'not found in .env file'),
    'EDIT_STATE_URL' => env('EDIT_STATE_URL', 'not found in .env file'),
    'UPDATE_STATES_URL' => env('UPDATE_STATES_URL', 'not found in .env file'),
    'DELETE_STATES_URL' => env('DELETE_STATES_URL', 'not found in .env file'),
    'DETAIL_STATES_URL' => env('DETAIL_STATES_URL', 'not found in .env file'),
// CITY COMMENT
    'ADD_CITY_TOUR_URL' => env('ADD_CITY_TOUR_URL', 'not found in .env file'),
    'GET_MYCITY_URL' => env('GET_MYCITY_URL', 'not found in .env file'),
    'EDIT_CITY_URL' => env('EDIT_CITY_URL', 'not found in .env file'),
    'UPDATE_CITY_URL' => env('UPDATE_CITY_URL', 'not found in .env file'),
    'DELETE_CITY_URL' => env('DELETE_CITY_URL', 'not found in .env file'),
    'DETAIL_CITY_URL' => env('DETAIL_CITY_URL', 'not found in .env file'),
// INTERNATION COMMANT
    'ADD_INTERNATIONAL_URL' => env('ADD_INTERNATIONAL_URL', 'not found in .env file'),
    'GET_MYINTERNATIONAL_URL' => env('GET_MYINTERNATIONAL_URL', 'not found in .env file'),
    'EDIT_INTERNA_URL' => env('EDIT_INTERNA_URL', 'not found in .env file'),
    'UPDATE_INTERNA_URL' => env('UPDATE_INTERNA_URL', 'not found in .env file'),
    'DELETE_INTERNAL_URL' => env('DELETE_INTERNAL_URL', 'not found in .env file'),
    'DETAIL_INTERNAL_URL' => env('DETAIL_INTERNAL_URL', 'not found in .env file'),

// Tour Opertor
    'HOLIDAY_CATEGORY_GET' => env('HOLIDAY_CATEGORY_GET', 'not found in .env file'),

    'DETAIL_INTERNAL_URL' => env('DETAIL_INTERNAL_URL', 'not found in .env file'),
    'HOLIDAY_CATEGORY_GET' => env('HOLIDAY_CATEGORY_GET', 'not found in .env file'),
    'GET_MYHOLIDAYTRIP_URL' => env('GET_MYHOLIDAYTRIP_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYTRIP_URL' => env('ADD_MYHOLIDAYTRIP_URL', 'not found in .env file'),

    'GET_MYHOLIDAYITERNARY_URL' => env('GET_MYHOLIDAYITERNARY_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYITERNARY_URL' => env('ADD_MYHOLIDAYITERNARY_URL', 'not found in .env file'),   
    'GET_MYHOLIDAYIMAGE_URL' => env('GET_MYHOLIDAYIMAGE_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYIMAGE_URL' => env('ADD_MYHOLIDAYIMAGE_URL', 'not found in .env file'),  
    'GET_ALLHOLIDAYTRIP_URL' => env('GET_ALLHOLIDAYTRIP_URL', 'not found in .env file'), 
    'PERMIT_TRIP_URL' => env('PERMIT_TRIP_URL', 'not found in .env file'), 

    'DELETE_TRIP_URL' => env('DELETE_TRIP_URL', 'not found in .env file'), 
    'DETAIL_TRIP_URL' => env('DETAIL_TRIP_URL', 'not found in .env file'), 
    'EDIT_TRIP_URL' => env('EDIT_TRIP_URL', 'not found in .env file'), 
    'UPDATE_TRIP_URL' => env('UPDATE_TRIP_URL', 'not found in .env file'), 

    'DELETE_ITERNARY_URL' => env('DELETE_ITERNARY_URL', 'not found in .env file'), 
    'DETAIL_ITERNARY_URL' => env('DETAIL_ITERNARY_URL', 'not found in .env file'), 
    'EDIT_ITERNARY_URL' => env('EDIT_ITERNARY_URL', 'not found in .env file'), 
    'UPDATE_ITERNARY_URL' => env('UPDATE_ITERNARY_URL', 'not found in .env file'), 
    'DELETE_IMAGES_URL' => env('DELETE_IMAGES_URL', 'not found in .env file'), 
    'DETAIL_IMAGES_URL' => env('DETAIL_IMAGES_URL', 'not found in .env file'), 
    'EDIT_IMAGES_URL' => env('EDIT_IMAGES_URL', 'not found in .env file'), 
    'UPDATE_IMAGE_URL' => env('UPDATE_IMAGE_URL', 'not found in .env file'),
    'GET_MYPROFILE_URL' => env('GET_MYPROFILE_URL', 'not found in .env file'),
    'GET_MYHOLIDAYLIST_URL' => env('GET_MYHOLIDAYLIST_URL', 'not found in .env file'),
    'GET_MYHOLIDAYUSERS_URL' => env('GET_MYHOLIDAYUSERS_URL', 'not found in .env file'),
    'DETAIL_SUBSCRIBER_URL' => env('DETAIL_SUBSCRIBER_URL', 'not found in .env file'),
    'DETAIL_ALLSUBSCRIBER_URL' => env('DETAIL_ALLSUBSCRIBER_URL', 'not found in .env file'),
    'PUBLISH_TRIP_URL' => env('PUBLISH_TRIP_URL', 'not found in .env file'),
    'UNPUBLISH_TRIP_URL' => env('UNPUBLISH_TRIP_URL', 'not found in .env file'),
    'REPUB_TRIP_URL' => env('REPUB_TRIP_URL', 'not found in .env file'),
    'REPUB_TRIP_URL' => env('REPUB_TRIP_URL', 'not found in .env file'),
    'GET_MYITERNARY_URL' => env('GET_MYITERNARY_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYCATEGORY_URL' => env('ADD_MYHOLIDAYCATEGORY_URL', 'not found in .env file'),

    'GET_ALLHOLIDAYUSER_URL' => env('GET_ALLHOLIDAYUSER_URL', 'not found in .env file'),
    'PERMIT_USER_URL' => env('PERMIT_USER_URL', 'not found in .env file'),
    'GET_CATEGORY_URL' => env('GET_CATEGORY_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYDETAIL_URL' => env('ADD_MYHOLIDAYDETAIL_URL', 'not found in .env file'),
    'DETAIL_TRIP_URL' => env('DETAIL_TRIP_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYTIMING_URL' => env('ADD_MYHOLIDAYTIMING_URL', 'not found in .env file'),
    'GET_MYCOUNTRY_URL' => env('GET_MYCOUNTRY_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYLOCATION_URL' => env('ADD_MYHOLIDAYLOCATION_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYITERNARYDAYS_URL' => env('ADD_MYHOLIDAYITERNARYDAYS_URL', 'not found in .env file'),
    'DELETE_MYHOLIDAYITERNARYDEL_URL' => env('DELETE_MYHOLIDAYITERNARYDEL_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYMEDIA_URL' => env('ADD_MYHOLIDAYMEDIA_URL', 'not found in .env file'),
    'ADD_MYHOLIDAYTICKET_URL' => env('ADD_MYHOLIDAYTICKET_URL', 'not found in .env file'),
    'DELETE_MYHOLIDAYTICKETDEL_URL' => env('DELETE_MYHOLIDAYTICKETDEL_URL', 'not found in .env file'),
   
    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'socialite' => Laravel\Socialite\Facades\Socialite::class,

    ],

];
