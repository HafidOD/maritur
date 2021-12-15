<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportReservation extends Model
{
    const STATUS_RESERVED = 1;
    const STATUS_MODIFIED = 2;
    const STATUS_CANCELLED = 3;
    
    public static $statusLabels = [
        self::STATUS_RESERVED => 'Reservado',
        self::STATUS_MODIFIED => 'Modificado',
        self::STATUS_CANCELLED => 'Cancelado',
    ];

    const TRIPTYPE_ONEWAY = 1;
    const TRIPTYPE_ROUNDTRIP = 2;
    
    public static $triptypeLabels = [
        self::TRIPTYPE_ONEWAY => 'One Way',
        self::TRIPTYPE_ROUNDTRIP => 'Roundtrip',
    ];

    protected $table = 'TransportReservations';
 	protected $dates = [
        'arrivalDatetime',
 		'departureDatetime',
 	];
    //
    public function reservation()
    {
        return $this->hasOne('App\Reservation','id','reservationId');
    }
    public function transportServiceType()
    {
        return $this->hasOne('App\TransportServiceType','id','transportServiceTypeId');
    }
    public function tour()
    {
        return $this->hasOne('App\Tour','id','tourId');
    }
    public function destination()
    {
        return $this->hasOne('App\City','id','destinationId');
    }
    public function hotel()
    {
    	return $this->hasOne('App\Hotel','id','hotelId');
    }
    public function destName()
    {
        if($this->hotel) return $this->hotel->name;
        if($this->tour) return $this->tour->name;
        if($this->destination) return $this->destination->name;
        return 'N/A';
    }
}
