<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InactiveTour extends Model
{
    protected $table = 'InactiveTours';
    public $timestamps = false;
    protected $guarded = [];

}

