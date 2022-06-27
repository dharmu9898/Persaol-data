<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripCity extends Model
{
    protected $fillable = ['country' ,'state','city','slug','slug1','slug2','Image','Describes','admin_name','admin_email','admin_id',];
}
