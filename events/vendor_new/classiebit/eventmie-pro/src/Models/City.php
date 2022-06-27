<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class City extends Model
{
    protected $guarded = [];
    protected $table = 'cities';
    
    public function get_cities($citi_id)
    {   
        log::info($citi_id);
        $result = City::where('city_state_id',$citi_id)->get();
        log::info($result);
        return to_array($result);
    }
   
    public function get_countries_having_events()
    {   
        $result = Countries::leftJoin("events", "events.country_id", '=', "countries.id")
                ->select(["countries.*"])
                ->where("events.country_id", '!=', null)
                ->groupBy('id')
                ->get();

        return to_array($result);
    }

    // get event country
    public function get_event_country($id = null)
    {   
       $result = Countries::where(['id' => $id])->first();

        return collect($result);
    }

    public function myget_cities()
    {   
        $result = City::all();
        return to_array($result);
    }
}
