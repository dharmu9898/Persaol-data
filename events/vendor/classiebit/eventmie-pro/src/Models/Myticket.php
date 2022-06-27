<?php

namespace Classiebit\Eventmie\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Log;
use Classiebit\Eventmie\Models\Tag;

use Classiebit\Eventmie\Models\User;
class Myticket extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'tickets';

protected $guarded = [];
    
    public function get_tickets($event_id)
    {   
        log::info('inside get_tickets seee');
        log::info($event_id);
        $result = Myticket::leftJoin("currency", "currency.id", '=', "tickets.currency")->
        leftJoin("ticket_title", "ticket_title.id", '=', "tickets.title")->where('event_id',$event_id)->get();
        log::info($result);
        return to_array($result);
    }
   
   
}
