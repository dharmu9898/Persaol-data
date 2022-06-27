<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Trip;
class Ticket_title extends Model
{
    protected $table = 'ticket_title';
   

    public function save_event($params = [], $event_id = null)
    {
       log::info('iternary_day');// if have no event id then create new event
       log::info($event_id);
       log::info($params);
      $dayss= Trip::where('id',$event_id)->first();
      $days=$dayss->days;
      $myid= Iternary_day::where('trip_id',$event_id)->pluck('trip_id');
      if(!$myid->isEmpty())
      {
        Iternary_day::where('trip_id',$event_id)->delete();
        for ($i=1; $i <= $days; $i++) { 
            $modelName = new Iternary_day;
            $modelName->trip_id = $event_id;
            $modelName->days = 'day'.$i;
            $modelName->save();
        }
      }
      else{
       for ($i=1; $i <= $days; $i++) { 
        $modelName = new Iternary_day;
        $modelName->trip_id = $event_id;
        $modelName->days = 'day'.$i;
        $modelName->save();
    }
}
}
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
