<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliateTourPriceTrans extends Model
{
    protected $table = 'AffiliatesTourPriceTrans';
    protected $guarded = [];
    public $timestamps = false;

    public function tourPriceTrans()
    {
    	return $this->hasOne('App\TourPriceTransportation','id','tourPriceTransportationId');
    }
    public function affiliate()
    {
    	return $this->hasOne('App\Affiliate','id','affiliateId');
    }
}