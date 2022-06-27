<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userevent extends Model
{
    protected $connection = 'mysql2';
        protected $table = 'users';
       
       
    public static function returnopretor(){
      $touropretor = Userevent::where('role_id','3')->get();
      return $touropretor;
        }

    function user()
    {
        return $this->belongsTo(User::class);
    }


}
