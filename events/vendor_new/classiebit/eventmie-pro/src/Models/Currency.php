<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;


class Currency extends Model
{
    protected $table = 'currency';
    protected $fillable = ['currency_id' ,'country','currencies','currency']; 
    
}
