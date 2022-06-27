<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rvsp extends Model
{ 
    protected $fillable = ['emailid','Phoneno','Name','TripTitle','password','Address'];
  

    public static function returnuser(){
        $touropretor = Rvsp::where('Permission','Approve')->get();
        return $touropretor;
          }
   
}
