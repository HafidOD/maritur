<?php

namespace App;

class TransportServiceType extends BaseModel
{
    use ModelGallery;
    private $imagesFolderName = "transport-services";

    protected $table = 'TransportServiceTypes';
    protected $guarded = [];

    const PRICE_TYPE_PAX = 1;
    const PRICE_TYPE_TRIP = 2;
    
    public $priceTypeLabels = [
        self::PRICE_TYPE_PAX => 'Por persona',
        self::PRICE_TYPE_TRIP => 'Por vehículo',
    ];
}
