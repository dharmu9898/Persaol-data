<?php

namespace Classiebit\Eventmie\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Log;
use Classiebit\Eventmie\Models\Tag;
use Classiebit\Eventmie\Models\Booking;
use Classiebit\Eventmie\Models\commission;
use Classiebit\Eventmie\Models\Mydiscount;
use Classiebit\Eventmie\Models\Myticket;
use Classiebit\Eventmie\Models\User;
class Discountmsg extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'mydiscountmsg';

protected $guarded = [];
    
    public function get_discount($dayss,$event_id)
    {
        log::info('yeh mera days hai naa');
        log::info($dayss);
     $tickets=   Myticket::where('event_id',$event_id)->first();
     if(empty($tickets))
     {
        if($dayss>=126)
        {
        $result = Discount::take(17)->get();
        log::info($result);
        return to_array($result);
    }
    elseif($dayss>=119)
    {
    $result = Discount::take(16)->get();
    log::info($result);
    return to_array($result);
}
elseif($dayss>=112)
{
$result = Discount::take(15)->get();
log::info($result);
return to_array($result);
}

elseif($dayss>=105){
    $result = Discount::take(14)->get();
    log::info($result);
    return to_array($result);
    
}

elseif($dayss>=98){
    $result = Discount::take(13)->get();
    log::info($result);
    return to_array($result);
    
}

elseif($dayss>=91){
    $result = Discount::take(12)->get();
    log::info($result);
    return to_array($result);
    
}

elseif($dayss>=84){
    $result = Discount::take(11)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=77){
    $result = Discount::take(10)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=63){
    $result = Discount::take(9)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=56){
    $result = Discount::take(8)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=49){
    $result = Discount::take(7)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=42){
    $result = Discount::take(6)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=35){
    $result = Discount::take(5)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=28){
    $result = Discount::take(4)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=21){
    $result = Discount::take(3)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=14){
    $result = Discount::take(2)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=7){
    $result = Discount::take(1)->get();
    log::info($result);
    return to_array($result);
    
}
else{


}


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
