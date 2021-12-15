<?php 
namespace App;
use App\Components\UploadsComponent;


trait ModelGallery {
	public function getImages()
	{
	    return Upload::where([
	        'referenceId'=>$this->id,
	        'folder'=>$this->imagesFolderName,
	    ])->orderBy('orderx','ASC')->get();
	}
	public function getPrimaryImageUrl($width=false,$height=false)
    {
        $images = $this->getImages();
        return count($images)>=1?UploadsComponent::url($images[0],$width,$height):'/images/no-image.jpg';
    }
    public function images()
    {
        return $this->hasMany('App\Upload','referenceId','id');
    }
}

 ?>