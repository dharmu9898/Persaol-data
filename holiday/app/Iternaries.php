<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iternaries extends Model
{
    protected $table = 'iternaries';
    protected $fillable = ['user_id' ,'Days','trips','location','explanation','operator_name','operator_email','operator_id'];


    
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
