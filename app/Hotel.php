<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    //
    protected $table = 'Hotels';
    public $timestamps = false;
    public function city()
    {
    	return $this->hasOne('App\City', 'code', 'cityCode');
    }
    public function state()
    {
    	return $this->hasOne('App\State', 'code', 'stateCode');
    }
    public function getPrimaryImageUrl()
    {
        $firstImage = $this->images()->first();
        return $firstImage?$firstImage->getPublicUrl():'/images/no-image.jpg';
    }
    public function images()
    {
        return $this->hasMany('App\Image','referenceId','id')->where(['folder'=>'hotels','referenceType'=>1]);
    }
    public function getFullPath()
    {
        return '/hotels/'.$this->city->path.'/'.$this->path;
    }

}
