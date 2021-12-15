<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportService extends Model
{
    protected $table = 'TransportServices';
    protected $guarded = [];
    public function destination()
    {
        return $this->hasOne('App\City', 'id', 'destinationId');
    }
    public function transportServiceType()
    {
        return $this->hasOne('App\TransportServiceType', 'id', 'transportServiceTypeId');
    }
}
