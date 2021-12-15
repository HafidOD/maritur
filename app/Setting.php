<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'Settings';
    protected $guarded = [];
    // protected $primaryKey = ['id','affiliateId'];
    // public $incrementing = false;
}
