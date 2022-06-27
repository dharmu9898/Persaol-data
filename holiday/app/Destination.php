<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['country' ,'slug','desc','Image','admin_name','admin_email','admin_id'];    
}
