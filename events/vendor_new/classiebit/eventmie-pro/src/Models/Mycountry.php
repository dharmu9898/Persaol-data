<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use Classiebit\Eventmie\Models\City;
use Classiebit\Eventmie\Models\State;
use DB;
use Log;


class Mycountry extends Model
{
    protected $guarded = [];
    protected $table = 'country';
    public function get_mycountries()
    {   
        $result = Mycountry::all();
        return to_array($result);
    }
    public function get_state_country_having_events($country)
    {   
        log::info('Yaha tak state');
        log::info($country);
        $result = State::leftJoin("events", "events.state_id", '=', "states.state_id")
                ->select(["states.*"])
                ->where("events.slug3",$country )
                ->groupBy('state_id')
                ->get();

        return to_array($result);
    }

    public function get_countries_having_events()
    {   
        $result = Mycountry::leftJoin("events", "events.country_id", '=', "country.country_id")
                ->select(["country.*"])
                ->where("events.country_id", '!=', null)
                ->groupBy('country_id')
                ->get();

        return to_array($result);
    }

    public function get_city_country_having_events($country)
    {   
        $result = City::leftJoin("events", "events.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("events.slug3",$country)
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }
    public function get_mycity_country_cities_having_events($city)
    {   
        $result = City::leftJoin("events", "events.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("events.city",$city)
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }
    public function get_mycountries_country_having_events($country)
    {   
        $result = Mycountry::leftJoin("events", "events.country_id", '=', "country.country_id")
                ->select(["country.*"])
                ->where("events.slug3",$country )
                ->groupBy('country_id')
                ->get();

        return to_array($result);
    }
    public function get_mystate_country_having_events($state)
    {   
        $result = State::leftJoin("events", "events.state_id", '=', "states.state_id")
                ->select(["states.*"])
                ->where("events.state",$state )
                ->groupBy('state_id')
                ->get();

        return to_array($result);
    }
    public function get_state_having_events()
    {   
      
        $result = State::leftJoin("events", "events.state_id", '=', "states.state_id")
                ->select(["states.*"])
                ->where("events.state_id", '!=', null)
                ->groupBy('state_id')
                ->get();
                log::info('yaha tak aa raha hai');
                log::info($result);
        return to_array($result);
    }

    public function get_state_country_city_having_events($state)
    {   
        $result = City::leftJoin("events", "events.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("events.state",$state )
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }

    public function get_city_country_cities_having_events($state)
    {   
        $result = City::leftJoin("events", "events.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("events.state",$state )
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }


    public function get_city_having_events()
    {   
        $result = City::leftJoin("events", "events.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("events.city_id", '!=', null)
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }

    // get event country
    public function get_event_country($id = null)
    {   
       $result = Mycountry::where(['country_id' => $id])->first();

        return collect($result);
    }
}
