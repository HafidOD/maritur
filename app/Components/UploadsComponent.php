<?php 
namespace App\Components;
use App\Upload;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class UploadsComponent{
	public static $sizes = [
		[150,150],
		[280,280],
		[750,550],
	];
	public static $sizesByFolder = [
		'sliders'=>[
			[150,150],
			[1350,500],
		],
	];	
	public static $folders = ['room-types','extras','transport-services','houses'];
	// public static $foldersSizes = ['tours'=>[100,100]];
	public static function url($upload,$imgWidth=false,$imgHeight=false)
	{
		$url = [];
		$url[] = env('APP_URL').'/storage';
		$url[] = $upload->folder;
		if($imgWidth!==false) $url[] = $imgWidth.'x'.$imgHeight;
		$url[] = str_replace(' ', '%20', $upload->filename);
		return implode('/', $url);
	}
	public static function saveFiles($images,$folder,$referenceId)
	{
		$ids = [];
		$errors = [];
		foreach ($images as $img) {
			$upload = new Upload;
			$upload->filename = $img->getClientOriginalName();
			$upload->folder = $folder;
			$upload->referenceId = $referenceId;
			$upload->size = $img->getSize();
			$upload->type = $img->getMimeType();
			$upload->extension = $img->getClientOriginalExtension();
			$upload->orderx = 0;
			if (Upload::where(['referenceId'=>$referenceId,'filename'=>$upload->filename,'folder'=>$folder])->count()==0) {
				if (self::saveOriginal($img,$folder) && $upload->save()) {
					self::generateSizes($upload);
					$ids[]= $upload->id;
				}else{
					$errors[] = 'Error al guardar archivo: '.$upload->filename;
				}
			}else{
				$errors[] = 'Ya existe el archivo: '.$upload->filename;
			}
		}
		return $errors;
	}
	public static function saveOriginal($file,$folder)
	{
		Image::configure(array('driver' => 'imagick'));
		$fileName = $file->getClientOriginalName();
		$img = Image::make($file->path());
		$success = true;
		Storage::makeDirectory('public/'.$folder);
		try {
			$img->save(storage_path().'/app/public/'.$folder.'/'.$fileName);
		} catch (\Exception $e) {
			$success = false;
		}
		return $success;
	}
	public static function generateImages()
	{
		$uploads = Upload::all();
		foreach ($uploads as $up) {
			self::generateSizes($up);
		}
	}
	public static function generateSizes($upload)
	{
		Image::configure(array('driver' => 'imagick'));
		$folder = $upload->folder;
		$fileName = $upload->filename;
		$extension = $upload->extension;
		$fileNameNoExt = str_replace('.'.$extension, '', $fileName);
		if (file_exists($upload->getPathFile())) {
			foreach (self::getSizes($folder) as list($sizeWidth,$sizeHeight)) {
				Storage::makeDirectory('public/'.$folder.'/'.$sizeWidth.'x'.$sizeHeight);
				$img = Image::make($upload->getPathFile());
				$curWidth = $img->width();
				$curHeight = $img->height();
				$ratio = max($sizeWidth/$curWidth,$sizeHeight/$curHeight);
				$img->resize(floor($ratio*$curWidth),$ratio*$curHeight);
				$img->crop($sizeWidth,$sizeHeight);
				try {
					$img->save(storage_path().'/app/public/'.$folder.'/'.$sizeWidth.'x'.$sizeHeight.'/'.$fileName);
				} catch (\Exception $e) {
					echo $e->getMessage();
				}
			}
		}
	}
	public static function getSizes($folder)
	{
		if (isset(self::$sizesByFolder[$folder])) return self::$sizesByFolder[$folder];
		return self::$sizes;
	}
	public static function cleanUploads()
	{
		$uploads = Upload::all();
		foreach ($uploads as $i => $up) {
			if(!file_exists($up->getPathFile())){
				$up->delete();
				echo $i.'<br>';
			}
		}
	}
}