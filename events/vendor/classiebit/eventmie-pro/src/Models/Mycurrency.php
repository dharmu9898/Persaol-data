<?php

namespace Classiebit\Eventmie\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Log;

use Classiebit\Eventmie\Models\User;
class Mycurrency extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'currency';

protected $guarded = [];
    
    public function get_currencies()
    {   
        log::info('yanha data hai');
        $result = Mycurrency::get();
        log::info($result);
        return to_array($result);
    }
    
    
}
