<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menuitems extends Model
{
    protected $connection = 'mysql2';
        protected $table = 'menu_items';
    
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
