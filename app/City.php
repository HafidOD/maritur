<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    use ModelGallery;
    private $imagesFolderName = "destinations";

    protected $table = 'Cities';
    public $timestamps = false;
    protected $guarded = [];
    public function country()
    {
    	return $this->hasOne('App\Country', 'code', 'countryCode');
    }
    public function hotels()
    {
        return $this->hasMany('App\Hotel', 'cityCode', 'code');
    }
    public function availableHotels()
    {
        return $this->hasMany('App\Hotel', 'cityCode', 'code')->where('available',true);
    }
    public function tours()
    {
        return $this->hasMany('App\Tour', 'cityId', 'id');
    }
    public function houses()
    {
        return $this->hasMany('App\House', 'destinationId', 'id');
    }
    public function destinationInfos()
    {
    	return $this->hasMany('App\DestinationInfo', 'destinationId', 'id');
    }
    public function transportServices()
    {
        return $this->hasMany('App\TransportService','destinationId','id');
    }
    public function validTransportServices()
    {
        return $this->hasMany('App\TransportService','destinationId','id')->where('onewayPrice','>',0)->where('roundtripPrice','>',0);
    }
    public function getPrices($transportServiceType)
    {
        $oneway = 0;
        $roundtrip = 0;
        $ts = TransportService::where('destinationId',$this->id)
        ->where('transportServiceTypeId',$transportServiceType->id)
        ->first();
        if ($ts) {
            $oneway = $ts->onewayPrice;
            $roundtrip = $ts->roundtripPrice;
        }
        return [$oneway,$roundtrip];
    }
    public function fullName()
    {
        return $this->name.', '.$this->country->name;
    }
    public function getFullPath($cat='')
    {
        return '/'.$cat.'/'.$this->path;
    }

}
