<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use DB;
class Country extends Model
{
    protected $table = "countries";  //

    public function get_countries()
    {
        $result = Country::all();
        return to_array($result);
                    
    }
}
