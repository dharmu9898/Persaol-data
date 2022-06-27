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
class Mydiscount extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'mydiscount';

protected $guarded = [];
    

public function insertselect_discount($discountid,$eventsid)
{
    log::info('$insertselect_discount');
   if($discountid){
    $get_discount= DB::connection('mysql2')->table("mydiscount")->
    insert([
    ['selectdiscount' => $discountid,'trip_id' => $eventsid]
           ]);
    

}
}
    public function insert_discount($discountid,$eventsid)
    {
        log::info('$insert_discount');
       if($discountid){
        $get_discount= DB::connection('mysql2')->table("mydiscount")->
        insert([
        ['selectdiscount' => $discountid,'trip_id' => $eventsid]
               ]);
        

    }
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
