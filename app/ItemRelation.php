<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemRelation extends Model
{
    protected $table = 'ItemListRelations';
    protected $guarded = [];

    public function itemList()
    {
        return $this->hasOne('App\ItemList','id','itemListId');
    }
    public function referenceModel()
    {
        $modelsByType = [
            1=>'App\Hotel',
            2=>'App\Tour',
            3=>'App\City',
        ];
        return $this->hasOne($modelsByType[$this->itemList->itemType],'id','referenceId');
    }
}