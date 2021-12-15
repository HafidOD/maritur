<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TourReservation extends Model
{
    protected $table = 'TourReservations';
 	protected $dates = [
 		'day',
 	];
    //
    public function reservation()
    {
        return $this->hasOne('App\Reservation','id','hotelId');
    }
    public function fromDestination()
    {
    	return $this->hasOne('App\City','id','fromDestinationId');
    }
    public function tourPrice()
    {
    	return $this->hasOne('App\TourPrice','id','tourPriceId');
    }
}
