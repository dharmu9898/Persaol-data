<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventcity extends Model
{
    protected $connection = 'mysql2';
        protected $table = 'cities';
    
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
