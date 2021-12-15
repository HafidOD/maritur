<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    use ModelGallery;
    private $imagesFolderName = "sliders";
    protected $table = 'SliderImages';
}
