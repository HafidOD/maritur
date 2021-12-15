<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TourPriceTransportation extends Model
{
	protected $table = 'TourPriceTransportation';
    protected $guarded = [];
    public $timestamps = false;
   	public function destination()
    {
    	return $this->hasOne('App\City','id','destinationId');
    }

}
