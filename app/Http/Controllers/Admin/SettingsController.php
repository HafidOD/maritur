<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use App\Components\SettingsComponent;
use App\Components\UploadsComponent;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }
    public function update(Request $request)
    {
    	$settings = $request->input('settings');
    	foreach ($settings as $key => $value) {
    		SettingsComponent::set($key,$value);
    	}
        $imageFile = $request->file('imageFile');
        if ($imageFile) {
            $prevImages = \Auth::user()->affiliate->getImages();
            $errors = UploadsComponent::saveFiles([$imageFile],'logos',\Auth::user()->affiliateId);
            if(count($errors)==0){
                foreach ($prevImages as $img) $img->delete();
            }
        }
        $icoFile = $request->file('icoFile');
        if ($icoFile) {
            $prevImages = \Auth::user()->affiliate->images()->where('folder','icos');
            $errors = UploadsComponent::saveFiles([$icoFile],'icos',\Auth::user()->affiliateId);
            if(count($errors)==0){
                foreach ($prevImages as $img) $img->delete();
            }
        }
    	return ['success'=>true];
    }
}
