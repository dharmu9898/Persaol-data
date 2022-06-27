<?php

namespace Classiebit\Eventmie\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Log;

use Classiebit\Eventmie\Models\User;
class Mytripticket extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ticket_title';

protected $guarded = [];
    
    public function get_tripticket()
    {   
     
        $result = Mytripticket::get();
        log::info($result);
        return to_array($result);
    }
   
   

    // get event country
    public function get_event_country($id = null)
    {   
       $result = State::where(['id' => $id])->first();

        return collect($result);
    }
}
