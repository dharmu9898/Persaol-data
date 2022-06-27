<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Log;
use Classiebit\Eventmie\Models\Tag;
use Classiebit\Eventmie\Models\Booking;
use Classiebit\Eventmie\Models\commission;
use Classiebit\Eventmie\Models\User;
class Trip extends Model
{
    
    protected $connection = 'mysql2';
        protected $table = 'trips';

    protected $guarded = [];
    protected $hidden  = ['online_location'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     *  total events
     */

    public function total_events()
    {
        return Event::where(['status' => 1])->count();
    }
    public function save_event($params = [], $event_id = null)
    {
       // if have no event id then create new event
       return Trip::updateOrCreate(
            ['id' => $event_id],
            $params
        );
    }
    public function get_my_date($params)
    {   
        log::info($params);
        $myid=$params['event_id'];
        log::info($myid);
        return Trip::select('trips.*')->from('trips')
            
            ->Where(['id' => $params['event_id']])    
          
            ->first();
    }
    
    /* Get event tickets */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // get event
    public function get_trip($slug = null, $event_id = null)
    {   
        return Trip::select('trips.*')->from('trips')
            ->where(['slug' => $slug])
            ->orWhere(['id' => $event_id])    
              ->first();
    }

    
    // check event id that event id have login user or not    
    public function get_user_event($event_id = null, $user_id = null)
    {
        log::info('yanh a$event_id');
        log::info($event_id);
        return Trip::select('trips.*')->from('trips')
        ->where(['id' => $event_id, 'user_id' => $user_id ])
                    
                    ->first();
    }

    // create user event
   

    
    // add tags to event
  
    

    /**
     * Get events with 
     * pagination and custom selection
     * 
     * @return string
     */
    public function events($params  = [])
    {   
        $query = Trip::query(); 
        log::info('yaha evenst fun');
     
        log::info($params['search']);
       
        $query
        ->leftJoin("trip_categories", "trip_categories.id", '=', "trips.category_id")
        ->select(["trips.*", "trip_categories.category as category_name"]);

        if(!empty($params['search']))    
        {
            $query
            ->whereRaw("( title LIKE '%".$params['search']."%' 
                OR venue LIKE '%".$params['search']."%' OR country_state LIKE '%".$params['search']."%' OR country_state_city LIKE '%".$params['search']."%' OR slug3 LIKE '%".$params['search']."%')");
        }
 
        $query
        ->selectRaw("(SELECT CN.country_name FROM countries CN WHERE CN.country_id = trips.country_id) country_name")
        ->selectRaw("(SELECT SN.state_id FROM states SN WHERE SN.state_id = trips.state_id) state_name")
        ->selectRaw("(SELECT CITYN.city_id FROM cities CITYN WHERE CITYN.city_id = trips.city_id) city_name")
        ->selectRaw("(SELECT SD.repetitive_type  FROM schedules SD WHERE SD.event_id = trips.id limit 1 ) repetitive_type");
        
        if(!empty($params['category_id']))
            $query->where('category_id',$params['category_id']);

        if(!empty($params['country_id']))
            $query->where('country_id',$params['country_id']);
        
            if(!empty($params['state_id']))
            $query->where('state_id',$params['state_id']);

            if(!empty($params['city_id']))
            $query->where('city_id',$params['city_id']);
 
        if(!empty($params['start_date']) && !empty($params['end_date']))
        {
            $query->whereDate('start_date', '>=' , $params['start_date']);
            $query->whereDate('start_date', '<=' , $params['end_date']);
        }
        
        if(!empty($params['start_date']) && empty($params['end_date']))
            $query->whereDate('start_date', $params['start_date']);
        
    
        if(!empty($params['price']))
        {
            if($params['price'] == 'free')
                $query->where('price_type', "0" );
            
            if($params['price'] == 'paid')
                $query->where('price_type', 1);    
        }

        $query
        ->where(["trips.status" => 1, "trips.publish" => 1]);
        
        // if hide expired events is on
        if(!empty(setting('booking.hide_expire_events')))
        {
            $today  = \Carbon\carbon::now(setting('regional.timezone_default'))->format('Y-m-d');    
            $query->whereRaw('(IF(trips.repetitive = 1, trips.end_date >= "'.$today.'", trips.start_date >= "'.$today.'"))');
        }

        return $query->orderBy('trips.updated_at', 'DESC')->paginate(9);
    }
    
    // update price_type column of event table by 1 if have no free tickets
    public function update_price_type($event_id = null, $params = [])
    {
        return Event::where('id', $event_id)->update($params);
    }

    // get featured event for welocme page
    public function get_featured_events()
    {
        return Trip::leftJoin("trip_categories", "trip_categories.id", '=', "trips.category_id")
                ->select(["trips.*", "trip_categories.category as category_name"])
                ->where(['trips.featured' => 1, 'trips.publish' => 1, 'trips.status' => 1])
                ->whereDate('end_date', '>=', Carbon::today()->toDateString())
                    ->limit(6)
                ->get();
    }
    
    // get top selling event
    public function get_top_selling_events()
    {
        return Trip::leftJoin("trip_categories", "trip_categories.id", '=', "trips.category_id")
                ->select(["trips.*", "trip_categories.category as category_name"])
                ->selectRaw("(SELECT SUM(BK.quantity) FROM bookings BK WHERE BK.event_id = trips.id) total_booking")
                ->selectRaw("(SELECT SD.repetitive_type  FROM schedules SD WHERE SD.event_id = trips.id limit 1 ) repetitive_type")
                ->where(['trips.publish' => 1, 'trips.status' => 1])
                ->whereDate('end_date', '>=', Carbon::today()->toDateString())
                ->orderBy('total_booking', 'desc')
                ->limit(6)
                ->get();
    }
    
    // get upcomming events
    public function get_upcomming_events()
    {
        return  Trip::leftJoin("trip_categories", "trip_categories.id", '=', "trips.category_id")
                    ->select(["trips.*", "trip_categories.category as category_name"])
                    ->whereDate('start_date', '!=', Carbon::now()->format('Y-m-d'))
                    ->whereDate('start_date', '>', Carbon::now()->format('Y-m-d'))
                    ->selectRaw("(SELECT SD.repetitive_type  FROM schedules SD WHERE SD.event_id = trips.id limit 1 ) repetitive_type")
                    ->where(['trips.publish' => 1, 'trips.status' => 1])
                    ->whereDate('end_date', '>', Carbon::today()->toDateString())
                    ->orderBy('start_date')
                    ->limit(6)
                    ->get();

    }
    public function get_event($slug = null, $event_id = null)
    {   
        return Trip::select('trips.*')->from('trips')
            ->where(['slug' => $slug])
            ->orWhere(['id' => $event_id])    
            ->selectRaw("(SELECT CT.category FROM trip_categories CT WHERE CT.id = trips.category_id) category_name")
            ->selectRaw("(SELECT SD.repetitive_type FROM schedules SD WHERE SD.event_id = trips.id limit 1 ) repetitive_type")
            ->first();
    }
    public function get_latest_trips()
    {
        return  Trip::leftJoin("trip_categories", "trip_categories.id", '=', "trips.category_id")
        ->leftJoin("currency", "currency.id", '=', "trips.currency_id") 
        ->select(["trips.*", "trip_categories.category as category_name"])
                  
                    ->selectRaw("(SELECT SD.repetitive_type  FROM schedules SD WHERE SD.event_id = trips.id limit 1 ) repetitive_type")
                    ->where(['trips.publish' => 1, 'trips.status' => 1])
                   
                   ->orderBy('start_date', 'desc')
                    ->limit(6)
                    ->get();

    }
 

    // get cities events
    public function get_cities_events()
    {
        log::info('data get_cities_events ');
        $mode           = config('database.connections.mysql2.strict');
        log::info('data get_cities_events after mode');
         
        $query          = DB::connection('mysql2')->table('trips');

        if(!$mode)
        {
            // safe mode is off
            $select = array(
                            "city",
                            "poster",
                            DB::raw("COUNT(*) AS cities"),
                        );
        }
        else
        {
            // safe mode is on
            $select = array(
                            "city",
                            DB::raw("ANY_VALUE(poster) AS poster"),
                            DB::raw("COUNT(*) AS cities"),
                        );
        }

        $query->select($select)
                ->where(['publish' => 1, 'status' => 1]);
                
                        
        // if hide expired events is on
        if(!empty(setting('booking.hide_expire_events')))
        {
            $today  = \Carbon\carbon::now(setting('regional.timezone_default'))->format('Y-m-d');    
            $query->whereRaw('(IF(trips.repetitive = 1, trips.end_date >= "'.$today.'", trips.start_date >= "'.$today.'"))');
            
        }
        
        $result = $query->where(['trips.publish' => 1, 'trips.status' => 1])->groupBy('city')
                        ->orderBy('cities', 'DESC')
                        ->limit(6)->get();
                        log::info($result);    
        return to_array($result);
    }

    // get organisers 
    public function get_organizers($search = null)
    {
        $query = DB::table('users'); 
        $query->select('name', 'id', 'email')
                ->where('role_id', 3);
        if(!empty($search))
        {
            $query
            ->where(function ($query) use($search) {
                $query->where('name','LIKE',"%{$search}%")
                      ->orWhere('email','LIKE',"%{$search}%");
            });
        }   
        $result = $query->limit(10)->get();
        
        return to_array($result);
    }

    // get customers
    public function get_customers($search = null)
    {
        $query = DB::table('users'); 
        $query->select('name', 'id')
                ->where('role_id', 2);
        if(!empty($search))
        {
            $query
            ->where(function ($query) use($search) {
                $query->where('name','LIKE',"%{$search}%")
                      ->orWhere('email','LIKE',"%{$search}%");
            });
        }   
        $result = $query->limit(10)->get();
        return to_array($result);
    }
    public function get_days($event_id)
    {   
        log::info($event_id);
        $result = Trip::where('id',$event_id)->get();
        log::info($result);
        return to_array($result);
    }
    // search customers
    public function search_customers($email = null)
    {
        $query = DB::table('users'); 
        $query->select('name', 'id', 'email')
                ->where('role_id', 2)
                ->where('email', $email);
        
        $result = $query->get();
        return to_array($result);
    }

    //get selected tags from tag_event table when organiser editing his event
    public function selected_event_tags($event_id = null)
    {   
        $event = Event::where('id', $event_id)->first();
        
        return $event->tags;
        
    }

    /**
     * The tags that belong to the event.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    

    /**
     * =====================GEt events for particular organiser start=====================================
     */

    // get my evenst of particular organiser 
    public function get_my_events($params = [])
    {
        return Trip::select('trips.*')
            ->from('trips')
           
            ->where(['user_id' => $params['organiser_id'] ])
            ->paginate(9);
    }

    // organiser can disable own event
    public function disable_event($params = [], $data = [])
    {
        return Event::where(['id' => $params['event_id'], 'user_id' => $params['organiser_id']])
            ->update($data);
    }

    // only admin can delete event  
    public function delete_event($params = [])
    {
        return Event::where(['id' => $params['event_id']])
            ->delete();
    }

    

    // get all my evenst of particular organiser 
    public function get_all_myevents($params = [])
    {
        $result = Event::select('events.*')
                    ->from('events')
                    ->selectRaw("(SELECT CN.country_name FROM countries CN WHERE CN.id = events.country_id) country_name")
                    ->selectRaw("(SELECT CT.name FROM categories CT WHERE CT.id = events.category_id) category_name")
                    ->where(['user_id' => $params['organiser_id'] ])
                    ->get();
        
        return to_array($result);
    }
    
    /**
     *  top selling event
     */
    public function top_selling_events($user_id = null)
    {
        $query  = Event::query();

                $query->select(
                    'events.*',
                    DB::raw("(select sum(quantity) from bookings where bookings.event_id = events.id ) as total_bookings ")
                )
                ->whereDate('end_date', '>=', Carbon::today()->toDateString());
                
                if(!empty($user_id))
                {
                    $query->where(['user_id' => $user_id]);
                }

        $result =  $query->orderBy('total_bookings', 'DESC')
                ->limit(10)
                ->get();
        
        return to_array($result);
    }

    /**
     *  total event count
     */

    public function total_event($user_id = null)
    {
        if(!empty($user_id))
        {
            return Event::where(['status' => 1, 'user_id' => $user_id])->count();
            
        }
        return Event::count();
    }
    
    // get all my evenst for admin
    public function get_all_events($params = [], $user_id = null)
    {
        $query = Event::query();

        if(!empty($user_id))
        {
            
            $query->where(['user_id' => $user_id]);
            
        }

        $result = $query->get();
        
        return to_array($result);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }


     /**
      * ============================= End particular organiser events ===================================================
      */

    
   
}   
