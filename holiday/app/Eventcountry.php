<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventcountry extends Model
{
    protected $connection = 'mysql2';
        protected $table = 'countries';
    
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
