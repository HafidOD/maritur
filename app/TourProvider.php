<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TourProvider extends Model
{
    protected $table = 'TourProviders';
    protected $guarded = [];
    //
    public function tours()
    {
    	return $this->hasMany('App\Tour', 'tourProviderId', 'id');
    }
}
