<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Tax extends Model
{
    protected $guarded = [];
    protected $table = 'taxes';
    public function get_taxes()
    {
        $result = Tax::where(['status' => 1])->get();
    
        return to_array($result);
    }

    public function get_admin_taxes()
    {
        return Tax::where(['status' => 1])->get();
        
    }
}