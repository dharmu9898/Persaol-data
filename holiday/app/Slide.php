<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'slide_title',
        'description',
        'filename',
        'mime',
        'path',
        'tag',
        'slug',
       	'original_filename',
        'trainer_id',
        'trainer_email',                   
        'trainer_name'
    ];
}
