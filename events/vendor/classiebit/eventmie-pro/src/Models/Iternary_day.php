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
class Iternary_day extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'iternary_day';

protected $guarded = [];
    
    public function get_days($event_id)
    {   
        log::info($event_id);
        $result = Iternary_day::where('trip_id',$event_id)->get();
        log::info($result);
        return to_array($result);
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
