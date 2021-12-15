<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use ModelGallery;
    private $imagesFolderName = "tours";
    
    protected $table = 'Tours';
    protected $guarded = [];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;

    public static $statusLabels = [
        self::STATUS_ACTIVE => 'Activo',
        self::STATUS_INACTIVE => 'Inactivo',
        self::STATUS_DELETED => 'Eliminado',
    ];

    public function city()
    {
        return $this->hasOne('App\City', 'id', 'cityId');
    }
    public function tourProvider()
    {
        return $this->hasOne('App\TourProvider', 'id', 'tourProviderId');
    }
    public function affiliate()
    {
        return $this->hasOne('App\Affiliate', 'id', 'affiliateId');
    }
    public function tourPrices()
    {
        return $this->hasMany('App\TourPrice','tourId','id');
    }
    public function getFullPath()
    {
    	return '/tours/'.$this->city->path.'/'.$this->path;
    }
    public function getBestTourPrice()
    {
        $best = null;
        foreach ($this->tourPrices as $tp) {
            if($best == null || $tp->adultPrice <= $best->adultPrice) $best = $tp;
        }
        return $best;
    }
    public function categories()
    {
        return $this->belongsToMany('App\TourCategory','ToursTourCategories','tourId','tourCategoryId');
    }
    public function destinations()
    {
        return $this->belongsToMany('App\City','DestinationsTours','tourId','destinationId');
    }
    public function getTourCategoriesIds()
    {
        $ids = [];
        foreach ($this->categories as $ct) {
            $ids[] = $ct->id;
        }
        return $ids;
    }
    public function inactiveTours()
    {
        return $this->hasMany('App\InactiveTour','tourId','id');       
    }
}
