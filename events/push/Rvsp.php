<?php

namespace Classiebit\Eventmie\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Log;
use Classiebit\Eventmie\Models\Tag;
use Classiebit\Eventmie\Models\Booking;
use Classiebit\Eventmie\Models\Trip;
use Classiebit\Eventmie\Models\commission;
use Classiebit\Eventmie\Models\User;
class Rvsp extends Model
{
   
    protected $table = 'rvsps';
    protected $fillable = ['emailid','Phoneno','Name','TripTitle','password','Address'];
  
protected $guarded = [];
    
    public function get_iternaries($event_id)
    {   
        
        log::info($event_id);
        $result = Iternary::where('trip_id',$event_id)->get();
        log::info($result);
        return to_array($result);
    }
    public function get_days($event_id)
    {   
        $myday=Trip::where('id',$event_id)->first();
        $dayss=$myday->days;
        log::info($dayss);
        for($i=1; $i <= $dayss; $i++)
        {
          $day=   'day' .$i;
         $days[]=$day;
        }
        $iternary = DB::table('iternary')
        ->whereIn('iternary.Days', $days)
        ->where('iternary.trip_id', $event_id)
         ->select('iternary.*')
         ->orderBy('iternary.id', 'asc')->get();
       
        return to_array($iternary);
    }
   
    public function get_countries_having_events()
    {   
        $result = State::leftJoin("events", "events.country_id", '=', "countries.id")
                ->select(["countries.*"])
                ->where("events.country_id", '!=', null)
                ->groupBy('id')
                ->get();

        return to_array($result);
    }

    // get event country
    public function get_event_country($id = null)
    {   
       $result = State::where(['id' => $id])->first();

        return collect($result);
    }
}
