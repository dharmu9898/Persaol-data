<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripState extends Model
{
    protected $fillable = ['country','state', 'slug','slug1','Image','Explain','admin_name','admin_email','admin_id',];
}
