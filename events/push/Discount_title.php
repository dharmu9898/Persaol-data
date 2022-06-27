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
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Classiebit\Eventmie\Models\Trip;
use Classiebit\Eventmie\Models\Week;
use Classiebit\Eventmie\Models\Mydiscount;
use Classiebit\Eventmie\Models\Discount;
use Classiebit\Eventmie\Models\Myticket;
class Discount_title extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'discount_title';

protected $guarded = [];


public function get_discountweek($discountid,$eventsid,$first_discount)
{   
    log::info('$get_discountweek');
    $tickets=  Myticket::where('ticket_event_id',$eventsid)->first();
  $mydis=  Mydiscount::where('trip_id',$eventsid)->first();
  $trip=Trip::where('id',$eventsid)->first();
  $publish=$trip->publish;
  if(empty($tickets)|| $publish==0 ||$publish==1)
  {
 if($mydis){
    $discounts= DB::connection('mysql2')->table("mydiscount")->where('trip_id',$eventsid)->pluck('selectdiscount');
    $select_min= DB::connection('mysql2')->table("mydiscount")->where('trip_id',$eventsid)->min('selectdiscount');
    log::info($discounts);
    log::info( $select_min);
   if($discountid){
    $get_discount= DB::connection('mysql2')->table("week")->
    insert([
    ['selectweek' => $discountid,'trip_id' => $eventsid]
           ]);
    $result = Discount_title::where('id','<', $first_discount)->get();
    log::info($result);
    return to_array($result);
}
}  
else{
if($discountid){
    log::info('inside  else if discountid');
    $get_discount= DB::connection('mysql2')->table("week")->
    insert([
    ['selectweek' => $discountid,'trip_id' => $eventsid]
           ]);
    $result = Discount_title::get();
    log::info($result);
    return to_array($result);
}
}
  }
else{
    log::info('yanha $result aaya');
    log::info($first_discount);
    $result = Discount_title::where('id','<', $first_discount)->get();
    log::info($result);
    return to_array($result);

}

}

public function get_discountweek_second($discountid,$eventsid,$first_discount,$second_discount)
{   
    log::info('$get_discountweek');
    $tickets=  Myticket::where('ticket_event_id',$eventsid)->first();
  $mydis=  Mydiscount::where('trip_id',$eventsid)->first();
  $trip=Trip::where('id',$eventsid)->first();
  $publish=$trip->publish;
  if(empty($tickets)|| $publish==0 ||$publish==1)
  {
 if($mydis){
    $discounts= DB::connection('mysql2')->table("mydiscount")->where('trip_id',$eventsid)->pluck('selectdiscount');
    $select_min= DB::connection('mysql2')->table("mydiscount")->where('trip_id',$eventsid)->min('selectdiscount');
    log::info($discounts);
    log::info( $select_min);
   if($discountid){
    $get_discount= DB::connection('mysql2')->table("week")->
    insert([
    ['selectweek' => $discountid,'trip_id' => $eventsid]
           ]);
    $result = Discount_title::where('id','<', $second_discount)->get();
    log::info($result);
    return to_array($result);
}
}  
else{
if($discountid){
    log::info('inside  else if discountid');
    $get_discount= DB::connection('mysql2')->table("week")->
    insert([
    ['selectweek' => $discountid,'trip_id' => $eventsid]
           ]);
    $result = Discount_title::get();
    log::info($result);
    return to_array($result);
}
}
  }
else{
    log::info('yanha $result second aaya');
    log::info($second_discount);
    $result = Discount_title::where('id','<', $second_discount)->get();
    log::info($result);
    return to_array($result);

}

}
    public function get_discount($discountid,$eventsid)
    {   
        log::info('$discount_title');
        $tickets=   Myticket::where('ticket_event_id',$eventsid)->first();
        $trip=Trip::where('id',$eventsid)->first();
        $publish=$trip->publish;
      $mydis=  Mydiscount::where('trip_id',$eventsid)->first();
      $trip=Trip::where('id',$eventsid)->first();
      $publish=$trip->publish;
      if(empty($tickets)|| $publish==0 ||$publish==1)
      {
     if($mydis){
        $get_discount= DB::connection('mysql2')->table("week")->
        insert([
        ['selectweek' => $discountid,'trip_id' => $eventsid]
               ]);
        $result = Discount_title::get();
        log::info($result);
        return to_array($result);
    }
    else{
        $get_discount= DB::connection('mysql2')->table("week")->
        insert([
        ['selectweek' => $discountid,'trip_id' => $eventsid]
               ]);
        $result = Discount_title::get();
        log::info($result);
        return to_array($result);

    }
}  

      }

    public function get_add_discount($dayss,$eventsid,$discountid)
    {   
        log::info('$get_add_discount daysss');
        log::info($discountid);
        log::info($eventsid);
        log::info($dayss);
        
        $discounts= DB::connection('mysql2')->table("week")->where('trip_id',$eventsid)->pluck('selectweek');
          $select_min= DB::connection('mysql2')->table("week")->where('trip_id',$eventsid)->min('selectweek');
       log::info($discounts);
       log::info($select_min);

       $tickets=   Myticket::where('ticket_event_id',$eventsid)->first();
       $trip=Trip::where('id',$eventsid)->first();
       $publish=$trip->publish;
       if(empty($tickets)|| $publish==0 ||$publish==1)
       {
        if($dayss>=126)
        {
        $result = Discount::where('id','<', $discountid)->take(17)->get();
        log::info($result);
        return to_array($result);
    }
    elseif($dayss>=119)
    {
    $result = Discount::where('id','<', $discountid)->take(16)->get();
    log::info($result);
    return to_array($result);
}
elseif($dayss>=112)
{
$result = Discount::where('id','<', $discountid)->take(15)->get();
log::info($result);
return to_array($result);
}

elseif($dayss>=105){
    $result = Discount::where('id','<', $discountid)->take(14)->get();
    log::info($result);
    return to_array($result);
    
}

elseif($dayss>=98){
    $result = Discount::where('id','<', $discountid)->take(13)->get();
    log::info($result);
    return to_array($result);
    
}

elseif($dayss>=91){
    $result = Discount::where('id','<', $discountid)->take(12)->get();
    log::info($result);
    return to_array($result);
    
}

elseif($dayss>=84){
    $result = Discount::where('id','<', $discountid)->take(11)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=77){
    $result = Discount::where('id','<', $discountid)->take(10)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=63){
    $result = Discount::where('id','<', $discountid)->take(9)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=56){
    $result = Discount::where('id','<', $discountid)->take(8)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=49){
    $result = Discount::where('id','<', $discountid)->take(7)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=42){
    $result = Discount::where('id','<', $discountid)->take(6)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=35){
    $result = Discount::where('id','<', $discountid)->take(5)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=28){
    $result = Discount::where('id','<', $discountid)->take(4)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=21){
    $result = Discount::where('id','<', $discountid)->take(3)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=14){
    $result = Discount::where('id','<', $discountid)->take(2)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=7){
    $result = Discount::where('id','<', $discountid)->take(1)->get();
    log::info($result);
    return to_array($result);
    
}
else{
    log::info('$result');  

}

       }
       else{
        if($dayss>=126)
        {
        $result = Discount::where('id','<', $discountid)->take(17)->get();
        log::info($result);
        return to_array($result);
    }
    elseif($dayss>=119)
    {
    $result = Discount::where('id','<', $discountid)->take(16)->get();
    log::info($result);
    return to_array($result);
}
elseif($dayss>=112)
{
$result = Discount::where('id','<', $discountid)->take(15)->get();
log::info($result);
return to_array($result);
}

elseif($dayss>=105){
    $result = Discount::where('id','<', $discountid)->take(14)->get();
    log::info($result);
    return to_array($result);
    
}

elseif($dayss>=98){
    $result = Discount::where('id','<', $discountid)->take(13)->get();
    log::info($result);
    return to_array($result);
    
}

elseif($dayss>=91){
    $result = Discount::where('id','<', $discountid)->take(12)->get();
    log::info($result);
    return to_array($result);
    
}

elseif($dayss>=84){
    $result = Discount::where('id','<', $discountid)->take(11)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=77){
    $result = Discount::where('id','<', $discountid)->take(10)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=63){
    $result = Discount::where('id','<', $discountid)->take(9)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=56){
    $result = Discount::where('id','<', $discountid)->take(8)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=49){
    $result = Discount::where('id','<', $discountid)->take(7)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=42){
    $result = Discount::where('id','<', $discountid)->take(6)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=35){
    $result = Discount::where('id','<', $discountid)->take(5)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=28){
    $result = Discount::where('id','<', $discountid)->take(4)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=21){
    $result = Discount::where('id','<', $discountid)->take(3)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=14){
    $result = Discount::where('id','<', $discountid)->take(2)->get();
    log::info($result);
    return to_array($result);
    
}
elseif($dayss>=7){
    $result = Discount::where('id','<', $discountid)->take(1)->get();
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
