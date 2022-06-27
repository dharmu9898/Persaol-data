<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Ticket;
use Classiebit\Eventmie\Models\Category;
use Classiebit\Eventmie\Models\Country;
use Classiebit\Eventmie\Models\Schedule;
use Classiebit\Eventmie\Models\Tag;
use Classiebit\Eventmie\Models\Tax;
use Classiebit\Eventmie\Models\Booking;
use DB;
use Mail;
use Log;
use App\Mail\OperatorMail;
use App\Mail\OperatorMails;
use Classiebit\Eventmie\Models\Rvsp;
use Classiebit\Eventmie\Models\Mycountry;
use Classiebit\Eventmie\Models\State;
use Classiebit\Eventmie\Models\City;


class EventsController extends Controller
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
    
        $this->event    = new Event;

        $this->ticket   = new Ticket;

        $this->category = new Category;

        $this->country  = new Country;
		
		 $this->countries  = new Mycountry;

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
    public function index($view = 'eventmie::events.index', $extra = [])
    {
        // get prifex from eventmie config
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');

        return Eventmie::view($view, compact('path', 'extra'));
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
                if($value['name'] == $category)
                    $category_id = $value['id'];
            }
        }
    
        // search country id
        if(!empty($country))
        {
            $countries = $this->countries->get_mycountries();
    
            foreach($countries as $key=> $value)
            {
                if($value['country_name'] == $country)
                    $country_id = $value['country_id'];
            }
        }
    
        if(!empty($state))
        {
            $states = $this->state->get_mystates();
    
            foreach($states as $key=> $value)
            {
                if($value['state_name'] == $state)
                    $state_id = $value['state_id'];
            }
        }
    
        if(!empty($city))
        {
            $cities = $this->city->myget_cities();
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
        log::info('yaha tak aa events aa filter aa gaya hai');
        log::info($filters);
        $events          = $this->event->events($filters);
        
        log::info($events);
        $event_ids       = [];

        foreach($events as $key => $value)
            $event_ids[] = $value->id;

        // pass events ids
        // tickets
        $events_tickets     = $this->ticket->get_events_tickets($event_ids);

        $events_data             = [];
        foreach($events as $key => $value)
        {
            // online event - yes or no
            $value                  = $value->makeVisible('online_location');
            // check event is online or not
            $value->online_location    = (!empty($value->online_location)) ? 1 : 0; 

            $events_data[$key]             = $value;
            
           foreach($events_tickets as $key1 => $value1)
            {
                // check relevant event_id with ticket id
                if($value->id == $value1['event_id'])
                {
                    $events_data[$key]->tickets[]       = $value1;
                }
            }
        }
        
        // set pagination values
        $event_pagination = $events->jsonSerialize();
        log::info(' mera $events');
        log::info($events);
        $countries = $this->countries->get_countries_having_events();
        $allstates = $this->countries->get_state_having_events();
        $allcities = $this->countries->get_city_having_events();
        if($request->search!==NULL){
            $all_country=  Event::where('slug3',$request->search)->first();
            log::info($all_country);
            log::info('YAHNA $all_country');
       $all_state=  Event::where('state',$request->search)->first();
       $all_city=  Event::where('city',$request->search)->first();
       log::info('YAHNA $all_state');
       log::info($all_state);
      // get all countries
   
      if($all_country){
          log::info('andar $all_country');
          $mycountry = $this->countries->get_mycountries_country_having_events($all_country->slug3);
          $states = $this->countries->get_state_country_having_events($all_country->slug3);
          $cities = $this->countries->get_city_country_having_events($all_country->slug3);
          if($request->state!==NULL){
            log::info('if first null inside events');
            log::info($request);
         
            $mycities = $this->countries->get_city_country_cities_having_events($request->state);
          
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
          $mycountry = $this->countries->get_mycountries_country_having_events($all_state->slug3);
          $states = $this->countries->get_mystate_country_having_events($all_state->state);
          $cities = $this->countries->get_city_country_cities_having_events($all_state->state);
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
          $mycountry = $this->countries->get_mycountries_country_having_events($all_city->slug3);
          $states = $this->countries->get_mystate_country_having_events($all_city->state);
          $cities = $this->countries->get_mycity_country_cities_having_events($all_city->city);
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
            $states = $this->countries->get_state_country_having_events($request->country);
           if($request->state!==NULL){
               log::info('if first null inside events state');
               log::info($request);
              
               $cities = $this->countries->get_city_country_cities_having_events($request->state);
               log::info($cities);
               log::info('if first aa events_data inside events state');
               log::info($events_data);
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
          
           $cities = $this->countries->get_city_country_having_events($request->country);
           log::info('if first country aa events_data inside events state');
           log::info($events_data);
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
           $states = $this->countries->get_state_having_events();
           $cities = $this->countries->get_city_having_events();
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



    public function rvsp(Request $request)
    {
      log::info('aa hjiji');
      log::info($request);
      $trip_id= $request->TripTitle;
      log::info($trip_id);
      $this->validate($request, [
                 
                'emailid'  =>  'required',
            'g-recaptcha-response' => 'required|captcha',
           ]);

           $titles=Event::where('id',$trip_id)->first();
           log::info($titles);
           $email=$titles->operator_email;
           

         
                    $sub=$titles->title;
                     $OperatorMailsend = array(  
                         'email' => $email
    );
              $OperatorMailsends = array(  
             'emailid' => $request->emailid 
       );

                            $data = $request->emailid;
                             log::info($data);
                       $email = Rvsp::where('emailid',$data)->where('TripHeading',$sub)->first();
                       $myemail = Rvsp::where('emailid',$data)->where('TripHeading',$sub)->pluck('emailid');
                       $visit_trip = Rvsp::where('emailid',$data)->pluck('emailid');

    if(!$myemail->isEmpty() )
      {
          $trip=$email->TripHeading;
            log::info($trip);
            log::info($sub);
        if($trip==$sub){
          log::info('inside it');
          DB::table('rvsps')
          ->where('emailid', $data)
          ->increment('visit_trip', 1);
          DB::table('rvsps')
        ->where('emailid', $data)->where('TripHeading', $sub)
        ->increment('users', 1);
        }
        else{
          $addrequest = new Rvsp();
          log::info('inside of inside else');
          DB::table('rvsps')
          ->where('emailid', $data)
          ->increment('visit_trip', 1);
          DB::table('events')
          ->where('title', $sub)
          ->increment('publish', 1);
          $addrequest->emailid= $request->emailid;
           $addrequest->Phoneno =  $request->Phoneno;
           $addrequest->Name =  $request->Name;
           $addrequest->status =  1;
           $addrequest->Address =  $request->Address;
           
           $addrequest->TripHeading =  $sub;
           $addrequest->save();
        }
      }
      else{
        if(!$visit_trip->isEmpty() )
        {
          log::info('inside visit else');
          $addrequest = new Rvsp();
        $counts = DB::table('rvsps')
          ->where('emailid', $data)
          ->count('emailid');
          DB::table('events')
          ->where('title', $sub)
          ->increment('publish', 1);
           $addrequest->emailid= $request->emailid;
           $addrequest->Phoneno =  $request->Phoneno;
           $addrequest->Name =  $request->Name;
           $addrequest->status =  1;
           $addrequest->visit_trip =  $counts+1;
           $addrequest->Address =  $request->Address;
         
           $addrequest->TripHeading =  $sub;
                         $addrequest->save();
        }
        else{
        log::info('inside else');
        $addrequest = new Rvsp();
        DB::table('events')
        ->where('id', $trip_id)
        ->increment('publish', 1);
         $addrequest->emailid= $request->emailid;
         $addrequest->Phoneno =  $request->Phoneno;
         $addrequest->Name =  $request->Name;
         $addrequest->status =  1;
         $addrequest->visit_trip =  1;
         $addrequest->Address =  $request->Address;
         
         $addrequest->tour_id =  $trip_id;
         $addrequest->TripHeading =  $sub;
                       $addrequest->save();
                      }
      }
      return redirect()->back()->with('success', 'You have been successfully confirmed for this Event.Event organiser would communicate with you soon with more details.');
    }
    
    public function rvspstore(Request $request)
    {
      $input = $request->useremail;
      log::info($input);
      $trip_id= $request->tourtitle;
     
 
      
      $userdata = DB::table('rvsps')->where('emailid', $input)->first();
      $usersdata = DB::table('rvsps')->where('emailid', $input)->pluck('emailid');

      $titles=Event::where('id',$trip_id)->first();
      $email=$titles->operator_email;
      $Tourtitle=$titles->title;
      if(!$usersdata->isEmpty() )
      {
      $name=$userdata->Name;
      $address=$userdata->Address;
      $phone=$userdata->Phoneno;
      $TripHeading =  $Tourtitle;
      log::info('inside show stores okk');
              $this->validate($request, [
                'useremail'  =>  'required',
                'g-recaptcha-response' => 'required|captcha',
 
        ]);
        $sub=$Tourtitle;
                            $data = $request->useremail;
                          log::info($data);
                    $email = Rvsp::where('emailid',$data)->where('TripHeading',$sub)->first();
                    $myemail = Rvsp::where('emailid',$data)->where('TripHeading',$sub)->pluck('emailid');
                    $visit_trip = Rvsp::where('emailid',$data)->pluck('emailid');
                      if(!$myemail->isEmpty() )
{
             $trip=$email->TripHeading;
             log::info($trip);
             log::info($sub);
        if($trip==$sub){
        log::info('inside it');
        DB::table('rvsps')
        ->where('emailid', $data)
        ->increment('visit_trip', 1);
        DB::table('rvsps')
        ->where('emailid', $data)->where('TripHeading', $sub)
        ->increment('users', 1);
        }
        else{
        $addrequest = new Rvsp();
        log::info('inside of inside else');
        DB::table('rvsps')
        ->where('emailid', $data)
        ->increment('visit_trip', 1);
        DB::table('events')
        ->where('id', $trip_id)
        ->increment('publish', 1);
          $addrequest->emailid= $request->useremail;
        $addrequest->Phoneno =  $phone;
        $addrequest->Name =  $name;
        $addrequest->status =  1;
        $addrequest->Address =  $address;
        $addrequest->TripHeading =  $sub;
        $addrequest->password = $request->userpassword;
                      $addrequest->save();
      }
      }
      else{
      if(!$visit_trip->isEmpty() )
      {
      log::info('inside visit else');
      $addrequest = new Rvsp();
      $counts = DB::table('rvsps')
      ->where('emailid', $data)
      ->count('emailid');
      DB::table('events')
      ->where('id', $trip_id)
      ->increment('publish', 1);
                    $addrequest->save();
                    $addrequest->emailid= $request->useremail;
                    $addrequest->Phoneno =  $phone;
                    $addrequest->Name =  $name;
                    $addrequest->status =  1;
      $addrequest->visit_trip =  $counts+1;
      $addrequest->Address =  $address;
      $addrequest->TripHeading =  $sub;
      $addrequest->password = $request->userpassword;
                    $addrequest->save();
      }
      else{
      log::info('inside else');
      $addrequest = new Rvsp();
      DB::table('trips')
      ->where('id', $trip_id)
      ->increment('publish', 1);
      $addrequest->emailid= $request->useremail;
                    $addrequest->Phoneno =  $phone;
                    $addrequest->Name =  $name;
                    $addrequest->status =  1;
      $addrequest->visit_trip =  1;
      $addrequest->Address =  $address;
      $addrequest->TripHeading =  $sub;
      $addrequest->password = $request->userpassword;
                  $addrequest->save();
                  }
      }
      return redirect()->back()->with('success', 'You have been successfully confirmed for this Event.Event organiser would communicate with you soon with more details.');
    }
    else{
      log::info('inside else register');
      return redirect()->back()->with('success', 'You  email not exist please sign up.');
    }
    }
    /**
     * Show single event
     *
     * @return array
     */
    public function show(Event $event, $view = 'eventmie::events.show', $extra = [])
    {
        // it is calling from model because used subquery
        $event = $this->event->get_event($event->slug);

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
        $max_ticket_qty      = (int) setting('booking.max_ticket_qty'); 
        $google_map_key      = setting('apps.google_map_key');

        // group by type
        $tag_groups          = [];
        if($tags)
            $tag_groups          = collect($tags)->groupBy('type');
        
        // check free ticket
        $free_tickets        = $this->ticket->check_free_tickets($event['id']);

        // event country
        $country            = $this->countries->get_event_country($event['country_id']);

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
        $tickets_data   = $this->get_tickets($event['id']);
        $tickets        = $tickets_data['tickets'];
        $currency       = $tickets_data['currency'];
        $booked_tickets = $tickets_data['booked_tickets'];
        $total_capacity = $tickets_data['total_capacity'];

        return Eventmie::view($view, compact(
            'event', 'tag_groups', 'max_ticket_qty', 'free_tickets', 
            'ended', 'category', 'country', 'google_map_key', 'is_paypal', 
            'tickets', 'currency', 'booked_tickets', 'total_capacity', 'extra'));
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
