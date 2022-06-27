<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use Log;
use Classiebit\Eventmie\Models\Trip;

use Classiebit\Eventmie\Models\Ticket;
use Classiebit\Eventmie\Models\Iternary;
use Classiebit\Eventmie\Models\Category;
use Classiebit\Eventmie\Models\Country;
use Classiebit\Eventmie\Models\State;
use Illuminate\Support\Facades\Hash;
use Classiebit\Eventmie\Models\City;
use Classiebit\Eventmie\Models\Rvsp;

use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Mail\SendMail;
use Classiebit\Eventmie\Mail\Confirm_message;
use Classiebit\Eventmie\Mail\Confirm_msg;
use Classiebit\Eventmie\Mail\Confirm_messages;
use Mail;
use Carbon\Carbon;
use App\Mail\OperatorMail;
use App\Mail\OperatorMails;
use Cookie;
use Classiebit\Eventmie\Models\Myclientcountry;
use Classiebit\Eventmie\Models\Schedule;
use Classiebit\Eventmie\Models\Tag;
use Classiebit\Eventmie\Models\Tax;
use Classiebit\Eventmie\Models\Tripcategory;
use Classiebit\Eventmie\Models\Booking;
use Classiebit\Eventmie\Models\Userevent;
use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Currency;


class TourController extends Controller
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
    
        $this->event    = new Trip;

        $this->ticket   = new Ticket;

        $this->category = new Tripcategory;

        $this->country  = new Myclientcountry;
        $this->iternary  = new Iternary;
        $this->city  = new City;

        $this->state  = new State;

        $this->schedule = new Schedule;

        $this->tag  = new Tag;
        
        $this->tax      = new Tax;
        
        $this->booking      = new Booking;
        
        $this->organiser_id = null;   
    }

    /* ==================  EVENT LISTING ===================== */

    /**
     * Show all events
     *
     * @return array
     */
    public function index($view = 'eventmie::trip.index', $extra = [])
    {
        // get prifex from eventmie config
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');

        return Eventmie::view($view, compact('path', 'extra'));
    }

    public function touropreter(Request $request,$name, $view = 'eventmie::trip.touro_perator')
    {
      $touropretor = Userevent::where('role_id','3')->where('slug',$name)->first();
      $email = $touropretor->email;
      log::info($email);
      $user = Userevent::where('email',$email)->first();
      $user_id = $user->id;
      $trip = Trip::where('operator_email',$email)->get();
      $event = Event::leftJoin('countries','events.country_id', '=', 'countries.id')
      ->leftJoin('categories','events.category_id', '=', 'categories.id')
      ->select('events.*','countries.country_name','categories.name')->where('user_id',$user_id)->get();
      log::info($event);
    

      return Eventmie::view($view, compact('touropretor','trip','event'))->render();
    }
    // filters for get_events function
    protected function event_filters(Request $request)
    {
        $request->validate([
            'category'          => 'max:256|String|nullable',
            'search'            => 'max:256|String|nullable',
            'start_date'        => 'date_format:Y-m-d|nullable',
            'end_date'          => 'date_format:Y-m-d|nullable',
            'price'             => 'max:256|String|nullable',
            'city'              => 'max:256|String|nullable',
            'state'             => 'max:256|String|nullable',
            'country'           => 'max:256|String|nullable',    
            
        ]);
        
        $category_id            = null;
        $category               = urldecode($request->category); 
        $search                 = $request->search;
        $price                  = $request->price;
        $city                   = urldecode($request->city); 
        $state                  = urldecode($request->state); 
        $country_id             = null;
        $state_id               = null;
        $city_id                = null;
        $country                = urldecode($request->country); 
        
        // search category id
        if(!empty($category))
        {
            $categories  = $this->category->get_categories();

            foreach($categories as $key=> $value)
            {
                if($value['category'] == $category)
                    $category_id = $value['id'];
            }
        }

        // search country id
        if(!empty($country))
        {
            $countries = $this->country->get_mycountries();

            foreach($countries as $key=> $value)
            {
                if($value['country_name'] == $country)
                    $country_id = $value['country_id'];
            }
        }

        if(!empty($state))
        {
            $states = $this->state->get_states();

            foreach($states as $key=> $value)
            {
                if($value['state_name'] == $state)
                    $state_id = $value['state_id'];
            }
        }

        if(!empty($city))
        {
            $cities = $this->city->get_cities();
log::info($cities);
            foreach($cities as $key=> $value)
            {
                if($value['city_name'] == $city)
                    $city_id = $value['city_id'];
            }
        }

        $filters                    = [];
        $filters['category_id']     = $category_id;
        $filters['search']          = $search;
        $filters['price']           = $price;
        $filters['start_date']      = $request->start_date;
        $filters['end_date']        = $request->end_date;
        $filters['city_id']          = $city_id;
        $filters['state_id']        = $state_id;
        $filters['country_id']      = $country_id;
        
        // in case of today and tomorrow and weekand
        if($request->start_date == $request->end_date)
            $filters['end_date']     = null;

        return $filters;    
    }

    // EVENT LISTING APIs
    // get all events
    public function events(Request $request)
    {
        log::info('coming first inside events');
        log::info($request);
        $filters         = [];
        // call event fillter function
        $filters         = $this->event_filters($request);

        $events          = $this->event->events($filters);
      
        log::info($events);
        $event_ids       = [];

        foreach($events as $key => $value)
            $event_ids[] = $value->id;

        // pass events ids
        // tickets
     

        $events_data             = [];

        foreach($events as $key => $value)
        {
            // online event - yes or no
            $value                  = $value->makeVisible('online_location');
            // check event is online or not
            $value->online_location    = (!empty($value->online_location)) ? 1 : 0;
            $events_data[$key]             = $value;
           
        }
        // set pagination values
        $event_pagination = $events->jsonSerialize();
        log::info('mera $events');
        log::info($events);
        $countries = $this->country->get_countries_having_events();
        $allstates = $this->country->get_state_having_events();
        $allcities = $this->country->get_city_having_events();
        if($request->search!==NULL){
            $all_country=  Trip::where('slug3',$request->search)->first();
            log::info($all_country);
            log::info('YAHNA $all_country');
       $all_state=  Trip::where('state',$request->search)->first();
       $all_city=  Trip::where('city',$request->search)->first();
       log::info('YAHNA $all_state');
       log::info($all_state);
      // get all countries
   
      if($all_country){
          log::info('andar $all_country');
          $mycountry = $this->country->get_mycountries_country_having_events($all_country->slug3);
          $states = $this->country->get_state_country_having_events($all_country->slug3);
          $cities = $this->country->get_city_country_having_events($all_country->slug3);
          if($request->state!==NULL){
            log::info('if first null inside events');
            log::info($request);
         
            $mycities = $this->country->get_city_country_cities_having_events($request->state);
          
            return response([
                'events'=> [
                    'currency' => setting('regional.currency_default'),
                    'data' => $events_data,
                    'total' => $event_pagination['total'],
                    'per_page' => $event_pagination['per_page'],
                    'current_page' => $event_pagination['current_page'],
                    'last_page' => $event_pagination['last_page'],
                    'from' => $event_pagination['from'],
                    'to' => $event_pagination['to'],
                    'countries' => $countries,
                    'states' => $states,
                    'cities' => $mycities
                ],
            ], Response::HTTP_OK);
        } 
        else{
          return response([
              'events'=> [
                  'currency' => setting('regional.currency_default'),
                  'data' => $events_data,
                  'total' => $event_pagination['total'],
                  'per_page' => $event_pagination['per_page'],
                  'current_page' => $event_pagination['current_page'],
                  'last_page' => $event_pagination['last_page'],
                  'from' => $event_pagination['from'],
                  'to' => $event_pagination['to'],
                  'countries' => $mycountry,
                  'states' => $states,
                  'cities' => $cities
              ],
          ], Response::HTTP_OK);
        }
      }

      if($all_state){
          log::info('andar $all_state');
          $mycountry = $this->country->get_mycountries_country_having_events($all_state->slug3);
          $states = $this->country->get_mystate_country_having_events($all_state->state);
          $cities = $this->country->get_city_country_cities_having_events($all_state->state);
          log::info($cities);
          return response([
              'events'=> [
                  'currency' => setting('regional.currency_default'),
                  'data' => $events_data,
                  'total' => $event_pagination['total'],
                  'per_page' => $event_pagination['per_page'],
                  'current_page' => $event_pagination['current_page'],
                  'last_page' => $event_pagination['last_page'],
                  'from' => $event_pagination['from'],
                  'to' => $event_pagination['to'],
                  'countries' => $mycountry,
                  'states' => $states,
                  'cities' => $cities
              ],
          ], Response::HTTP_OK);
      } 
      if($all_city){
          $mycountry = $this->country->get_mycountries_country_having_events($all_city->slug3);
          $states = $this->country->get_mystate_country_having_events($all_city->state);
          $cities = $this->country->get_mycity_country_cities_having_events($all_city->city);
          log::info($cities);
          return response([
              'events'=> [
                  'currency' => setting('regional.currency_default'),
                  'data' => $events_data,
                  'total' => $event_pagination['total'],
                  'per_page' => $event_pagination['per_page'],
                  'current_page' => $event_pagination['current_page'],
                  'last_page' => $event_pagination['last_page'],
                  'from' => $event_pagination['from'],
                  'to' => $event_pagination['to'],
                  'countries' => $mycountry,
                  'states' => $states,
                  'cities' => $cities
              ],
          ], Response::HTTP_OK);
      } 

       }
        
        if($request->country!=='All'){
            log::info('andar $country hai');
            $states = $this->country->get_state_country_having_events($request->country);
           if($request->state!==NULL){
               log::info('if first null inside events');
               log::info($request);
              
               $cities = $this->country->get_city_country_cities_having_events($request->state);
               log::info($cities);
               return response([
                   'events'=> [
                       'currency' => setting('regional.currency_default'),
                       'data' => $events_data,
                       'total' => $event_pagination['total'],
                       'per_page' => $event_pagination['per_page'],
                       'current_page' => $event_pagination['current_page'],
                       'last_page' => $event_pagination['last_page'],
                       'from' => $event_pagination['from'],
                       'to' => $event_pagination['to'],
                       'countries' => $countries,
                       'states' => $states,
                       'cities' => $cities
                   ],
               ], Response::HTTP_OK);
           } 
           else{
           log::info('if next first inside events');
           log::info($request);
          
           $cities = $this->country->get_city_country_having_events($request->country);
           return response([
               'events'=> [
                   'currency' => setting('regional.currency_default'),
                   'data' => $events_data,
                   'total' => $event_pagination['total'],
                   'per_page' => $event_pagination['per_page'],
                   'current_page' => $event_pagination['current_page'],
                   'last_page' => $event_pagination['last_page'],
                   'from' => $event_pagination['from'],
                   'to' => $event_pagination['to'],
                   'countries' => $countries,
                   'states' => $states,
                   'cities' => $cities
               ],
           ], Response::HTTP_OK);
       }
       }
     
       else{
          
           log::info('else first inside events');
           log::info($request);
           log::info($events_data);
           $states = $this->country->get_state_having_events();
           $cities = $this->country->get_city_having_events();
           return response([
               'events'=> [
                   'currency' => setting('regional.currency_default'),
                   'data' => $events_data,
                   'total' => $event_pagination['total'],
                   'per_page' => $event_pagination['per_page'],
                   'current_page' => $event_pagination['current_page'],
                   'last_page' => $event_pagination['last_page'],
                   'from' => $event_pagination['from'],
                   'to' => $event_pagination['to'],
                   'countries' => $countries,
                   'states' => $allstates,
                   'cities' => $allcities
               ],
           ], Response::HTTP_OK);
       }
   }

   
    /**
     * 
     * 
     * Show single event
     *
     * @return array
     */
    public function show($country,$state,$city,$event, $view = 'eventmie::trip.show', $extra = [])
    {
       
        log::info('trips howevnets hai');
        log::info($event);
        $response = new Response('Set Cookie');

     
        // it is calling from model because used subquery
      $trip=  Trip::where('slug',$event)->first();
      $tripid=$trip->id;
      log::info($tripid);
      
      $currence=$trip->currency_name;
      $curre_name=substr($currence,0, 3);
      log::info($curre_name);

      $iternary = $this->iternary->get_days($tripid);
      log::info($iternary);
      $ticket = $this->ticket->get_tickets($tripid);
      log::info('tickets hai');
      log::info($ticket);
        // it is calling from model because used subquery
        $event = $this->event->get_event($event);
        log::info('trips howevnets hai');
        log::info($event);

        $detail=Userevent::where('email',$event->operator_email)->first();
        Log::info('Event ka data');
        Log::info($detail);
        $opphone = $detail->phone;
        Log::info('phone ka data');
        Log::info($opphone);

        if(!$event->status || !$event->publish)
            abort('404');

        // online event - yes or no
        $event                  = $event->makeVisible('online_location');
        // check event is online or not
        $event['online_location']    = (!empty($event['online_location'])) ? 1 : 0; 

        // check if category is disabled 
        $category            = $this->category->get_event_category($event['category_id']); 
        if(empty($category))
            abort('404');

        $tags                = $this->tag->get_event_tags($event['id']);
     
        $google_map_key      = setting('apps.google_map_key');

        // group by type
        $tag_groups          = [];
        if($tags)
            $tag_groups          = collect($tags)->groupBy('type');
        
        // check free ticket
     
        // event country
        $country            = $this->country->get_event_country($event['country_id']);

        // check event and or not 
        $ended  = false;

        // if event is repetitive then event will be expire according to end date
        if($event['repetitive'])
        {
            if(\Carbon\Carbon::now()->format('Y/m/d') > \Carbon\Carbon::createFromFormat('Y-m-d', $event['end_date'])->format('Y/m/d'))
                $ended = true;
        }
        else 
        {
            // none repetitive event so check start date for event is ended or not
            if(\Carbon\Carbon::now()->format('Y/m/d') > \Carbon\Carbon::createFromFormat('Y-m-d', $event['start_date'])->format('Y/m/d'))
                $ended = true;    
        }
        
        $is_paypal = $this->is_paypal();

        // get tickets
      

        return Eventmie::view($view, compact(
            'event', 'tag_groups','ticket' , 
            'ended', 'category', 'country', 'curre_name', 'google_map_key','iternary', 'is_paypal', 
             'extra','opphone'));
    }

    /**
     *  Event tag detail by title
     * 
     */

    public function tag($event_slug = null, $tag_title = null, $view = 'eventmie::tags.show', $extra = [])
    {
        $tag_title  = str_replace('-', ' ', strtolower(urldecode($tag_title)));
        $tag        = $this->tag->get_tag_by_title($tag_title);

        if(empty($tag))
            return error_redirect(__('eventmie-pro::em.tag').' '.__('eventmie-pro::em.not_found'));

        return Eventmie::view($view, compact( 'tag', 'extra'));
        
    }

     // get all categories
    public function categories()
    {
        $categories  = $this->category->get_categories();
        if(empty($categories))
        {
            return response()->json(['status' => false]);    
        }
        log::info('yeh category me aaraha hain a');
        log::info($categories);
        return response()->json(['status' => true, 'categories' => $categories ]);

    }   
    
    public function getcategory()
    {
        $categories  = $this->category->get_categories();

        if(empty($categories))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'categories' => $categories ]);


    }  

    // check session
    public function check_session()
    {
        session(['verify'=>1]);
        
        return response()->json(['status' => true]);
    }    

    
    // is_paypal
    
    protected function is_paypal()
    {
        // if have paypal keys then will show paypal payment option otherwise hide
        $is_paypal = 1;
        if(empty(setting('apps.paypal_secret')) || empty(setting('apps.paypal_client_id')))
            $is_paypal = 0;
        
        return $is_paypal;
        
    }

    // get tickets and it is public
    protected function get_tickets($event_id = null)
    {   
        $params    = [
            'event_id' =>  (int) $event_id,
        ];
        $tickets     = $this->ticket->get_event_tickets($params);
        
        // apply admin tax
        $tickets     = $this->admin_tax($tickets);

        // get the bookings by ticket for live availability check
        $bookedTickets  = $this->booking->get_seat_availability_by_ticket($params['event_id']);
        // make a associative array by ticket_id-event_start_date
        // to reduce the loops on Checkout popup
        $booked_tickets = [];
        foreach($bookedTickets as $key => $val)
        {
            // calculate total_vacant each ticket
            $ticket         = $tickets->where('id', $val->ticket_id)->first();

            // Skip if ticket not found or deleted
            if(!$ticket)
                continue;

            $booked_tickets["$val->ticket_id-$val->event_start_date"] = $val;

            // min 0 or else it'll throw JS error
            $total_vacant   = $ticket->quantity - $val->total_booked;
            $total_vacant   = $total_vacant < 0 ? 0 : $total_vacant;
            $booked_tickets["$val->ticket_id-$val->event_start_date"]->total_vacant = $total_vacant;

            // unset if total_vacant > global max_ticket_qty
            // in case of high values, it throw JS error
            $max_ticket_qty = (int) setting('booking.max_ticket_qty');
            if($total_vacant > $max_ticket_qty)
                unset($booked_tickets["$val->ticket_id-$val->event_start_date"]);
        }

        // sum all ticket's capacity
        $total_capacity = 0;
        foreach($tickets as $val)
            $total_capacity += $val->quantity;
        
        return [
            'tickets' => $tickets, 
            'currency' => setting('regional.currency_default'), 
            'booked_tickets'=>$booked_tickets,
            'total_capacity'=>$total_capacity,
        ];
    }

    /**
     *  admin tax apply on all tickets
     */

    public function bookingjs($id)
    {
        log::info('$input bookingjs ka');
        log::info($id);
        $dates = Carbon::now()->toDateString();
        log::info($dates);
        $trip= Trip::where('id',$id)->first();
            log::info($trip);
            $discountdate=$trip->discountdate;
            $discountdate2=$trip->discountdate2;
            $discountdate3=$trip->discountdate3;
            if($dates<=$discountdate){
                $cost=$trip->real_cost;
                log::info($cost);
                $discount=$trip->discount;
                $response = [   
                    'success' => true,
                    'message' => 'Category retrieved successfully for edit.',
                    'data' => $trip,
                    'discount' => $discount,
                    'trip' => $cost,
                ];
                return response()->json($response, 200);
            }
            else if($dates<=$discountdate2 && $discountdate<=$dates)
            {
                $cost=$trip->cost2;
                log::info($cost);
                $discount=$trip->discount2;
                $response = [   
                    'success' => true,
                    'message' => 'Categoty retrieved successfully for edit.',
                    'data' => $trip,
                    'discount' => $discount,
                    'trip' => $cost,
                ];
                return response()->json($response, 200);
            }
            else if($dates<=$discountdate3 && $discountdate2<=$dates)
            {
                $cost=$trip->cost3;
                log::info($cost);
                $discount=$trip->discount3;
                $response = [   
                    'success' => true,
                    'message' => 'Categoty retrieved successfully for edit.',
                    'data' => $trip,
                    'discount' => $discount,
                    'trip' => $cost,
                ];
                return response()->json($response, 200);
            }
            else{
              
                $cost=$trip->real_cost;
                log::info($cost);
                $discount=$trip->discount;
                $response = [   
                    'success' => true,
                    'message' => 'Categoty retrieved successfully for edit.',
                    'data' => $trip,
                    'discount' => $discount,
                    'trip' => $cost,
                ];
                return response()->json($response, 200);
            }
             
            }


            public function loginjs($id)
            {
                log::info('$input loginjs ka');
                log::info($id);
                $tripid=$id;
               
                Cookie::queue("utm_source", $tripid , 432000000);
                $get_all_cookies = Cookie::get();
                log::info($get_all_cookies);
                                       $response = [   
                            'success' => true,
                            'message' => 'Categoty retrieved successfully for edit.',
                            
                        ];
                        return response()->json($response, 200);
                     
                }
    public function rvsp(Request $request)
    {
      log::info('aa hjiji register ka hai');
      log::info($request);
      $login= Userevent::where('email',$request->emailid)->first();
     log::info($login);
     $loginphone= Userevent::where('phone',$request->mobile_no)->first();
     if($login){
     $response = [   
        'success' => true,
        'message' => 'Categoty retrieved successfully for edit.',
        'data' => $login
    ];
    
    Log::info("response to chala gya ");
    return response()->json($response, 200);

    }
    else if($loginphone)
    {
       
        log::info('a loginphone');
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $loginphone
        ];
        
        Log::info("response phone ka to chala gya ");
        return response()->json($response, 200);
    

    }
    
    else{
        log::info('a else');
        $slug=$request->name;;
        $mfilter = str_replace("","%",$slug);
      $email=  $request->emailid;
        $addrequest = new Userevent();
        $addrequest->name =  $request->name;
      $addrequest->phone =  $request->mobile_no;
      $addrequest->slug =  $mfilter;
       $addrequest->email =$request->emailid;
      $addrequest->password = Hash::make($request->userpassword);
       
       $addrequest->save();
       $response = [   
        'success' => false,
        'message' => 'Categoty retrieved successfully for edit.',
        'errordata' => $email
    ];
    
    Log::info("response to chala gya ");
    return response()->json($response, 200);

    }
}

public function bookingdetail($trip_id,$view = 'eventmie::trip.booking')
{
    log::info('$input bookingdetail ka');
    log::info($trip_id);
    $dates = Carbon::now()->toDateString();
    log::info($dates);
    $trip= Trip::where('id',$trip_id)->first();
        log::info($trip);
        $discountdate=$trip->discountdate;
        $discountdate2=$trip->discountdate2;
        $discountdate3=$trip->discountdate3;
        if($dates<=$discountdate){
            $cost=$trip->cost;
            $discount=$trip->discount;
            return Eventmie::view($view, compact('trip','cost','discount'));
        }
        else if($dates<=$discountdate2 && $discountdate<=$dates)
        {
            $cost=$trip->cost2;
            $discount=$trip->discount2;
            return Eventmie::view($view, compact('trip','cost','discount'));
        }
        else if($dates<=$discountdate3 && $discountdate2<=$dates)
        {
            $cost=$trip->cost3;
            $discount=$trip->discount3;
            return Eventmie::view($view, compact('trip','cost','discount'));
        }
        else{
            $cost=$trip->cost;
            $discount=$trip->discount;
            return Eventmie::view($view, compact('trip','cost','discount'));
        }
       
        }
public function booking(Request $request)
{
            log::info('$input booking ka');
            log::info($request);
            $trip_id= $request->tourtitle;
            $get_all_cookies = Cookie::get();
            log::info($get_all_cookies);
            log::info($get_all_cookies['name']);
           $detail=Userevent::where('email',$request->userid)->first();
           log::info($detail);
           $titles=Trip::where('id',$trip_id)->first();
           log::info($titles);
           $dharmu =  $titles->currency_id;

           $titles_mail=$titles->operator_email;
           log::info($titles_mail);
           $pubdetail=Userevent::where('email',$titles_mail)->first();
           $user_id=Userevent::where('email',$request->userid)->first();
           log::info($titles);
           $email=$titles->operator_email;
		    $admin=  Userevent::where('role_id',1)                 
           ->first();

           $currency_name=Currency::where('currency_id',$dharmu)->first();
           Log::info('currency_name');
           Log::info($currency_name);

           $name_currency=$currency_name->currencies;
          Log::info('name_currency');
           Log::info($name_currency);

           log::info($admin);
                 $sub=$titles->title;
                        $rvsps=Rvsp::orderBy('id', 'DESC')->take(1)->first();
                        log::info($rvsps);
                        log::info('get_all_cookies ke email pehle');
                            $input = $request->all();
                           log::info($input);  
                       $coo_name=$get_all_cookies['name'];
                       log::info($coo_name);  
                     if($input['userid']==NULL){
                        $users_id=Userevent::where('email',$coo_name)->first();
                        log::info('OperatorMailsend ke email pehle');
                        $OperatorMailsend = array(  
                            'email' => $email,
                             'name' => $titles->operator_name,
                             'title' => $titles->title,
                             'country' => $titles->slug3,
                             'state' => $titles->slug2,
                             'city' => $titles->slug1,
                             'start_date' => $titles->start_date,
                             'end_date' => $titles->end_date,
                             'days' => $titles->days,
                             'cost' => $titles->cost,
                             'totalcost' => $request->paycost,
                                   'username' => $users_id->name,
                             'useremail' => $coo_name,
                             'mobile' => $request->Phoneno,
                             'address' => $users_id->address,
                             'singcurrency' => $name_currency,
                          
                      );
                      log::info('OperatorMailsend  dusra ke email pehle');
                      log::info($OperatorMailsend);
                      $UserMailsend = array(  
                          'email' => $coo_name,
                          'title' => $titles->title,
                          'name' => $users_id->name,
                          'mobile' => $request->Phoneno,
                          'address' => $users_id->address,
                          'touremail' => $email,
                          'operatorname' => $titles->operator_name,
                          'country' => $titles->slug3,
                          'state' => $titles->slug2,
                          'city' => $titles->slug1,
                          'start_date' => $titles->start_date,
                          'end_date' => $titles->end_date,
                          'days' => $titles->days,
                          'cost' => $titles->cost,
                          'totalcost' => $request->paycost,
                          'singcurrency' => $name_currency,
                       
                      );
                      log::info('yaha tak user');
                      log::info($UserMailsend);
                         $adminMailsend = array(  
                             'email' => $admin->email,
                             'name' => $titles->operator_name,
                             'title' => $titles->title,
                             'country' => $titles->slug3,
                             'state' => $titles->slug2,
                             'city' => $titles->slug1,
                             'start_date' => $titles->start_date,
                             'end_date' => $titles->end_date,
                             'days' => $titles->days,
                             'cost' => $titles->cost,
                             'totalcost' => $request->paycost,
                             'username' => $users_id->name,
                             'useremail' => $coo_name,
                             'mobile' => $request->Phoneno,
                             'address' => $users_id->address,
                             'touremail' => $email,
                           
                            
                      );
                      Mail::to($OperatorMailsend['email'])->send(new Confirm_msg($OperatorMailsend));
                      log::info('after mail cokies if');
                      
           Mail::to($UserMailsend['email'])->send(new  Confirm_message($UserMailsend));
           log::info('after mail cokies sec  if');
              Mail::to($adminMailsend['email'])->send(new Confirm_messages($adminMailsend));
                      log::info('after mail first cooj if');
               
                      $myticket= Rvsp::orderBy('id', 'DESC')->latest()->first();
                      if($myticket->Order_id==NULL){

                        $dates = Carbon::now()->toDateString();
                      $addrequest = new Rvsp();
                    DB::table('trips')
                  ->where('id', $request->tourtitle)
                  ->increment('Subscriber', 1);
                  $addrequest->emailid= $coo_name;
                  $addrequest->Phoneno =  $request->Phoneno;
                  $addrequest->Name =  $users_id->name;
                   $addrequest->status =  1;
                   $addrequest->Order_id = "12345670";
                   $addrequest->bookingdate=$dates;
                   $addrequest->visit_trip =  1;
                    $addrequest->tour_id =  $trip_id;
                   $addrequest->TripHeading =  $sub;
                                 $addrequest->save();
                      }
                                 else{
                                    $dates = Carbon::now()->toDateString();
                                $ticket= Rvsp::orderBy('id', 'DESC')->latest()->first();
                                     $fticket=$ticket->Order_id;
                                     $orderss=$fticket+1;

                                     $addrequest = new Rvsp();
                                     DB::table('trips')
                                   ->where('id', $request->tourtitle)
                                   ->increment('Subscriber', 1);
                                   $addrequest->emailid= $coo_name;
                                   $addrequest->Phoneno =  $request->Phoneno;
                                   $addrequest->Name =  $users_id->name;
                                    $addrequest->status =  1;
                                    $addrequest->Order_id = $orderss;
                                    $addrequest->bookingdate=$dates;
                                     
                                    $addrequest->visit_trip =  1;
                                     $addrequest->tour_id =  $trip_id;
                                    $addrequest->TripHeading =  $sub;
                                    $addrequest->save();
                                 }

      if($users_id){
        log::info('after detail first cooj if');
       $response = [   
           'success' => true,
           'message' => 'Categoty retrieved successfully for edit.',
           'data' => $users_id,
           'person' => $request->person,
           'phone' => $request->Phoneno,
           'confirm' => $rvsps,
           'pubdetail' => $pubdetail,
          
           'paycost' => $request->paycost,
           
           
       ];
      Log::info("response to cookie chala gya ");
      return response()->json($response, 200);
        
      }
                     }
                     else{
                        log::info('Confirm_messages ke array email pehle');
                        $OperatorMailsend = array(  
                            'email' => $email,
                             'name' => $titles->operator_name,
                             'title' => $titles->title,
                             'country' => $titles->slug3,
                             'state' => $titles->slug2,
                             'city' => $titles->slug1,
                             'start_date' => $titles->start_date,
                             'end_date' => $titles->end_date,
                             'days' => $titles->days,
                             'cost' => $titles->cost,
                             'totalcost' => $request->paycost,
                      
                             'username' => $user_id->name,
                             'useremail' => $request->userid,
                             'mobile' => $request->Phoneno,
                             'address' => $user_id->address,
                             'singcurrency' => $name_currency,
                          
                      );
                      $UserMailsend = array(  
                          'email' => $request->userid,
                          'title' => $titles->title,
                          'name' => $user_id->name,
                          'mobile' => $request->Phoneno,
                          'address' => $user_id->address,
                          'touremail' => $email,
                          'operatorname' => $titles->operator_name,
                          'country' => $titles->slug3,
                          'state' => $titles->slug2,
                          'city' => $titles->slug1,
                          'start_date' => $titles->start_date,
                          'end_date' => $titles->end_date,
                          'days' => $titles->days,
                          'cost' => $titles->cost,
                          'totalcost' => $request->paycost,
                          'singcurrency' => $name_currency,
                         
                       
                      );
                      log::info('yaha tak user');
                      log::info($UserMailsend);
                         $adminMailsend = array(  
                             'email' => $admin->email,
                             'name' => $titles->operator_name,
                             'title' => $titles->title,
                             'country' => $titles->slug3,
                             'state' => $titles->slug2,
                             'city' => $titles->slug1,
                             'start_date' => $titles->start_date,
                             'end_date' => $titles->end_date,
                             'days' => $titles->days,
                             'cost' => $titles->cost,
                             'totalcost' => $request->paycost,
                             'username' => $user_id->name,
                             'useremail' => $request->userid,
                             'mobile' => $request->Phoneno,
                             'address' => $user_id->address,
                             'touremail' => $email,
                             
                            
                      );
                      Mail::to($OperatorMailsend['email'])->send(new Confirm_msg($OperatorMailsend));
                      log::info('after mail iffg');
                      
           Mail::to($UserMailsend['email'])->send(new  Confirm_message($UserMailsend));
           log::info('after mail if');
              Mail::to($adminMailsend['email'])->send(new Confirm_messages($adminMailsend));
                      log::info('after mail if');
                      $myticket= Rvsp::orderBy('id', 'DESC')->latest()->first();
                      if($myticket->Order_id==NULL){

                        $dates = Carbon::now()->toDateString();
                       
                      $addrequest = new Rvsp();
                    DB::table('trips')
                  ->where('id', $request->tourtitle)
                  ->increment('Subscriber', 1);
                  $addrequest->emailid= $request->userid;
                  $addrequest->Phoneno =  $request->Phoneno;
                  $addrequest->Name =  $user_id->name;
                   $addrequest->status =  1;
                   $addrequest->Order_id = "12345670";
                   $addrequest->bookingdate=$dates;
                   $addrequest->visit_trip =  1;
                    $addrequest->tour_id =  $trip_id;
                   $addrequest->TripHeading =  $sub;
                                 $addrequest->save();
                                }
                                else{
                                    $dates = Carbon::now()->toDateString();
                                $ticket= Rvsp::orderBy('id', 'DESC')->latest()->first();
                                     $fticket=$ticket->Order_id;
                                     $orderss=$fticket+1;
                                     $addrequest = new Rvsp();
                                     DB::table('trips')
                                   ->where('id', $request->tourtitle)
                                   ->increment('Subscriber', 1);
                                   $addrequest->emailid= $request->emailid;
                                   $addrequest->Phoneno =  $request->Phoneno;
                                   $addrequest->Name =  $user_id->name;
                                   $addrequest->Order_id = $orderss;
                                   $addrequest->bookingdate=$dates;
                                    $addrequest->status = 1;
                                    $addrequest->visit_trip =  1;
                                     $addrequest->tour_id =  $trip_id;
                                    $addrequest->TripHeading =  $sub;
                                                  $addrequest->save();
                                               }

      if($detail){
       $response = [   
           'success' => true,
           'message' => 'Categoty retrieved successfully for edit.',
           'data' => $detail,
           'person' => $request->person,
           'phone' => $request->Phoneno,
           'confirm' => $rvsps,
           'pubdetail' => $pubdetail,
          
           'paycost' => $request->paycost,
           
           
       ];
      Log::info("response to chala gya ");
      return response()->json($response, 200);
        
      }
                     }
                       

}
public function bookingdetailall($trip_id,$view = 'eventmie::trip.booking')
{
    log::info('$input bookingdetail ka');
    log::info($trip_id);
    $get_all_cookies = Cookie::get();
    log::info($get_all_cookies);
     $email=$get_all_cookies['email'];
     log::info($email);
    $dates = Carbon::now()->toDateString();
    log::info($dates);
    $trip= Trip::where('id',$trip_id)->first();
        log::info($trip);
        $discountdate=$trip->discountdate;
        $discountdate2=$trip->discountdate2;
        $discountdate3=$trip->discountdate3;
        if($dates<=$discountdate){
            $cost=$trip->cost;
            $discount=$trip->discount;
            return Eventmie::view($view, compact('trip','cost','discount','email'));
        }
        else if($dates<=$discountdate2 && $discountdate<=$dates)
        {
            $cost=$trip->cost2;
            $discount=$trip->discount2;
            return Eventmie::view($view, compact('trip','cost','discount','email'));
        }
        else if($dates<=$discountdate3 && $discountdate2<=$dates)
        {
            $cost=$trip->cost3;
            $discount=$trip->discount3;
            return Eventmie::view($view, compact('trip','cost','discount','email'));
        }
        else{
            $cost=$trip->cost;
            $discount=$trip->discount;
            return Eventmie::view($view, compact('trip','cost','discount','email'));
        }
       
        }

public function rvspstore(Request $request)
    {
        log::info('$input rvspstore ka');
        log::info($request);
         $logins= Userevent::where('email',$request->useremail)->first();
            log::info($logins);
            if(!Hash::check($request->get ( 'userpassword') ,$logins->password  )){
                $mylogins='wels';
                log::info('a loginphone');
                $response = [   
                    'success' => false,
                    'message' => 'Categoty retrieved successfully for edit.',
                    'mydata' => $mylogins
                ];
                
                Log::info("response phone ka to chala gya ");
                return response()->json($response, 200);
            }
            else{
                if($logins){
                    $response = [   
                        'success' => true,
                        'message' => 'Categoty retrieved successfully for edit.',
                        'data' => $logins
                    ];
                    Log::info("response to chala gya ");
                    return response()->json($response, 200);
                }
          else 
          {
            $mylogins='wels';
              log::info('a welsloginphone');
              $response = [   
                  'success' => false,
                  'message' => 'Categoty retrieved successfully for edit.',
                  'mydata' => $mylogins
              ];
              
              Log::info("response phone ka to chala gya ");
              return response()->json($response, 200);
          
      
          }
        }
}

public function loginstore(Request $request)
    {
        log::info('$input loginstore ka');
        log::info($request);
         $logins= Userevent::where('email',$request->email)->first();
         $mypassword = Hash::make($request->password);
         log::info($logins->password);
         log::info($mypassword);
            log::info($logins);
           if(!Hash::check($request->get ('password') ,$logins->password  ))
           {
            $mylogins='wels';
            log::info('a Hash::check');
            $response = [   
                'success' => false,
                'message' => 'Categoty retrieved successfully for edit.',
                'mydata' => $mylogins
            ];
            
            Log::info("response phone ka to chala gya ");
            return response()->json($response, 200);
        
        }

        else{
            if($logins){
               $emails= $request->email;
                $cookie = Cookie::make('mail', $emails, 120);
                $val = Cookie::get('mail');
              
                log::info($val);
                $get_all_cookies = Cookie::get();
                log::info($get_all_cookies);
                $response = [   
                    'success' => true,
                    'message' => 'Categoty retrieved successfully for edit.',
                    'data' => $logins
                ];
                Log::info("response to chala gya ");
                return response()->json($response, 200);

            }
          else 
          {
            $mylogins='wels';
              log::info('a loginphone');
              $response = [   
                  'success' => false,
                  'message' => 'Categoty retrieved successfully for edit.',
                  'mydata' => $mylogins
              ];
              
              Log::info("response phone ka to chala gya ");
              return response()->json($response, 200);
          
      
          }
        }

}
public function mobilestore(Request $request)
    {
        log::info('$input mobilestore ka');
        log::info($request);
         $logins= Userevent::where('phone',$request->Phoneno)->first();
            log::info($logins);
            if($logins){
            $response = [   
                'success' => true,
                'message' => 'Categoty retrieved successfully for edit.',
                'data' => $logins
            ];
            Log::info("response to chala gya ");
            return response()->json($response, 200);
          }
}


public function phonervsp(Request $request)
    {
        log::info('$input mobilestore ka');
        log::info($request);
         $logins= Userevent::where('phone',$request->mobiles_no)->first();
            log::info($logins);
            if($logins){
            $response = [   
                'success' => true,
                'message' => 'Categoty retrieved successfully for edit.',
                'data' => $logins
            ];
            Log::info("response to chala gya ");
            return response()->json($response, 200);
          }
}
public function mygoogle(Request $request)
    {
        log::info('$input mygoogle ka');
        log::info($request);
        $social="google";
        $cookie = Cookie::make('social', $social, 120);
        $val = Cookie::get('social');
      
        
        $get_all_cookies = Cookie::get();
        log::info($get_all_cookies);
            Log::info("response to chala gya ");

           
            return redirect()->route('eventmie.oauth_login',['social' => 'google']);
          
}


    protected function admin_tax($tickets = [])
    {
        // get admin taxes
        $admin_tax  = $this->tax->get_admin_taxes();
        
        // if admin taxes are not existed then return
        if($admin_tax->isEmpty())
            return $tickets;
        
        // it work when tickets show for purchasing
        // for multiple tickets 
        if($tickets instanceof \Illuminate\Database\Eloquent\Collection)
        {   
            // push admin taxes in every tickets
            foreach($tickets as $key => $value)
            {
                foreach($admin_tax as $ad_k => $ad_v)
                {
                    $value->taxes->push($ad_v);  
                }
            }
        }    
        else
        {   
            // it work when booking data prepare
            // for single ticket 
            foreach($admin_tax as $ad_k => $ad_v)
            {
                $tickets['taxes'] = $tickets['taxes']->push($ad_v);
            }
        }  
        
        return $tickets;
    } 
}


