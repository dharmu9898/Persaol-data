<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $connection = 'mysql2';
        protected $table = 'settings';
    
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
