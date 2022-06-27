<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use Classiebit\Eventmie\Models\City;
use Classiebit\Eventmie\Models\Citytrip;
use Classiebit\Eventmie\Models\Trip;
use Classiebit\Eventmie\Models\Statetrip;

use DB;
use Log;


class Myclientcountry extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql2';
    protected $table = 'countries';
    public function get_mycountries()
    {   
        $result = Myclientcountry::all();
        return to_array($result);
    }
    public function get_state_country_having_events($country)
    {   
        $result = Statetrip::leftJoin("trips", "trips.state_id", '=', "states.state_id")
                ->select(["states.*"])
                ->where("trips.slug3",$country )
                ->groupBy('state_id')
                ->get();

        return to_array($result);
    }
    public function get_mycountries_country_having_events($country)
    {   
        $result = Myclientcountry::leftJoin("trips", "trips.country_id", '=', "countries.country_id")
                ->select(["countries.*"])
                ->where("trips.slug3",$country )
                ->groupBy('country_id')
                ->get();

        return to_array($result);
    }
    public function get_mystate_country_having_events($state)
    {   
        $result = Statetrip::leftJoin("trips", "trips.state_id", '=', "states.state_id")
                ->select(["states.*"])
                ->where("trips.state",$state )
                ->groupBy('state_id')
                ->get();

        return to_array($result);
    }
    public function get_countries_having_events()
    {   
        $result = Myclientcountry::leftJoin("trips", "trips.country_id", '=', "countries.country_id")
                ->select(["countries.*"])
                ->where("trips.country_id", '!=', null)
                ->groupBy('country_id')
                ->get();

        return to_array($result);
    }
    public function get_city_country_having_events($country)
    {   
        $result = Citytrip::leftJoin("trips", "trips.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("trips.slug3",$country)
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }
    public function get_mycity_country_cities_having_events($city)
    {   
        $result = Citytrip::leftJoin("trips", "trips.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("trips.city",$city)
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }

    
    public function get_state_having_events()
    {   
        $result = Statetrip::leftJoin("trips", "trips.state_id", '=', "states.state_id")
                ->select(["states.*"])
                ->where("trips.state_id", '!=', null)
                ->groupBy('state_id')
                ->get();

        return to_array($result);
    }
    public function get_state_country_city_having_events($state)
    {   
        $result = Citytrip::leftJoin("trips", "trips.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("trips.state",$state )
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }

    public function get_city_country_cities_having_events($state)
    {   
        $result = Citytrip::leftJoin("trips", "trips.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("trips.state",$state )
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }


    public function get_city_having_events()
    {   
        $result = Citytrip::leftJoin("trips", "trips.city_id", '=', "cities.city_id")
                ->select(["cities.*"])
                ->where("trips.city_id", '!=', null)
                ->groupBy('city_id')
                ->get();

        return to_array($result);
    }
    // get event country
    public function get_event_country($id = null)
    {   
       $result = Myclientcountry::where(['country_id' => $id])->first();

        return collect($result);
    }
}
