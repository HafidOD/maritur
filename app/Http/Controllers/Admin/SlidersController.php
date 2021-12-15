<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\SliderImage;
use App\Upload;
use App\Components\UploadsComponent;
use Illuminate\Routing\Controller as BaseController;

class SlidersController extends BaseController
{
	public function slider($section)
	{
		$sectionNames = [
			'HOME',
			'TOURS',
			'TRANSFERS',
			'OFFERS',
		];
		$sliders = SliderImage::where('section',$section)
			->where('affiliateId',\Auth::user()->affiliateId)
			->orderBy('orderx','ASC')->get();
		return view('admin.sliders.section',['section'=>$section,'sectionName'=>$sectionNames[$section],'sliders'=>$sliders]);
	}
	public function gallery($section)
	{
		$sectionNames = [
			'HOME',
			'TOURS',
			'TRANSFERS',
			'OFFERS',
		];
		$images = SliderImage::where('section',$section)
			->where('affiliateId',\Auth::user()->affiliateId)
			->orderBy('orderx','ASC')
			->get();
		return view('admin.sliders.gallery',['images'=>$images,'section'=>$section,'sectionName'=>$sectionNames[$section]]);
	}
	public function upload(Request $request,$section)
	{
		$success = false;
		$message = '';
		$image = $request->file('image');

		$url = $request->input('url');
		$orderx = $request->input('orderx');
		$altText = $request->input('altText');

		$slider = new SliderImage;
		$slider->url = $url;
		$slider->orderx = $orderx;
		$slider->section = $section;
		$slider->altText = $altText;
		$slider->affiliateId = \Auth::user()->affiliateId;
		// busca imagen antigua
		// $upload = Upload::where(['filename'=>$]);
		if ($slider->save()) {
			$errors = UploadsComponent::saveFiles([$image],'sliders',$slider->id);
			if ($errors) {
				$message = '<li>'.implode('</li><li>', $errors).'</li>';
				$success = false;
				$slider->delete();
			}else{
				$success = true;
			}
		}
		return ['success'=>$success,'message'=>$message];
	}
	public function delete(Request $request,$id)
	{
		$slider = SliderImage::find($id);
		if ($slider) {
			$slider->delete();
			$images = $slider->getImages();
			foreach ($images as $img) {
				if(file_exists($img->getPathFile())) unlink($img->getPathFile());
				$img->delete();
			}
		}
		return ['success'=>true,'callbackScript'=>'reloadCurrentModal();'];
	}

}