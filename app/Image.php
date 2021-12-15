<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'Images';
    protected $guarded = [];
    const REFERENCE_TYPE_HOTEL = 1;
    const REFERENCE_TYPE_ROOM_TYPE = 2;
    
    public function getPublicUrl()
    {
    	return '/storage/'.$this->folder.'/'.$this->fileName;
    }
    public function getPathFile()
    {
    	return base_path().'/storage/app/public/hotels/'.$this->filename;
    }
}
