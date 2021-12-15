<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemList extends Model
{
    protected $table = 'ItemLists';
    protected $guarded = [];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;

    public static $statusLabels = [
        self::STATUS_ACTIVE => 'Activo',
        self::STATUS_INACTIVE => 'Inactivo',
        self::STATUS_DELETED => 'Eliminado',
    ];
    const ITEM_TYPE_HOTEL = 1;
    const ITEM_TYPE_TOUR = 2;
    const ITEM_TYPE_DEST = 3;

    public static $itemTypeLabels = [
        self::ITEM_TYPE_HOTEL => 'Hotel',
        self::ITEM_TYPE_TOUR => 'Tour',
        self::ITEM_TYPE_DEST => 'Destino',
    ];
    const SECTION_HOME = 1;
    const SECTION_TOURS = 2;
    const SECTION_OFFERS = 3;

    public static $sectionLabels = [
        self::SECTION_HOME => 'Home',
        self::SECTION_TOURS => 'Tours',
        self::SECTION_OFFERS => 'Offers',
    ];
    public function itemRelations()
    {
        return $this->hasMany('App\ItemRelation','itemListId','id');
    }
}