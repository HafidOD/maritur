<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Components\OmnibeesApiComponent;

class HotelReservation extends Model
{
    const STATUS_RESERVED = 1;
    const STATUS_MODIFIED = 2;
    const STATUS_CANCELLED = 3;
    
    public static $statusLabels = [
        self::STATUS_RESERVED => 'Reservado',
        self::STATUS_MODIFIED => 'Modificado',
        self::STATUS_CANCELLED => 'Cancelado',
    ];
    protected $table = 'HotelReservations';
    protected $casts = [
    ];
    public function updateTotals()
    {
        $this->refSubtotal = 0;
        $this->refTotal = 0;
        $this->markup = 0;
        $this->subtotal = 0;
        $this->total = 0;
        foreach ($this->rooms as $room) {
            $this->refSubtotal+=$room->refSubtotal;
            $this->refTotal+=$room->refTotal;
            $this->markup+=$room->markup;
            $this->subtotal+=$room->subtotal;
            $this->total+=$room->total;
        }
    }
    public function rooms()
    {
    	return $this->hasMany('App\RoomReservation','hotelReservationId','id');
    }
    public function hotel()
    {
        return $this->hasOne('App\Hotel','id','hotelId');
    }
    public function reservation()
    {
        return $this->hasOne('App\Reservation','id','reservationId');
    }
    public function getTotalAfterCancelations()
    {
        $exchangeRate = $this->total/($this->refTotal + $this->markup);
        if ($this->cancellationPenaltyTotal>0) {
            return $this->cancellationPenaltyTotal * $exchangeRate;
        }
        $amount = 0;
        foreach ($this->rooms as $room) {
            $exchangeRate = $room->total/($room->refTotal + $room->markup);
            if ($room->status == RoomReservation::STATUS_CANCELLED) {
                $amount+=$room->cancellationPenaltyTotal * (1 + OmnibeesApiComponent::$goguyMarkup) * $exchangeRate;
            }else{
                $amount+=$room->total;
            }
        }
        return $amount;
    }
    public function isPushed()
    {
        return !empty($this->refCode);
    }
}
