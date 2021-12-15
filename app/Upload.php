<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = 'Uploads';
    public function getFileUrl()
    {
    	return env('APP_URL').'/storage/'.$this->folder.'/'.$this->filename;
    }
    public function getPathFile()
    {
    	return base_path().'/storage/app/public/'.$this->folder.'/'.$this->filename;
    }
}
