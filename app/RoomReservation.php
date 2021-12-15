<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomReservation extends Model
{
    protected $table = 'RoomsReservations';

    const STATUS_RESERVED = 1;
    const STATUS_MODIFIED = 2;
    const STATUS_CANCELLED = 3;
    public static $statusLabels = [
        self::STATUS_RESERVED => 'Reservado',
        self::STATUS_MODIFIED => 'Modificado',
        self::STATUS_CANCELLED => 'Cancelado',
    ];
    protected $casts = [
        'jsonAges' => 'array',
        'jsonNames' => 'array',
        'jsonRefExtraData' => 'array',
    ];
    public function reservation()
    {
    	return $this->hasOne('App\Reservation','id','hotelReservationId');
    }
    public function getRefTotalWithMarkup()
    {
    	return $this->refTotal+$this->markup;
    }
}
