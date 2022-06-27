<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Iternary_day;
use App\Trip;
class Iternary extends Model
{

    protected $table = 'iternary';
    protected $fillable = ['days','dayid','trip_id','flightcount','hotelscount','transfercount','activitycount','flight','hotels','transfer','activity','title','status','location','description','operator_email','operator_id'];

    public function save_event($params = [], $trip_id = null)
    {
       log::info('iternary my save_event');// if have no event id then create new event
       log::info($trip_id);
       log::info($params);
       $widget_id = str_replace(array('[',']'), '',$params['days']);
       $explode_id = explode(',', $widget_id);
       log::info($explode_id);
       $get_days= Iternary_day::whereIn('id',$explode_id)->pluck('days');
        $dayss= Trip::where('id',$trip_id)->first();
       $days=$dayss->days;
       $user_id= $dayss->user_id;
       log::info($user_id);
       $status=$dayss->status;
       $title=$dayss->title;
        $op_mail=$dayss->operator_email;
        $op_mail=$dayss->operator_email;
       
       $myid= Iternary::where('trip_id',$trip_id)->pluck('trip_id');
       
       if(!$myid->isEmpty())
       {
            $myticketid=$params['id'];
       
        if($myticketid)
        {
                      if(!$params['days'])
       {
           log::info('first if else');
             $itr=Iternary::where('id', $myticketid)->update(array('location' => $params['location'],'activity' => $params['activity'],'transfer' => $params['transfer'],'hotels' => $params['hotels'],'flight' => $params['flight'],'description' => $params['description']));
             $counthot = Iternary::where('trip_id',$trip_id)->count('hotels');
             $countacticity = Iternary::where('trip_id',$trip_id)->count('activity');
             $countflight = Iternary::where('trip_id',$trip_id)->count('flight'); 
             log::info($counthot);
             $counttr = Iternary::where('trip_id',$trip_id)->count('transfer');
             log::info($counttr);
               
            }
       else{  
        log::info('outer else');
        Iternary::where('id', $myticketid)->delete();
     Iternary::where('trip_id', $trip_id)
->where(function ($query) use ($get_days) {
 $query->whereIn('days',$get_days);
 
})->delete();
            foreach ($get_days as $myday) {
                log::info('inside delete foreach');
                $dayid= substr($myday, 3); 
           log::info($dayid);
                Iternary::create(['days' => $myday,'title' => $title, 'trip_id' => $trip_id,'location' => $params['location'],
                'activity' => $params['activity'],'transfer' => $params['transfer'],'hotels' => $params['hotels'],'flight' => $params['flight'],'description' => $params['description'],'operator_email' => $op_mail,'status' => $status]);
                $counthot = Iternary::where('trip_id',$trip_id)->count('hotels');
                $countacticity = Iternary::where('trip_id',$trip_id)->count('activity');
                $countflight = Iternary::where('trip_id',$trip_id)->count('flight'); 
                log::info($counthot);
                $counttr = Iternary::where('trip_id',$trip_id)->count('transfer');
                log::info($counttr);
                
    
        }  
    } 
       
        
    }
    
    else{
       
        foreach ($get_days as $myday) {
           log::info('inside 2nnd else foreach multiple day');
           $dayid= substr($myday, 3); 
           log::info($dayid);
            Iternary::create(['days' => $myday,'title' => $title, 'trip_id' => $trip_id,'location' => $params['location'],
            'activity' => $params['activity'],'transfer' => $params['transfer'],'hotels' => $params['hotels'],'flight' => $params['flight'],'description' => $params['description'],'operator_email' => $op_mail,'status' => $status]);
          }
       }
       $counthot = Iternary::where('trip_id',$trip_id)->count('hotels');
       $countacticity = Iternary::where('trip_id',$trip_id)->count('activity');
       $countflight = Iternary::where('trip_id',$trip_id)->count('flight'); 
       log::info($counthot);
       $counttr = Iternary::where('trip_id',$trip_id)->count('transfer');
       log::info($counttr);
           
    
    }
       else{
        foreach ($get_days as $myday) {
            log::info('inside last else foreach single day');
            $dayid= substr($myday, 3); 
            log::info($dayid);
            Iternary::create(['days' => $myday,'title' => $title, 'trip_id' => $trip_id,'location' => $params['location'],
            'activity' => $params['activity'],'transfer' => $params['transfer'],'hotels' => $params['hotels'],'flight' => $params['flight'],'description' => $params['description'],'operator_email' => $op_mail,'status' => $status]);
         }
         $counthot = Iternary::where('trip_id',$trip_id)->count('hotels');
         $countacticity = Iternary::where('trip_id',$trip_id)->count('activity');
         $countflight = Iternary::where('trip_id',$trip_id)->count('flight'); 
         log::info($counthot);
         $counttr = Iternary::where('trip_id',$trip_id)->count('transfer');
         log::info($counttr);
            
    }
}   
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
