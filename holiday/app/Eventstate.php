<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventstate extends Model
{
    protected $connection = 'mysql2';
        protected $table = 'states';
    
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
