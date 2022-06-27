<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class Banner extends Model
{
    protected $guarded = [];
    public function get_banners()
    {
        log::info("banner me aata hain");
        return Banner::where(['status' => 1])->orderBy('order', 'asc')->get();
    }

}    