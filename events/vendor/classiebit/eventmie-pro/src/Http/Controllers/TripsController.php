<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Log;
use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Trip;
use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Models\Ticket;
use Classiebit\Eventmie\Models\Trip_Ticket;
use Classiebit\Eventmie\Models\Banner;
use Classiebit\Eventmie\Models\Tag;
use Classiebit\Eventmie\Models\Tripcategory;
use Classiebit\Eventmie\Models\Post;
use Carbon\Carbon;

class TripsController extends Controller
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
    
        $this->trip            = new Trip;
        $this->ticket           = new Trip_Ticket;
        $this->banner           = new Banner;
        $this->tag              = new Tag;
        $this->user             = new User;
        $this->category         = new Tripcategory;
        $this->post             = new Post;
    }


    // get featured events
    public function index($view = 'eventmie::tripS', $extra = [])
    {
   log::info('inside index trip welcome');
        $featured_events     = $this->get_featured_events();
        $top_selling_events  = $this->get_top_selling_events();
        $upcomming_events    = $this->get_upcomming_events();
        $banners             = $this->banner->get_banners();
        $categories          = $this->category->get_categories();
        $currency            = setting('regional.currency_default');
        $cities_events       = $this->trip->get_cities_events();
        
        //get blog for welcome page
        $posts               = $this->post->index();
        log::info('ab data index trip welcome');
        log::info($upcomming_events);
        log::info($top_selling_events);
        log::info('$featured_events');
        log::info($featured_events);
        
        return Eventmie::view($view, 
            compact(
                'featured_events', 'top_selling_events', 
                'upcomming_events', 'banners',
                'categories', 'posts', 'currency', 'cities_events', 
                'extra'
            ));
            
    }

    // get featured events API
    protected function get_featured_events()
    {
        $featured_events  = $this->trip->get_featured_events();
        
        $event_ids       = [];

        foreach($featured_events as $key => $value)
            $event_ids[] = $value->id;

        // pass events ids
        // tickets
        $events_tickets     = $this->ticket->get_events_tickets($event_ids);
log::info('first events_tickets');
        $events_data             = [];
        foreach($featured_events as $key => $value)
        {
            // online event - yes or no
            $value                  = $value->makeVisible('online_location');
            // check event is online or not
            $value->online_location    = (!empty($value->online_location)) ? 1 : 0; 

            $events_data[$key]             = $value;
            
           foreach($events_tickets as $key1 => $value1)
            {
                // check relevant event_id with ticket id
                if($value->id == $value1['ticket_event_id'])
                {
                    $events_data[$key]->tickets[]       = $value1;
                }
            }
        }

        return  $events_data;
        
    }

    // get top selling events API
    protected function get_top_selling_events()
    {
        $top_selling_events  = $this->trip->get_top_selling_events();

        $event_ids           = [];

        foreach($top_selling_events as $key => $value)
        {
            if($value->total_booking)
                $event_ids[] = $value->id;
        }
        
        // pass events ids
        // tickets
        $events_tickets     = $this->ticket->get_events_tickets($event_ids);
        log::info('second events_tickets');
        $events_data             = [];
        foreach($top_selling_events as $key => $value)
        {
            if($value->total_booking)
            {
                // online event - yes or no
                $value                  = $value->makeVisible('online_location');
                // check event is online or not
                $value->online_location    = (!empty($value->online_location)) ? 1 : 0; 

                $events_data[$key]     = $value;
            }
            
           foreach($events_tickets as $key1 => $value1)
            {
                // check relevant event_id with ticket id
                if($value->id == $value1['ticket_event_id'])
                {
                    $events_data[$key]->tickets[]       = $value1;
                }
            }
        }

        return  $events_data;
        
    }

    // get upcomming events
    protected function get_upcomming_events()
    {
        $upcomming_events  = $this->trip->get_upcomming_events();

        $event_ids           = [];

        foreach($upcomming_events as $key => $value)
            $event_ids[] = $value->id;

        // pass events ids
        // tickets
        $events_tickets     = $this->ticket->get_events_tickets($event_ids);
        log::info('third events_tickets');
        $events_data             = [];
        // foreach($upcomming_events as $key => $value)
        // {   
        //     // online event - yes or no
        //     $value                  = $value->makeVisible('online_location');
        //     // check event is online or not
        //     $value->online_location    = (!empty($value->online_location)) ? 1 : 0; 

        //     $events_data[$key]             = $value;
            
        //    foreach($events_tickets as $key1 => $value1)
        //     {
        //         // check relevant event_id with ticket id
        //         if($value->id == $value1['ticket_event_id'])
        //         {
        //             $events_data[$key]->tickets[]       = $value1;
        //         }
        //     }
        // }
        
        return  $events_data;
        
    }
}
