<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class Statetrip extends Model
{
   
    protected $connection = 'mysql2';
    protected $guarded = [];
    protected $table = 'states';
    

    public function get_mystates()
    {   
        $result = Statetrip::all();
        return to_array($result);
    }

    public function get_states($state_id)
    {   
        log::info($state_id);
        $result = Statetrip::where('state_country_id',$state_id)->get();
        log::info($result);
        return to_array($result);
    }
   
    public function get_countries_having_events()
    {   
        $result = Statetrip::leftJoin("events", "events.country_id", '=', "countries.id")
                ->select(["countries.*"])
                ->where("events.country_id", '!=', null)
                ->groupBy('id')
                ->get();

        return to_array($result);
    }

    // get event country
    public function get_event_country($id = null)
    {   
       $result = Statetrip::where(['id' => $id])->first();

        return collect($result);
    }
}
