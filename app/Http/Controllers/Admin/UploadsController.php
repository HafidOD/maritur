<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Tour;
use App\City;
use App\Upload;
use App\TourProvider;
use App\Reservation;
use App\RoomReservation;
use App\Components\OmnibeesApiComponent;
use App\Components\UploadsComponent;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class UploadsController extends BaseController
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	public function actionAdd(Request $request)
	{
		$success = true;
		$message = '';
		$images = $request->file('images');
		$referenceId = $request->input('referenceId');
		$folder = $request->input('folder');
		// busca imagen antigua
		// $upload = Upload::where(['filename'=>$]);
		$errors = UploadsComponent::saveFiles($images,$folder,$referenceId);
		if ($errors) {
			$message = '<li>'.implode('</li><li>', $errors).'</li>';
			$success = false;
		}
		return ['success'=>$success,'message'=>$message];
	}
	public function actionUpdatePositions(Request $request)
	{
		$positions = $request->input('positions',[]);
		$uploads = Upload::find(array_keys($positions));
		foreach ($uploads as $up) {
			$up->orderx = $positions[$up->id];
			$up->save();
		}
		return ['success'=>true];
	}
	public function actionDelete(Request $request,$id)
	{
		$upload = Upload::find($id);
		if ($upload) {
			if(file_exists($upload->getPathFile())) unlink($upload->getPathFile());
			$upload->delete();
		}
		return ['success'=>true,'callbackScript'=>'reloadCurrentModal();'];
	}
}