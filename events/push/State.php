<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class State extends Model
{
    protected $guarded = [];
    protected $table = 'states';
    

    public function get_mystates()
    {   
        $result = State::all();
        return to_array($result);
    }

    public function get_states($state_id)
    {   
        log::info($state_id);
        $result = State::where('state_country_id',$state_id)->get();
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
