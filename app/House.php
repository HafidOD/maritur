<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $table = 'Houses';
    protected $guarded = [];
    public function destination()
    {
        return $this->hasOne('App\City', 'id', 'destinationId');
    }
    public function getImages()
    {
        return Upload::where([
            'referenceId'=>$this->id,
            'folder'=>'houses',
        ])->orderBy('orderx','ASC')->get();
    }
    public function getFullPath()
    {
    	return '/vacational-rentals/'.$this->destination->path.'/'.$this->path;
    }
    public function getPrimaryImageUrl()
    {
        $images = $this->getImages();
        return count($images)>=1?$images[0]->getFileUrl():'/images/no-image.jpg';
    }
}
