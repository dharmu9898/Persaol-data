<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripCategory extends Model
{
    protected $fillable = ['category' ,'Image','Description','admin_name','admin_email','admin_id'];    
}
