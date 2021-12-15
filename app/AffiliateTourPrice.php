<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliateTourPrice extends Model
{
    protected $table = 'AffiliatesTourPrices';
    protected $guarded = [];
    public $timestamps = false;

    public function tourPrice()
    {
    	return $this->hasOne('App\TourPrice','id','tourPriceId');
    }
    public function affiliate()
    {
    	return $this->hasOne('App\Affiliate','id','affiliateId');
    }
}