<?php 
	namespace App\Components;
	
use Illuminate\Support\Facades\Storage;
use App\Image;

class ImagesComponent{
    public static function getLocalImage($externalUrl,$hotel,$referenceType=1)
    {
        $image = Image::where('externalUrl',$externalUrl)->first();
        if ($image) {
          if ($image->referenceType<=0 || $image->referenceId <=0){
            $image->update([
              'referenceId'=>$hotel->id,
              'referenceType'=>$referenceType,
            ]);
          } 
          return $image->getPublicUrl();
        }else{
          // obtiene imagen
          $contents = OmnibeesApiComponent::doRequestImage($externalUrl);
          $extension = explode('.', $externalUrl);
          $extension = $extension[count($extension)-1];
          $newFileName = self::generateImageName($hotel,$extension);

          Storage::put('/public/hotels/'.$newFileName, $contents);
          // Storage::putFileAs('hotels', $contents, $newFileName);
          // Storage::put($newFileName, $contents);
          // guarda imagen
          $image = new Image;
          $image->fileName =  $newFileName;
          $image->folder = 'hotels';
          $image->externalUrl = $externalUrl;
          $image->referenceId = $hotel->id;
          $image->referenceType = $referenceType;
          $image->save();
          return $image->getPublicUrl();
        }
    }
    public static function generateImageName($hotel,$extension)
    {
      // dd($hotel);
      $number = 0;
      $path = $hotel->path;
      if ($hotel->cityCode>0) $path.='-'.$hotel->city->path;
      $aux = $path;
      while (Image::where('fileName','=',$aux.'.'.$extension)->count()>0) {
        $aux = $path.'-'.$number;
        $number++;
      }
      return $aux.'.'.$extension;
    }
    public static function cleanImages()
    {
      $images = Image::all();
      foreach ($images as $i => $img) {
        if(!file_exists($img->getPathFile())){
          $img->delete();
          echo $i.'<br>';
        }
      }
    }
}