<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use Redirect;
use File;
use Classiebit\Eventmie\Models\Booking;
use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Mail\SendMail;
use Classiebit\Eventmie\Mail\Tripcreate;
use Classiebit\Eventmie\Mail\Tripcreated;
use Classiebit\Eventmie\Models\Mycurrency;
use Classiebit\Eventmie\Models\Discount;
use Classiebit\Eventmie\Models\Discount_title;
use Classiebit\Eventmie\Models\Mytripticket;
use Classiebit\Eventmie\Models\Tripcategory;
use Classiebit\Eventmie\Models\Ticket;
use Classiebit\Eventmie\Models\Myticket;
use Classiebit\Eventmie\Models\Mydiscount;
use Classiebit\Eventmie\Models\Iternary_day;
use Classiebit\Eventmie\Models\Iternary;
use Classiebit\Eventmie\Models\Trip;
use Classiebit\Eventmie\Models\Mycountry;
use Classiebit\Eventmie\Models\Country;
use Classiebit\Eventmie\Models\State;
use Classiebit\Eventmie\Models\City;
use Classiebit\Eventmie\Models\User;
use DB;
use Classiebit\Eventmie\Models\Category;
use Illuminate\Support\Facades\Mail;
use Classiebit\Eventmie\Models\Countries;
use Classiebit\Eventmie\Models\Schedule;
use Classiebit\Eventmie\Models\Tag;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Log;
use Classiebit\Eventmie\Models\Tax;
use Classiebit\Eventmie\Notifications\MailNotification;


class MyEventsController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // language change
        $this->middleware('common');

        // exclude routes
        $this->middleware('organiser')->except(['delete_event']);
        
        $this->event    = new Event;

        $this->ticket   = new Ticket;

        $this->category = new Category;
        $this->tripcategory = new Tripcategory;
        $this->country  = new Country;
        $this->myticket  = new Myticket;
        $this->countries  = new Mycountry;
        $this->mydiscount  = new Discount;
        $this->mydiscount_title  = new Discount_title;
        $this->insert_discount= new Mydiscount;

        $this->tripticket  = new Mytripticket;
        $this->tripcurrencies  = new Mycurrency;
        $this->iternarydays=new Iternary_day;
        $this->iternary=new Iternary;
        $this->schedule = new Schedule;
        $this->tag      = new Tag;
        $this->trip  = new Trip;
        $this->states  = new State;
        $this->cities  = new City;
        $this->booking  = new Booking;
        
        $this->organiser_id = null;   
    }

    /**
     *  my event for particular ogrganiser
     */

    // only organiser can see own events and admin or customer can't see organiser events 
    public function book($view = 'eventmie::myevents.customer_bookings', $extra = [])
    {
        // get prifex from eventmie config
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');
        // if have booking email data then send booking notification
        $is_success = !empty(session('booking_email_data')) ? 1 : 0;    
        return Eventmie::view($view, compact('path', 'is_success','extra'));
    }
    public function index($view = 'eventmie::myevents.index', $extra = [])
    {
        // get prifex from eventmie config
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');

        // admin can't see organiser bookings
        if(Auth::user()->hasRole('admin'))
        {
            return redirect()->route('voyager.events.index');   
        }
        
        return Eventmie::view($view, compact('path', 'extra'));
    }

    
    public function tripticket_title()
    {
        $ticket_title = $this->tripticket->get_tripticket();
       

        if(empty($ticket_title))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'tickettitle' => $ticket_title ]);
        
    }

    public function tripcurrencies()
    {
        $tripcurrencies = $this->tripcurrencies->get_currencies();
        log::info('mera  $tripcurrencies');
        log::info($tripcurrencies);
        if(empty($tripcurrencies))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'trip_currency' => $tripcurrencies ]);
        
    }
    public function tripindex($view = 'eventmie::mytrip.index', $extra = [])
    {
        log::info('inside  tripindex index function event.index');
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');

        // admin can't see organiser bookings
        if(Auth::user()->hasRole('admin'))
        {
            return redirect()->route('voyager.events.index');   
        }
        
        return Eventmie::view($view, compact('path', 'extra'));
    }

    public function getiternary(Request $request)
    {
        log::info('inside getiternary');
        log::info($request);
        $event_id=$request->event_id;
        $iternary = $this->iternary->get_iternaries($event_id);
        log::info($iternary);
        if(empty($iternary))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'tickets' => $iternary ]);
        
    }

    public function get_mytrips(Request $request)
    {
        if(Auth::user()->hasRole('admin'))
        {
            return redirect()->route('voyager.events.index');   
        }

        $params   = [
            'organiser_id' => Auth::id(),
        ];

        $myevents    = $this->trip->get_my_events($params);

        if(empty($myevents))
            return error(__('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );
        
        return response([
            'myevents'=> $myevents->jsonSerialize(),
        ], Response::HTTP_OK);

    }
    protected function completetrip_step($is_publishable = [], $type = 'detail', $event_id = null)
    {

        log::info('yanha complete_step result hai');
        if(!empty($is_publishable))
            $is_publishable             = json_decode($is_publishable, true);

        $is_publishable[$type]      = 1;
        
        // save is_publishable
        $params     = ['is_publishable' => json_encode($is_publishable)];
        $status     = $this->trip->save_event($params, $event_id);

        return true;
    }
    public function get_date(Request $request)
    {
    
        $params   = [
            'event_id' => $request->event_id,
        ];

        $myevents    = $this->trip->get_my_date($params);
        log::info('mera start date');
         log::info($myevents);
        return response()->json(['status' => true, 'currentdate' => $myevents->start_date]); 

    }

    public function storetrip_media(Request $request)
    {
        // if logged in user is admin
        log::info('sare media aate ahai');
        log::info($request);
       

        $images    = [];
        $poster    = null;
        $thumbnail = null;

        // 1. validate data
        $request->validate([
            'event_id'      => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            'thumbnail'     => 'required',
            'poster'        => 'required',
        ]);

        // vedio link optional so if have vedio ling then validation apply
        if(!empty($request->video_link))
        {
            $request->validate([
                'video_link'    => 'required',
            ]);
        }
        
        $result              = [];
        $trips=  Trip::where('id',$request->event_id)
                   
            ->first();
            $user_id= $trips->user_id;
        // check this event id have login user or not
        $result    = $this->trip->get_user_event($request->event_id, $user_id);

       
    
        // for multiple image
        $path = 'trips/'.Carbon::now()->format('FY').'/';

        // for thumbnail
        if(!empty($_REQUEST['thumbnail'])) 
        { 
            $params  = [
                'image'  => $_REQUEST['thumbnail'],
                'path'   => 'trips',
                'width'  => 512,
                'height' => 512,  
            ];
            $thumbnail   = $this->upload_base64_image($params);
        }

        if(!empty($_REQUEST['poster'])) 
        {
            $params  = [
                'image'  => $_REQUEST['poster'],
                'path'   => 'trips',
                'width'  => 1920,
                'height' => 1080,  
            ];

            $poster   = $this->upload_base64_image($params);
        }
    
        // for image
        if($request->hasfile('images')) 
        { 
            // if have  image and database have images no images this event then apply this rule 
            $request->validate([
                'images'        => 'required',
                'images.*'      => 'mimes:jpeg,png,jpg,gif,svg',
            ]); 
        
            $files = $request->file('images');
    log::info('me rakesh hu');
    log::info($files);
            foreach($files as  $key => $file)
            {
                $extension       = $file->getClientOriginalExtension(); // getting image extension
                $image[$key]     = time().rand(1,988).'.'.$extension;
                $file->storeAs('public/'.$path, $image[$key]);
                log::info('me image j hu');
                $myimage = str_ireplace("tmp", "trip", $file);

                log::info($file); 
                log::info($myimage);
                $images[$key]    = $path.$image[$key];
            }

            
            log::info($images);
        }
        
        $params = [
            "thumbnail"     => !empty($thumbnail) ? $path.$thumbnail : null ,
            "poster"        => !empty($poster) ? $path.$poster : null,
            "video_link"    => $request->video_link,
            "user_id"       =>$user_id,
        ];  

        // if images uploaded
        if(!empty($images))
        {
            if(!empty($result->images))
            {
                $exiting_images = json_decode($result->images, true);

                $images = array_merge($images, $exiting_images);
            }

            $params["images"] = json_encode(array_values($images));

        }    
        
        $status   = $this->trip->save_event($params, $request->event_id);

        if(empty($status))
        {
            return error('Database failure!', Response::HTTP_BAD_REQUEST );
        }

        // get media  related event_id who have created now
        $images   = $this->trip->get_user_event($request->event_id, $user_id);

        // set step complete
        $this->completetrip_step($images->is_publishable, 'media', $request->event_id);

        return response()->json(['images' => $images, 'status' => true]);
    }
    public function visitindex($view = 'eventmie::mytrip.visit', $extra = [])
    {
        log::info('inside  visitindex visit function event.visit');
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');
            
        // admin can't see organiser bookings
        if(Auth::user()->hasRole('admin'))
        {
            return redirect()->route('voyager.events.visit');  
        }
        return Eventmie::view($view, compact('path', 'extra'));
    }
    public function trip_publish(Request $request)
    {
        log::info('yanha event_publish');
        // if logged in user is admin
        $this->is_admin($request);

        $request->validate([
            'event_id'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
        ]);
        $trips=  Trip::where('id',$request->event_id)
                   
            ->first();
            $user_id= $trips->user_id;
            $admin=  User::where('role_id',1)                 
            ->first();
            log::info($admin);
        // check event is valid or not
        $OperatorMailsend = array(  
            'email' => $trips->operator_email,
            'name' => $trips->operator_name,
            'title' => $trips->title,
            'country' => $trips->slug3,
            'state' => $trips->slug2,
            'city' => $trips->slug1,
            'start_date' => $trips->start_date,
            'end_date' => $trips->end_date,
            'days' => $trips->days,
            'cost' => $trips->cost,
         
    );
    $OperatorMailadmin = array(  
        'email' => $admin->email,
        'name' => $trips->operator_name,
        'title' => $trips->title,
        'country' => $trips->slug3,
        'state' => $trips->slug2,
        'city' => $trips->slug1,
        'start_date' => $trips->start_date,
        'end_date' => $trips->end_date,
        'days' => $trips->days,
        'cost' => $trips->cost,
     
);

Mail::to($OperatorMailadmin['email'])->send(new Tripcreated($OperatorMailadmin));
            log::info('after mail if');
    Mail::to($OperatorMailsend['email'])->send(new Tripcreate($OperatorMailsend));
            log::info('after mail if');

        $event    = $this->trip->get_user_event($request->event_id, $user_id);

       

        // check all step is completed or not 
        $is_publishable     =  json_decode($event->is_publishable, true);
        
        if(count($is_publishable) < 6)
            return error(__('eventmie-pro::em.please_complete_steps'), Response::HTTP_BAD_REQUEST );

        // do not unpublish in demo mode
        if(config('voyager.demo_mode'))
        {
            if($event->publish)
                return error('Demo mode', Response::HTTP_BAD_REQUEST );
        }
        
        $params  = [
            'publish'      => $event->publish == 1 ? 0 : 1,
        ];

        $publish_event    = $this->trip->save_event($params, $request->event_id);

        if(empty($publish_event))
        {
            return error('Database failure!', Response::HTTP_BAD_REQUEST );
        }

        return response()->json(['status' => true ]);

    }
    public function insertselect_discount(Request $request)
    {
        log::info('inside insertselect_discount');
          
         log::info('request my insertselect_discount id aa rahA hai');
       
         log::info($request);
        $discountid= $request->my_id;
        $eventsid= $request->event_id;
          $first_discount= $request->first_discount;
        $second_discount= $request->second_discount;
         $discount = $this->insert_discount->insertselect_discount($discountid,$eventsid); 
         $mydiscount = $this->mydiscount_title->get_discountweek_second($discountid,$eventsid,$first_discount,$second_discount); 
        
      
        return response()->json(['status' => true, 'seconddiscountweek' => $mydiscount,'insertdiscount' => $discount ]);
        
    }
    

    
    public function insert_discount(Request $request)
    {
        log::info('inside insert_discount');
          
         log::info('request my insert_discount id aa rahA hai');
       
         log::info($request);
        $discountid= $request->my_id;
        $eventsid= $request->event_id;
        $first_discount= $request->first_discount;
         
        $discountmy = $this->mydiscount_title->get_discountweek($discountid,$eventsid,$first_discount); 
        log::info(' aata hai na ji');
        log::info($discountmy);
         $discount = $this->insert_discount->insert_discount($discountid,$eventsid); 
        
       
        return response()->json(['status' => true, 'seconddiscountweek' => $discountmy,'selectdiscountweek' => $discountmy,'discountweeks' => $discount, ]);
        
    }
    

    public function addsecond_discount(Request $request)
    {
        log::info('inside insert_discount');
                 log::info('request my insert_discount id aa rahA hai');
                log::info($request);
              $eventsid= $request->my_id;
         $trip= Trip::where('id',$eventsid)->first();
       $start_date=$trip->start_date;
      $now= Carbon::now();
       $datetime2 = strtotime($start_date); // convert to timestamps
       $datetime1 = strtotime($now); // convert to timestamps
       $dayss = (int)(($datetime2 - $datetime1)/86400);
       log::info($dayss);
        if(empty($dayss))
        {
            return response()->json(['status' => false]);    
        }
             else if($dayss){
         $discount =  $this->mydiscount_title->get_add_discount($dayss,$eventsid);  
        return response()->json(['status' => true, 'addsecond_discount' => $discount ]);
        
        }
        else{


        }
        
    }
    public function add_discount(Request $request)
    {
        log::info('inside insert_discount');
                 log::info('request my insert_discount id aa rahA hai');
                log::info($request);
              $eventsid= $request->my_id;
         $trip= Trip::where('id',$eventsid)->first();
       $start_date=$trip->start_date;
      $now= Carbon::now();
       $datetime2 = strtotime($start_date); // convert to timestamps
       $datetime1 = strtotime($now); // convert to timestamps
       $dayss = (int)(($datetime2 - $datetime1)/86400);
       log::info($dayss);
        if(empty($dayss))
        {
            return response()->json(['status' => false]);    
        }
             else if($dayss){
         $discount =  $this->mydiscount_title->get_add_discount($dayss,$eventsid);  
        return response()->json(['status' => true, 'add_discount' => $discount ]);
        
        }
        else{


        }
        
    }


    public function mydiscount(Request $request)
    {
        log::info('inside mydiscount');
          
         log::info('request my states id aa rahA hai');
       
         log::info($request);
        $discountid= $request->my_id;
        $eventsid= $request->event_id;
        
         $discount_titles = $this->mydiscount_title->get_discount($discountid,$eventsid);
         
         $trip= Trip::where('id',$eventsid)->first();
         $start_date=$trip->start_date;
        $now= Carbon::now();
         $datetime2 = strtotime($start_date); // convert to timestamps
         $datetime1 = strtotime($now); // convert to timestamps
         $dayss = (int)(($datetime2 - $datetime1)/86400);
         log::info($dayss);
          if(empty($dayss))
          {
              return response()->json(['status' => false]);    
          }
               else if($dayss){
           $discount =  $this->mydiscount_title->get_add_discount($dayss,$eventsid,$discountid);  
          return response()->json(['status' => true, 'add_discount' => $discount,'discountweeks' => $discount_titles ]);
          
          }
          else{
  
  
          }
        

        
        
    }
    


    public function mydiscountsecondweek(Request $request)
    {
        log::info('inside mydiscountsecondweek');
          
         log::info('request my states id aa rahA hai');
       
         log::info($request);
        $discountid= $request->my_id;
        $eventsid= $request->event_id;
        $first_discount= $request->first_discount;
        $second_discount= $request->second_discount;
         
         $discount = $this->mydiscount_title->get_discountweek_second($discountid,$eventsid,$first_discount,$second_discount); 
        
        if(empty($discount))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'seconddiscountweek' => $discount ]);
        
    }
    public function mydiscountweek(Request $request)
    {
        log::info('inside mydiscountweek');
          
         log::info('request my states id aa rahA hai');
       
         log::info($request);
        $discountid= $request->my_id;
        $eventsid= $request->event_id;
        $first_discount= $request->first_discount;
         
         $discountmy = $this->mydiscount_title->get_discountweek($discountid,$eventsid,$first_discount); 
        
         $trip= Trip::where('id',$eventsid)->first();
         $start_date=$trip->start_date;
        $now= Carbon::now();
         $datetime2 = strtotime($start_date); // convert to timestamps
         $datetime1 = strtotime($now); // convert to timestamps
         $dayss = (int)(($datetime2 - $datetime1)/86400);
         log::info($dayss);
          if(empty($dayss))
          {
              return response()->json(['status' => false]);    
          }
               else if($dayss){
           $discount =  $this->mydiscount_title->get_add_discount($dayss,$eventsid,$discountid);  
          return response()->json(['status' => true, 'selectdiscountweek'=>$discountmy,'addsecond_discount' => $discount ]);
          
          }
          else{
  
  
          }
        
    }
    public function get_discount(Request $request)
    {
        log::info('inside get_discount');
          log::info($request);
         log::info('request my states id aa rahA hai');
        $event_id=$request->my_id;
       $trip= Trip::where('id',$event_id)->first();
       $start_date=$trip->start_date;
      $now= Carbon::now();
       $datetime2 = strtotime($start_date); // convert to timestamps
       $datetime1 = strtotime($now); // convert to timestamps
       $dayss = (int)(($datetime2 - $datetime1)/86400);
       log::info($dayss);
        if(empty($dayss))
        {
            return response()->json(['status' => false]);    
        }
      
        else if($dayss){
         $discount = $this->mydiscount->get_discount($dayss,$event_id);  
        return response()->json(['status' => true, 'discount' => $discount ]);
         
        }
        else{


        }
           
       
    }
     public function tripform($slug = null, $view = 'eventmie::trip.form', $extra = [])
    {
        $event  = [];
        
        log::info('yanha $slug hai');
        log::info($slug);
        // get event by event_slug
        if($slug)
        {
            $event  = $this->trip->get_trip($slug);
           
          
            log::info('inside slug form');
            log::info($event);
        }
    
        $organisers = [];
        // fetch organisers dropdown
        // only if login user is admin
        if(Auth::user()->hasRole('admin'))
        {
            // fetch organisers
            $organisers    = $this->event->get_organizers(null);
            foreach($organisers as $key => $val)
                $organisers[$key]->name = $val->name.'  ( '.$val->email.' )';

            if($slug)
            {
                // in case of edit event, organiser_id won't change
                $this->organiser_id = $event->user_id;    
            }
        }
        
        $organiser_id             = $this->organiser_id ? $this->organiser_id : 0;
        $selected_organiser       = User::find($this->organiser_id);
        log::info('inside form');
        log::info($event);
        return Eventmie::view($view, compact('event', 'organisers', 'organiser_id', 'extra', 'selected_organiser'));
    }
    /**
     *  my  event for particular organiser
     */

    public function get_myevents(Request $request)
    {
        if(Auth::user()->hasRole('admin'))
        {
            return redirect()->route('voyager.events.index');   
        }

        $params   = [
            'organiser_id' => Auth::id(),
        ];

        $myevents    = $this->event->get_my_events($params);

        if(empty($myevents))
            return error(__('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );
        
        return response([
            'myevents'=> $myevents->jsonSerialize(),
        ], Response::HTTP_OK);

    }

    /**
     *  my all  event for particular organiser
     */

    public function get_all_myevents()
    {
        if(Auth::user()->hasRole('admin'))
        {
            return redirect()->route('voyager.events.index');   
        }

        $params   = [
            'organiser_id' => Auth::id(),
        ];

        $myevents    = $this->event->get_all_myevents($params);

        if(empty($myevents))
            return error(__('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );
        
        return response([
            'myevents'=> $myevents,
        ], Response::HTTP_OK);

    }

   
    
    // check login user role
    protected function is_admin(Request $request)
    {
        // if login user is Organiser then 
        // organiser id = Auth::id();
        $this->organiser_id = Auth::id();

        // if admin is creating event
        // then user Auth::id() as $organiser_id
        // and organiser id will be the id selected from Vue dropdown
        if(Auth::user()->hasRole('admin'))
        {
            $request->validate([
                'organiser_id'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            ], [
                'organiser_id.*' => __('eventmie-pro::em.organiser').' '.__('eventmie-pro::em.required'),
            ]);
            $this->organiser_id = $request->organiser_id;
        }

    }


    /**
     * Create-edit event
     *
     * @return array
     */
    public function form($slug = null, $view = 'eventmie::events.form', $extra = [])
    {
        
        $event  = [];
        
        // get event by event_slug
        if($slug)
        {
            $event  = $this->event->get_event($slug);
            $event  = $event->makeVisible('online_location');
            // user can't edit other user event but only admin can edit event's other users
            if(!Auth::user()->hasRole('admin') && Auth::id() != $event->user_id)
                return redirect()->route('eventmie.events_index');
        }
    
        $organisers = [];
        // fetch organisers dropdown
        // only if login user is admin
        if(Auth::user()->hasRole('admin'))
        {
            // fetch organisers
            $organisers    = $this->event->get_organizers(null);
            foreach($organisers as $key => $val)
                $organisers[$key]->name = $val->name.'  ( '.$val->email.' )';

            if($slug)
            {
                // in case of edit event, organiser_id won't change
                $this->organiser_id = $event->user_id;    
            }
        }
        
        $organiser_id             = $this->organiser_id ? $this->organiser_id : 0;
        $selected_organiser       = User::find($this->organiser_id);
        
        return Eventmie::view($view, compact('event', 'organisers', 'organiser_id', 'extra', 'selected_organiser'));
    }

    // create event
    public function store(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);
       
        $mycat=Category::where('id',$request->category_id)->first();
        $catname=$mycat->name;
        log::info($catname);
        $opemail=Auth::User()->email;
        $opname=Auth::User()->name;
        // 1. validate data
        $request->validate([
            'title'             => 'required|max:256',
            'excerpt'           => 'required|max:512',
            'slug'              => 'required|max:512',
            'category_id'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            'description'       => 'required',
            'faq'               => 'nullable',
        ], [
            'category_id.*' => __('eventmie-pro::em.category').' '.__('eventmie-pro::em.required')
        ]);

        log::info('$result->title se pehle');
        $result             = (object) [];
        $result->title      = null;
        $result->excerpt    = null;
        $result->slug       = null;
        
        // in case of edit
        if(!empty($request->event_id))
        {
            $request->validate([
                'event_id'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            ]);

            // check this event id have login user relationship
            $result      = (object) $this->event->get_user_event($request->event_id, $this->organiser_id);
        
            
    
        }
        
        // title is not equal to before title then apply unique column rule    
        if($result->title != $request->title)
        {
            log::info('unique ke bheetar');
            $request->validate([
                'title'             => 'unique:events,title',
            ]);
        }
        
        // slug is not equal to before slug then apply unique column rule    
        if($result->slug != $request->slug)
        {
            $request->validate([
                'slug'             => 'unique:events,slug',
            ]);
        }
        $event = Event::orderBy('id','desc')->first();
        log::info('event aa raha hai');
        log::info($event);
        $events_id=$event->id;
        $ev_id= $events_id + 1;
        log::info($ev_id);
        $params = [
            "title"         => $request->title,
            "excerpt"       => $request->excerpt,
            "slug"          => $this->slugify($request->slug),
            "description"   => $request->description,
            "category"       => $catname,
            "event_id"   => $ev_id,
            "operator_email"   => $opemail,
            "operator_name"   => $opname,
            "faq"           => $request->faq,
            "category_id"   => $request->category_id,
            "featured"      => 0,
        ];

        //featured
        if(!empty($request->featured))
        {
            $request->validate([
                'featured'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            ]);

            $params["featured"]       = $request->featured;
        }

        // Admin controls status via checkbox
        if(Auth::user()->hasRole('admin'))
        {
            $status             = (int) $request->status;
            $params["status"]   = $status ? 1 : 0;
        }
        else
        {
            // organizer event status will be controlled by admin
            // - when organizer login
            // - when creating event
            if(empty($request->event_id))
            {
                // - manual approval on
                if(setting('multi-vendor.verify_publish'))
                {
                    $params["status"] = 0;
                }
                else
                {
                    // - manual approval off
                    $params["status"] = 1;
                }
            }
        }
        
        // only at the time of event create
        if(!$request->event_id)
        {
            $params["user_id"]       = $this->organiser_id;
            $params["item_sku"]      = (string) time().rand(1,98);
        }
        
        $event    = $this->event->save_event($params, $request->event_id);
        
        if(empty($event))
            return error(__('eventmie-pro::em.event_not_created'), Response::HTTP_BAD_REQUEST );

        // ====================== Notification ====================== 
        //send notification after bookings
        $msg[]                  = __('eventmie-pro::em.event').' - '.$event->title;
        $extra_lines            = $msg;

        $mail['mail_subject']   = __('eventmie-pro::em.event_created');
        $mail['mail_message']   = __('eventmie-pro::em.event_info');
        $mail['action_title']   = __('eventmie-pro::em.manage_events');
        $mail['action_url']     = route('eventmie.myevents_index');
        $mail['n_type']         = "events";

        $notification_ids       = [1, $this->organiser_id];
        
        $users = User::whereIn('id', $notification_ids)->get();
        try {
            \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail, $extra_lines));
        } catch (\Throwable $th) {}
        // ====================== Notification ======================     
        
        
        // in case of create
        if(empty($request->event_id))
        {
            // set step complete
            $this->complete_step($event->is_publishable, 'detail', $event->id);
            return response()->json(['status' => true, 'id' => $event->id, 'organiser_id' => $event->user_id , 'slug' => $event->slug ]);
        }    
        // update event in case of edit
        $event      = $this->event->get_user_event($request->event_id, $this->organiser_id);
        return response()->json(['status' => true, 'slug' => $event->slug]);    
    }

    // complete specific step
    protected function complete_step($is_publishable = [], $type = 'detail', $event_id = null)
    {
        if(!empty($is_publishable))
            $is_publishable             = json_decode($is_publishable, true);

        $is_publishable[$type]      = 1;
        
        // save is_publishable
        $params     = ['is_publishable' => json_encode($is_publishable)];
        $status     = $this->event->save_event($params, $event_id);

        return true;
    }

    // crate media of event
    public function store_media(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);

        $images    = [];
        $poster    = null;
        $thumbnail = null;

        // 1. validate data
        $video = $request->video_link;
                        $embeds = preg_replace(
                            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
                            "https://www.youtube.com/embed/$2",
                            $video
                        );
        $request->validate([
            'event_id'      => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            'thumbnail'     => 'required',
            'poster'        => 'required',
        ]);

        // vedio link optional so if have vedio ling then validation apply
        if(!empty($request->video_link))
        {
            $request->validate([
                'video_link'    => 'required',
            ]);
        }
        
        $result              = [];
        // check this event id have login user or not
        $result    = $this->event->get_user_event($request->event_id, $this->organiser_id);

       
    
        // for multiple image
        $path = 'events/'.Carbon::now()->format('FY').'/';

        // for thumbnail
        if(!empty($_REQUEST['thumbnail'])) 
        { 
            $params  = [
                'image'  => $_REQUEST['thumbnail'],
                'path'   => 'events',
                'width'  => 512,
                'height' => 512,  
            ];
            $thumbnail   = $this->upload_base64_image($params);
        }

        if(!empty($_REQUEST['poster'])) 
        {
            $params  = [
                'image'  => $_REQUEST['poster'],
                'path'   => 'events',
                'width'  => 1920,
                'height' => 1080,  
            ];

            $poster   = $this->upload_base64_image($params);
        }
    
        // for image
        if($request->hasfile('images')) 
        { 
            // if have  image and database have images no images this event then apply this rule 
            $request->validate([
                'images'        => 'required',
                'images.*'      => 'mimes:jpeg,png,jpg,gif,svg',
            ]); 
        
            $files = $request->file('images');
    
            foreach($files as  $key => $file)
            {
                $extension       = $file->getClientOriginalExtension(); // getting image extension
                $image[$key]     = time().rand(1,988).'.'.$extension;
              $mypath=  $file->storeAs('public/'.$path, $image[$key]);
                log::info('me image j hu');
              
                log::info($file); 
                log::info($mypath); 
              

                $images[$key]    = $path.$image[$key];
            }
			log::info($images);
        }
        
        $params = [
            "thumbnail"     => !empty($thumbnail) ? $path.$thumbnail : null ,
            "poster"        => !empty($poster) ? $path.$poster : null,
            "video_link"    => $request->video_link,
            "video"    =>   $embeds,
            "user_id"       => $this->organiser_id,
        ];  

        // if images uploaded
        if(!empty($images))
        {
            if(!empty($result->images))
            {
                $exiting_images = json_decode($result->images, true);

                $images = array_merge($images, $exiting_images);
            }

            $params["images"] = json_encode(array_values($images));

        }    
        
        $status   = $this->event->save_event($params, $request->event_id);

        if(empty($status))
        {
            return error('Database failure!', Response::HTTP_BAD_REQUEST );
        }

        // get media  related event_id who have created now
        $images   = $this->event->get_user_event($request->event_id, $this->organiser_id);

        // set step complete
        $this->complete_step($images->is_publishable, 'media', $request->event_id);

        return response()->json(['images' => $images, 'status' => true]);
    }

    // crate location of event
    public function store_location(Request $request)
    {
       
        $input = $request->all();
      
        $this->is_admin($request);

        $trips=  Event::where('id',$request->event_id)
                   
        ->first();
        $user_id= $trips->user_id;
        $trips_country=  Mycountry::where('country_id',$request->country_id)
             
        ->first();
        $trips_contname=$trips_country->country_name;
        $trips_state=  State::where('state_id',$request->state_id)
               
        ->first();
        $trips_statename=$trips_state->state_name;

        $lowerstate = strtolower($trips_statename);
        $slug2 = str_replace(" ", "-", $lowerstate);

        $getCountryName = $trips_contname;
        $Countriess =str_slug($getCountryName);
        $Statename =  $slug2; 
        $States =str_slug($Statename);
        $country_state=collect([$Countriess, $States])->implode('/');
        $trips_city=  City::where('city_id',$request->city_id)
            
        ->first();
        $trips_cityname=$trips_city->city_name;
        $lowercity = strtolower($trips_cityname);
        $slug1 = str_replace(" ", "-", $lowercity);
       
        $myvalue = strtolower($trips_cityname);
        $arr = explode(' ',trim($trips_cityname));
       
        $count = count($arr);
        $getCityName = $slug1;
        $cities = str_slug($getCityName); 
        $country_state_city=collect([$Countriess, $States,$cities])->implode('/');

        $request->validate([
            'event_id'          => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            'country_id'        => 'numeric|min:0',
            'venue'             => 'required|max:256',
            'address'           => 'max:512',
            'city'              => 'max:256',
            'state'             => 'max:256',
            'zipcode'           => 'max:64',
            'latitude'          => ['nullable','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude'         => ['nullable','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'online_location'   => 'nullable|string',
            'state_id' => 'required|string|max:255',
            'city_id' => 'required|string|max:255',
        ]);

        if($count==1)
        {
           
            $params = [
                "country_id"    => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,

                "venue"         => $request->venue,
                "address"       => $request->address,
                "city"          => $trips_cityname,
                "zipcode"       => $request->zipcode,
                "slug3"       =>  $trips_contname,
                "slug2"         => $slug2,
                "slug1"         => $trips_cityname,
    
                "country_state"  =>$country_state,
                "country_state_city"=>$country_state_city,
                "state"         => $slug2,
                "latitude"      => $request->latitude,
                "longitude"     => $request->longitude,
                "online_location" => $request->online_location
            ];
    
        }
        else{
            $params = [
                "country_id"    => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
    
                "venue"         => $request->venue,
                "address"       => $request->address,
                "city"          => $slug1,
                "zipcode"       => $request->zipcode,
                "slug3"       =>  $trips_contname,
                "slug2"         => $slug2,
                "slug1"         => $slug1,
    
                "country_state"  =>$country_state,
                "country_state_city"=>$country_state_city,
                "state"         => $slug2,
                "latitude"      => $request->latitude,
                "longitude"     => $request->longitude,
                "online_location" => $request->online_location
            ];
        }
        // only at the time of event create
        if(!$request->event_id)
        {
            $params["user_id"]       = $this->organiser_id;
        }
log::info('check this event id have login user or not');
        // check this event id have login user or not
        $check_event    = $this->event->get_user_event($request->event_id, $this->organiser_id);
       

        $location   = $this->event->save_event($params, $request->event_id);
        if(empty($location))
        {
            return error('Database failure!', Response::HTTP_BAD_REQUEST );
        }

        // get update event
        log::info('get_user_event this event id have login user or not');
        $event    = $this->event->get_user_event($request->event_id, $this->organiser_id);
        
        $events    = $this->event->get_user_myevent($request->event_id, $this->organiser_id);
       
        // set step complete
        $this->complete_step($events->is_publishable, 'location', $request->event_id);
        
        return response()->json(['status' => true, 'event' => $events]);
    }    


    // crate timing of event
    public function store_timing(Request $request)
    {   
        // if logged in user is admin
        $this->is_admin($request);

        $request->validate([
            'event_id'          => 'required',
        ]); 
        
        $check_event    = $this->event->get_user_event($request->event_id, $this->organiser_id);

       

        $data           = [];
        $schedules      = [];

        $repetitive     = (int) $request->repetitive;
        
        if($repetitive)
        {
            $repetitive_event   = $this->prepare_repetitive_event($request);
            
            //=======================Update schedule case=============================================
            
            // update schedule true then return response to vue 
            if(!empty($repetitive_event['update']))
            {
                if($repetitive_event['update_schedule'] == true) 
                    return response()->json(['status' => true]);

                
                if($repetitive_event['update_schedule'] == false) 
                {
                    $msg = __('eventmie-pro::em.schedules_not_updated');
                    return error($msg, Response::HTTP_BAD_REQUEST );
                }
            }        
            //=====================================End==============================================    

                
            if(!$repetitive_event['status'])
                return error($repetitive_event['error'], Response::HTTP_BAD_REQUEST );
            
            $data               = $repetitive_event['repetitive_event']['data'];
            $schedules          = $repetitive_event['repetitive_event']['schedules'];
        }
        else
        {
            $single_event       = $this->prepare_single_event($request);

            if(!$single_event['status'])
                return error($single_event['error'], Response::HTTP_BAD_REQUEST );

            // === Delete schedules if have any schedules in case of single repetitive event ===            
                
                // if changing event from repetitive to normal, then delete all schedules
                if($check_event->repetitive)
                {
                    $params  = [
                        'event_id'          => $request->event_id,
                        'user_id'           => $this->organiser_id,
                    ];
                    
                    // delete old schedule because changeid_date have true
                    $delete_schedule  = $this->schedule->delete_schedule($params); 
                    
                    if(empty($delete_schedule))
                    {
                        $msg = __('eventmie-pro::em.schedules_not_updated');
                        return error($msg, Response::HTTP_BAD_REQUEST );
                    }
                       
                }    
            // ==== End ====    

            $data               = $single_event['data'];
        }
        
        $event_timing           = $this->event->save_event($data, $request->event_id);

        if(empty($event_timing))
            return error('Database failure!', Response::HTTP_BAD_REQUEST);


        // in case of repetitive event
        if(!empty($schedules))
        {
            // create repetitive event  schedule 
            $schedule      =  $this->schedule->create_schedule($schedules, $request->event_id);
            
            if(empty($schedule))
            {
                $msg = __('eventmie-pro::em.schedules_not_updated');
                return error($msg, Response::HTTP_BAD_REQUEST );
            }
        }
        
        // get update event
        $event    = $this->event->get_user_event($request->event_id, $this->organiser_id);
        // set step complete
        $this->complete_step($event->is_publishable, 'timing', $request->event_id);

        return response()->json(['status' => true]);
    }

    // add tags to event
    public function store_event_tags(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);

        $request->validate([
            'event_id'        => 'required',
        ]);
        
        // check event is valid or not
        $check_event    = $this->event->get_user_event($request->event_id, $this->organiser_id);

       

        $event_tags     = [];
          
        // event_tags array optional  
        
        if(!empty($request->tags_ids))
        {
            // comma value to conver to array for tags
            $tags_ids       = explode(',', $request->tags_ids);
            
            // check tag and login user relationship 
            $tags           = Tag::whereIn('id', $tags_ids)
                                        ->where(['organizer_id' => $this->organiser_id])
                                        ->get();
            $tags           = to_array($tags);
            if(empty($tags))
            {
                $msg = __('eventmie-pro::em.tag').' '.__('eventmie-pro::em.not_found');
                return response()->json(['status' => false, 'message' => $msg ]);
            }

            foreach($tags as $key => $value)
                $event_tags[$key]['tag_id']      = $value['id'];
            
        }    
        
        $params = [
            'event_tags'     => $event_tags,
            "event_id"       => $request->event_id,
            "user_id"        => $this->organiser_id,
        ];
        
        $status = $this->event->event_tags($params);
        
        
        $msg = __('eventmie-pro::em.event_save_success');
        return response()->json(['status' => true, 'message' => $msg ]);

    }

    
    // store seo for event
    public function store_seo(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);

        // 1. validate data
        $request->validate([
            'meta_title'             => 'max:256',
            'meta_keywords'          => 'max:256',
            'meta_description'       => 'max:512',
        ]);

        $params = [
            "meta_title"         => $request->meta_title,
            "meta_keywords"      => $request->meta_keywords,
            "meta_description"   => $request->meta_description,
          
        ];

        
        // check this event id have login user or not
        $check_event    = $this->event->get_user_event($request->event_id, $this->organiser_id);

       

        $seo   = $this->event->save_event($params, $request->event_id);
        
        if(empty($seo))
        {
            return error('Database failure!', Response::HTTP_BAD_REQUEST );
        }

        // get update event
        $event    = $this->event->get_user_event($request->event_id, $this->organiser_id);
        
        return response()->json(['status' => true, 'event' => $event]);
    }   
    public function mytickets(Request $request)
    {
        log::info('inside mytickets seee');
        log::info($request);
        $event_id=$request->event_id;
        log::info($event_id);
        $tickets = $this->myticket->get_tickets($event_id);
        log::info($tickets);
        if(empty($tickets))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'tickets' => $tickets ]);
        
    }
    // publish event
    public function event_publish(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);

        $request->validate([
            'event_id'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
        ]);
        
        // check event is valid or not
        $event    = $this->event->get_user_event($request->event_id, $this->organiser_id);

       

        // check all step is completed or not 
        $is_publishable     =  json_decode($event->is_publishable, true);
        
        if(count($is_publishable) < 5)
            return error(__('eventmie-pro::em.please_complete_steps'), Response::HTTP_BAD_REQUEST );

        // do not unpublish in demo mode
        if(config('voyager.demo_mode'))
        {
            if($event->publish)
                return error('Demo mode', Response::HTTP_BAD_REQUEST );
        }
        
        $params  = [
            'publish'      => $event->publish == 1 ? 0 : 1,
        ];

        $publish_event    = $this->event->save_event($params, $request->event_id);

        if(empty($publish_event))
        {
            return error('Database failure!', Response::HTTP_BAD_REQUEST );
        }

        return response()->json(['status' => true ]);

    }

    // get event
    public function get_user_event(Request $request)
    {
        log::info('insidess dharmaesh get_user_event');
               // if logged in user is admin
        $this->is_admin($request);
        $request->validate([
            'event_id'        => 'required',
            
        ]);
       
        // check event is valid or not
        $event  = $this->event->get_user_event($request->event_id, $this->organiser_id);

      
        log::info('insidess');
        log::info($event);
        if(!empty($event))
        {
        $event    = $event->makeVisible('online_location');
        // check event is online or not
        $event->online_event    = (!empty($event->online_location)) ? 1 : 0; 

        return response()->json(['status' => true, 'event' => $event ]);

    }
    else{
        $event  = $this->trip->get_user_event($request->event_id, $this->organiser_id);
        log::info('insidess');
        
        $event    = $event->makeVisible('online_location');
        // check event is online or not
        $event->online_event    = (!empty($event->online_location)) ? 1 : 0; 
        log::info($event);
      

        return response()->json(['status' => true, 'event' => $event ]);

    }

    }

    public function get_user_trip(Request $request)
    {
        log::info('insidess dharmaesh');
               // if logged in user is admin
        $this->is_admin($request);
        $request->validate([
            'event_id'        => 'required',
            
        ]);
       
        // check event is valid or not
      
        $event  = $this->trip->get_user_event($request->event_id);
        log::info('insidess');
        log::info($event);
        if(!empty($event))
        {
        $event    = $event->makeVisible('online_location');
        // check event is online or not
        $event->online_event    = (!empty($event->online_location)) ? 1 : 0; 

        return response()->json(['status' => true, 'event' => $event ]);

    }
    else{
        $event  = $this->trip->get_user_event($request->event_id, $this->organiser_id);
        log::info('insidess');
        
        $event    = $event->makeVisible('online_location');
        // check event is online or not
        $event->online_event    = (!empty($event->online_location)) ? 1 : 0; 
        log::info($event);
      

        return response()->json(['status' => true, 'event' => $event ]);

    }

    }
    /**
     * The tags that belong to the event.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

      
    
    // get all countries
    public function countries()
    {
        $countries = $this->countries->get_mycountries();
      
        if(empty($countries))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'countries' => $countries ]);
        
    }
    public function mystates(Request $request)
    {
        log::info('inside mystates');
        log::info($request);
        $state_id=$request->my_id;
        $states = $this->states->get_states($state_id);
        log::info($states);
        if(empty($states))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'states' => $states ]);
        
    }
    public function mycities(Request $request)
    {
        log::info($request);
        $citi_id=$request->my_id;
        log::info($citi_id);
        $cities = $this->cities->get_cities($citi_id);
         log::info($cities);
        if(empty($cities))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'cities' => $cities ]);
        
    }

    public function tripdays(Request $request)
    {
        log::info('inside tripdays');
        log::info($request);
        $event_id=$request->event_id;
        $iternarydays = $this->iternarydays->get_days($event_id);
        log::info($iternarydays);
        if(empty($iternarydays))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'taxes' => $iternarydays ]);
        
    }
    public function mycountries()
    {
        $countries = $this->countries->get_mycountries();
        log::info('mera  $countries');
        log::info($countries);
        if(empty($countries))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'countries' => $countries ]);
        
    }
    public function getcategory()
    {
        $categories = $this->tripcategory->get_category();
        log::info('mera  $tripcategory');
        log::info($categories);
        if(empty($categories))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'categories' => $categories ]);
        
    }
    
    /**
     *   only admin can delete event
     */
    public function delete_event($slug = null)
    {   
        if(config('voyager.demo_mode'))
        {
            return redirect()
            ->route("voyager.events.index")
            ->with([
                'message'    => 'Demo mode',
                'alert-type' => 'info',
            ]);
        }
        
        // only admin can delete event
        
        if(Auth::check() && !Auth::user()->hasRole('admin'))
        {
            return redirect()->route('eventmie.events');
        }

        // get event by event_slug
        if(empty($slug))
            return error('Event Not Found!', Response::HTTP_BAD_REQUEST );
        

        $event = $this->event->get_event($slug);
        
        if(empty($event))
            return error('Event Not Found!', Response::HTTP_BAD_REQUEST );

        $params    = [
            'event_id'     => $event->id,
        ];

        $delete_event     = $this->event->delete_event($params);

        if(empty($delete_event))
        {
            return error('Event Could Not Deleted!', Response::HTTP_BAD_REQUEST );   
        }

        $msg = __('eventmie-pro::em.event_deleted');
        
        return redirect()
        ->route("voyager.events.index")
        ->with([
            'message'    => $msg,
            'alert-type' => 'success',
        ]); 
        
    }

    /**
     * export_attendees
     */

    public function export_attendees($slug)
    {
        // check event is valid or not
        $event    = $this->event->get_event($slug);
       
        
        $params   = [
            'event_id' => $event->id,
        ];

        // get particular event's bookings
        $bookings = $this->booking->get_event_bookings($params);
        if(empty($bookings))
            return error_redirect('Booking Not Found!');

        // customize column values
        $bookings_csv = [];
        foreach($bookings as $key => $item)
        {
            $bookings[$key]['event_repetitive'] = $item['event_repetitive'] ? __('eventmie-pro::em.yes') : __('eventmie-pro::em.no');
            $bookings[$key]['is_paid']          = $item['is_paid'] ? __('eventmie-pro::em.yes') : __('eventmie-pro::em.no');
            
            
            if($item['booking_cancel'] == 1)
                $bookings[$key]['booking_cancel']       = __('eventmie-pro::em.pending');
            elseif($item['booking_cancel'] == 2)
                $bookings[$key]['booking_cancel']       = __('eventmie-pro::em.approved');
            elseif($item['booking_cancel'] == 3)
                $bookings[$key]['booking_cancel']       = __('eventmie-pro::em.refunded');
            else
                $bookings[$key]['booking_cancel']   = __('eventmie-pro::em.no_cancellation');

            
            if($item['status'])
                $bookings[$key]['status']           = __('eventmie-pro::em.enabled');
            else
                $bookings[$key]['status']           = __('eventmie-pro::em.disabled');

            
            $bookings[$key]['checked_in']           = $item['checked_in'].' / '.$item['quantity'];
        }    

        // convert array to collection for csv
        $bookings = collect($bookings);

        // create object of laracsv
        $csvExporter = new \Laracsv\Export();
    
        // create csv 
        $csvExporter->build($bookings, [
            
            //events fields which will be include
            'id',
            
            'itm_sku',
            'event_category',
            'event_title',
            'event_start_date',
            'event_end_date',
            'event_start_time',
            'event_end_time',
            'event_repetitive',

            'customer_name', 
            'customer_email', 

            'order_number',
            'ticket_title',
            'ticket_price',
            'price',
            'quantity', 
            'tax',
            'net_price',
            'currency',
            'transaction_id',
            'is_paid',
            'payment_type',
            
            'booking_cancel',
            'status',
            'checked_in',

            'created_at', 
            'updated_at'
        ]);
        
        // download csv
        $csvExporter->download($event->slug.'-attendies.csv');
    } 
    

    /* ==================== Private fucntions ==================== */
    
    /**
     *  Upload base64 image 
     */
    protected function upload_base64_image($params = [])
    {
        if(!empty($params['image'])) 
        { 
            $image           = base64_encode(file_get_contents($params['image']));
            $image           = str_replace('data:image/png;base64,', '', $image);
            $image           = str_replace(' ', '+', $image);

            if(class_exists('\Str'))
                $filename        = time().\Str::random(10).'.'.'jpg';
            else
                $filename        = time().str_random(10).'.'.'jpg';
            
            $path      = '/storage/'.$params['path'].'/'.Carbon::now()->format('FY').'/';
            log::info('$path hai na');
            log::info($path);
            $image_resize    = Image::make(base64_decode($image))->resize($params['width'], $params['height']);

            // first check if directory exists or not
            
            if (! File::exists(public_path().$path)) {
                File::makeDirectory(public_path().$path, 0777, true);
            }
    
           $pathsing= $image_resize->save(public_path($path . $filename));
           log::info('public path  hai na');
            $pubs=public_path();
            log::info($pubs);
            return  $filename;
        }
    } 

    /**
     *  prepare_single_event
     */

    protected function prepare_single_event(Request $request)
    {

        $event = Event::where(['id' => $request->event_id])->first();
        
        // start validation will not apply if database start_date and request start is same
        if($event->start_date != $request->start_date)
        {
            $request->validate([
                'start_date'        => 'required|date|after_or_equal:today',
            ]);
           
        }

        // if logged in user is admin
        $this->is_admin($request);

        // 1. validate data
        $request->validate([
            'end_date'          => 'required|date|after_or_equal:start_date',
            'start_time'        => 'required|date_format:H:i:s',
            'end_time'          => 'required|date_format:H:i:s',
        ]);

        $data = [
            "start_date"        => $request->start_date,
            "start_time"        => $request->start_time,
            "end_date"          => $request->end_date,
            "end_time"          => $request->end_time,
            "repetitive"        => $request->repetitive,
        ];

        return [
            'status'    => true, 
            'data'      => $data
        ];
    }

    /**
     * prepare_repetitive_event
     */
   
    protected function prepare_repetitive_event(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);

        $request->validate([
            'end_date'          => 'required|date|after_or_equal:start_date',
            'start_time'        => 'required|date_format:H:i:s',
            'end_time'          => 'required|date_format:H:i:s',
            'repetitive'        => 'required|numeric|between:1,1',
            'repetitive_type'   => 'required|numeric|between:1,3',
        ]);
        
        $event = Event::where(['id' => $request->event_id])->first();
        

        // start validation will not apply if database start_date and request start is same
        if($event->start_date != $request->start_date)
        {
            $request->validate([
                'start_date'        => 'required|date|after_or_equal:today',
            ]);
           
        }

        // 1. count_months => start_date & end_date
        // 2. months_dates => start_date & end_date
            // $months_dates[0]['from_date'] 
            // $months_dates[0]['to_date'] 

            // $months_dates[1]['from_date'] 
            // $months_dates[2]['to_date'] 

        // 3. count_months == count(repetitive_dates) == count(from_time) == count(to_time)
            // return error('Error message!', Response::HTTP_BAD_REQUEST );
        
        $start_month               = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date)->modify('first day of this month');
        $end_month                 = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date)->modify('last day of this month');
        $count_months              = $end_month->diffInMonths($start_month)+1;

        // validate from_time and to_time
        $request->validate([
            'from_time'             => ['required', 'array', "min:$count_months", "max:$count_months"],
            'from_time.*'           => ['required', 'date_format:H:i:s'],

            'to_time'               => ['required', 'array', "min:$count_months", "max:$count_months"],
            'to_time.*'             => ['required', 'date_format:H:i:s'],
        ]);

        $repetitive_type = (int) $request->repetitive_type;

        // count_months == count_repetitive_dates
        // repetitive dates
        if( ($repetitive_type === 1 || $repetitive_type === 3) && !empty($request->repetitive_dates))
        {
            $request->validate([
                'repetitive_dates'       => ['required', 'array', "min:$count_months", "max:$count_months"],
                'repetitive_dates.*'     => ['required', 'regex:/^[0-9 ,]+$/i'], 
            ]);
        }
            
        // count_months == count_repetitive_days
        // repetitive days
        if( ($repetitive_type === 2) && !empty($request->repetitive_days))
        {
            $request->validate([
                'repetitive_days'       => ['required', 'array', "min:$count_months", "max:$count_months"],
                'repetitive_days.*'     => ['required', 'regex:/^[0-9 ,]+$/i'], 
            ]);
        }


        $start_date     = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date)->modify('first day of this month');
        $end_date       = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date)->modify('last day of this month');
        
        // get months name between two dates
        $month_names_temp = CarbonPeriod::create($start_date, '1 month', $end_date);
        

        $month_names     = [];
        $from_date       = [];
        $to_date         = [];
        
        foreach ($month_names_temp as $key => $value) 
        {
            $month_names[$key] = $value->format("Y-m-d") ;
        }
        
        if(!empty($month_names))
        {
            // get first day of months
            foreach ($month_names as $key => $value) {
                $first_day_month   = new \DateTime($value);
                $first_day_month->modify('first day of this month');
                $from_date[$key]   = $first_day_month->format('Y-m-d');
            }

            // get last day of months
            foreach ($month_names as $key => $value) {
                $last_day_month  = new \DateTime($value);
                $last_day_month->modify('last day of this month');
                $to_date[$key]   = $last_day_month->format('Y-m-d');
            }
        }
    
        $schedules           = [];

        // current date time
        $current_date_time   = Carbon::now()->toDateTimeString();
        
        // repetitive dates
        if( ($repetitive_type === 1 || $repetitive_type === 3) && !empty($request->repetitive_dates))
        {
            foreach ($request->repetitive_dates as $key => $value)
            {
                $schedules[$key]['event_id']             = $request->event_id;
                $schedules[$key]['user_id']              = $this->organiser_id;
                $schedules[$key]['repetitive_type']      = $repetitive_type;
                $schedules[$key]['repetitive_dates']     = $value;
                $schedules[$key]['from_time']            = $request->from_time[$key];
                $schedules[$key]['to_time']              = $request->to_time[$key];

                // generate from start_date and end_date
                $schedules[$key]['from_date']            = $from_date[$key];
                $schedules[$key]['to_date']              = $to_date[$key];
                $schedules[$key]['created_at']           = $current_date_time;
                $schedules[$key]['updated_at']           = $current_date_time;

                // timestamp
                // make repetitive_days = null
                $schedules[$key]['repetitive_days']      = null;
            }
        }    

        // repetitive days
        if( ($repetitive_type === 2) && !empty($request->repetitive_days))
        {
            foreach ($request->repetitive_days as $key => $value)
            {
                $schedules[$key]['event_id']             = $request->event_id;
                $schedules[$key]['user_id']              = $this->organiser_id;
                $schedules[$key]['repetitive_type']      = $repetitive_type;
                $schedules[$key]['repetitive_days']      = $value;
                $schedules[$key]['from_time']            = $request->from_time[$key];
                $schedules[$key]['to_time']              = $request->to_time[$key];

                // generate from start_date and end_date
                $schedules[$key]['from_date']            = $from_date[$key];
                $schedules[$key]['to_date']              = $to_date[$key];
                $schedules[$key]['created_at']           = $current_date_time;
                $schedules[$key]['updated_at']           = $current_date_time;

                // make repetitive_dates = null
                $schedules[$key]['repetitive_dates']      = null;
            }
        }    
        
        $data = [
            "start_date"        => $request->start_date,
            "start_time"        => $request->start_time,
            "end_date"          => $request->end_date,
            "end_time"          => $request->end_time,
            "repetitive"        => $request->repetitive,
            "merge_schedule"    => $repetitive_type > 1 ? $request->merge_schedule : 0,
            "user_id"           => $this->organiser_id,
        ];


        $repetitive_event = [
            'data'      => $data,
            'schedules' => $schedules,
        ];

        // check for update schedule
            // 1. Check if there's any schedule for this event (call model function)
            // if yes
            // -----------------------
                // 1. check if start_date and end_date changed (if)
                    // if dates changed then delete all schedules
                    // then continue below in this method
                
                // 2. if start_date and end_date not changed (else)
                // call this->update_schedule

            // -----------------------
            // if no 
            // then continue

            // if(already_schedules_available)
            // {
                // 
                    // if(check if dates are changed)
                    // {
                            // delete all schedules
                    // }
                    // else
                    // {
                            // call this->update_schedule
                            // return success;
                    // }
            // }
            // get old event
            $check_event    = $this->event->get_user_event($request->event_id, $this->organiser_id);
            // check schedule ids empty on not
            if(!empty($request->schedule_ids))
            {
                // validate from_time and to_time
                $request->validate([
                    'schedule_ids'          => ['required', 'array'],
                    'schedule_ids.*'        => ['required'],
                ]);

                $params  = [
                    'schedule_ids'      => $request->schedule_ids,
                    'event_id'          => $request->event_id,
                    'repetitive_type'   => $request->repetitive_type,
                    'user_id'           => $this->organiser_id,
                ];
                
                
                // check schedule ids existed on not in schedule table
                $check_schedule     = $this->schedule->check_schedule($params);

                if(!empty($check_schedule))
                {
                    $old_start_date     = Carbon::createFromFormat('Y-m-d', $check_event['start_date']);
                    $old_end_date       = Carbon::createFromFormat('Y-m-d', $check_event['end_date']);

                    $new_start_date     = Carbon::createFromFormat('Y-m-d', $request->start_date);
                    $new_end_date       = Carbon::createFromFormat('Y-m-d', $request->end_date);

                    // if event date have not changed to previous dates then change_date variable false 
                    $changed_date       = true;
                    if($old_start_date->equalTo($new_start_date) && $old_end_date->equalTo($new_end_date))
                    {
                        
                        $changed_date    = false;

                        if($request->merge_schedule != $check_event->merge_schedule)
                            $changed_date    = true;
                    }

                    // if changed_date true means dates have changed then old schedule delete then new create schedule
                    if($changed_date)
                    {
                        $params  = [
                            'event_id'          => $request->event_id,
                            'user_id'           => $this->organiser_id,
                        ];
                        
                        // delete old schedule because changeid_date have true
                        $delete_schedule  = $this->schedule->delete_schedule($params);

                        if(empty($delete_schedule))
                        {
                            $msg = __('eventmie-pro::em.schedules_not_updated');
                            return error($msg, Response::HTTP_BAD_REQUEST );
                        }

                        $params = [
                            'repetitive' => 0
                        ];
                        // update repetitive column by 0 after schedule deleted
                        $this->event->save_event($params, $request->event_id);

                    }
                    else
                    {
                        $update_schedule  = $this->update_schedule($request, $schedules);

                        if(!$update_schedule['status'])
                                return ['update' => 1, 'update_schedule' => false];;
                        
                        return ['update' => 1, 'update_schedule' => true];
                    }
                }
                else
                {
                    $msg = __('eventmie-pro::em.schedules_not_updated');
                    return error($msg, Response::HTTP_BAD_REQUEST );
                }
            }

            $params  = [
                'event_id'          => $request->event_id,
                'user_id'           => $this->organiser_id,
            ];
            
            // delete old schedule 
            $delete_schedule  = $this->schedule->delete_schedule($params);

        return [
            'status'            => true,
            'repetitive_event'  => $repetitive_event,
        ]; 
        
    }

    // update repetitive event schedule
    protected function update_schedule(Request $request, $schedules = [])
    {
        // if logged in user is admin
        $this->is_admin($request);

        // if changed_date false means dates have not changed then update schedule
        
        if(empty($schedules))
        {
            $msg = __('eventmie-pro::em.schedules_not_updated');
            return error($msg, Response::HTTP_BAD_REQUEST );
        }
        
        $params       = [
            'schedules'     => $schedules,
            'schedule_ids'  => $request->schedule_ids,
            'event_id'      => $request->event_id,
            'user_id'       => $this->organiser_id,
        ];

        // in case of repetitive event
        if(!empty($params))
        {
            $update_schedule      =  $this->schedule->update_schedule($params);

            if(empty($update_schedule))
            {
                $msg = __('eventmie-pro::em.schedules_not_updated');
                return ['status' => false, 'error' => $msg];
            }
            return ['status' => true];
        }

        $msg = __('eventmie-pro::em.invalid').' '.__('eventmie-pro::em.data');
        return ['status' => false, 'error' => $msg];
    }

    // Make event title -> slug properly
    protected function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        
        // trim
        $text = trim($text, '-');

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     *  get organiser with pagination
     */
    public function get_organizers(Request $request)
    {
        $request->validate([
            'search'        => 'required|string|max:256',
        ]);

        $search     = $request->search;
        $organizers = $this->event->get_organizers($search);

        if(empty($organizers))
        {
            return response()->json(['status' => false, 'organizers' => []]);    
        }

        foreach($organizers as $key => $val)
            $organizers[$key]->name = $val->name.'  ( '.$val->email.' )';
        
        return response()->json(['status' => true, 'organizers' => $organizers ]);
    }

    /**
     *  delete image
     */

    public function delete_image(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);
        
        // 1. validate data
        $request->validate([
            'event_id'             => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            'image'                => 'required|string',
            
        ]);

        $event      = $this->event->get_user_event($request->event_id, $this->organiser_id);

       

        $images     = json_decode($event->images);
    
        $filtered_images = [];
        foreach($images as $key => $val)
        {
            if($val != $request->image)
                $filtered_images[$key] = $val;
        }
        
        $params = [
            'images' =>  !empty($filtered_images) ? json_encode(array_values($filtered_images)) : null, 
        ];

        $event   = $this->event->save_event($params, $request->event_id);

        if(empty($event))
        {
            return error('Database failure!', Response::HTTP_BAD_REQUEST );
        }


        // get media  related event_id who have created now
        return response()->json(['images' => $event, 'status' => true]);

    }

}