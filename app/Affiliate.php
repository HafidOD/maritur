<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use ModelGallery;
    private $imagesFolderName = "logos";

    protected $table = 'Affiliates';
    protected $guarded = [];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;

    public static $statusLabels = [
        self::STATUS_ACTIVE => 'Activo',
        self::STATUS_INACTIVE => 'Inactivo',
        self::STATUS_DELETED => 'Eliminado',
    ];
}

