<?php

namespace Classiebit\Eventmie\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Log;

use Classiebit\Eventmie\Models\User;
class Myeventticket extends Model
{
    protected $guarded = [];
    protected $table = 'ticket_title';
  
    
    public function get_eventticket()
    {   
     
        $result = Myeventticket::get();
        log::info($result);
        return to_array($result);
    }
   
   
}