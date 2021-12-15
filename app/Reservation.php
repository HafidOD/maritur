<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    const STATUS_RESERVED = 1;
	const STATUS_MODIFIED = 2;
    const STATUS_CANCELLED = 3;
    
    public static $statusLabels = [
        self::STATUS_RESERVED => 'Reservado',
        self::STATUS_MODIFIED => 'Modificado',
        self::STATUS_CANCELLED => 'Cancelado',
    ];

    const PAYMENT_STATUS_PENDING = 1;
    const PAYMENT_STATUS_PAYED = 2;

    const PAYMENT_METHOD_DEPOSIT = 1;
    const PAYMENT_METHOD_STRIPECARD = 2;
    const PAYMENT_METHOD_PAYPAL = 3;

    protected $table = 'Reservations';
    protected $casts = [
        // 'jsonRefQuote' => 'object',
        'jsonRefExtraData' => 'array',
        'paymentReference' => 'array',
    ];

    protected $fillable = [
    	'clientFirstName',
    	'clientLastName',
    	'clientPhone',
    	'clientAddress',
    	'clientCountryId',
    	'clientState',
    	'clientEmail',
    	'clientCity',
        'clientZipcode',
        'specialRequests',
        'hotelname',
    	'holdername',
    ];
    public function toursReservations()
    {
        return $this->hasMany('App\TourReservation','reservationId','id');
    }
    public function transportReservations()
    {
        return $this->hasMany('App\TransportReservation','reservationId','id');
    }
    public function country()
    {
        return $this->hasOne('App\Country','id','clientCountryId');
    }
    public function hotelReservation()
    {
        return $this->hasOne('App\HotelReservation','reservationId','id');
    }
    public function affiliate()
    {
        return $this->hasOne('App\Affiliate','id','affiliateId');
    }
    public function updateTotals()
    {
        $this->subtotal = 0;
        $this->total = 0;
        // var_dump($this->hotelReservation);
        if ($this->hotelReservation) {
            $this->subtotal+=$this->hotelReservation->subtotal;
            $this->total+=$this->hotelReservation->total;
        }
        foreach ($this->toursReservations as $t) {
            $this->subtotal+=$t->subtotal;
            $this->total+=$t->total;
        }
        foreach ($this->transportReservations as $t) {
            $this->subtotal+=$t->subtotal;
            $this->total+=$t->total;
        }
    }
}
