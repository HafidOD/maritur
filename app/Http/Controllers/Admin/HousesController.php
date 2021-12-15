<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\House;
use App\City;
use Illuminate\Routing\Controller as BaseController;

class HousesController extends BaseController
{
	public function actionIndex()
	{
    	return view('admin.houses.index');
	}
	public function actionNew()
	{
		$cities = City::all();
		return view('admin.houses.new',[
			'model'=>new House,
			'cities'=>$cities,
		]);
	}
	public function actionGallery(Request $request,$id)
	{
		$model = House::find($id);
    	return view('admin.houses.gallery',['model'=>$model]);
	}
	public function actionUpload(Request $request,$id)
	{
		$model = House::find($id);
		$image = $request->file('image');
		dd($image);
	}
	public function actionUpdate(Request $request,$id)
	{
        $r = ['success'=>false];
		$model = House::find($id);
        $model->fill($request->input('model'));
		if ($model->save()) {
			$r['success'] = true;
		}else{
			$r['message'] = 'Error';
		}
		return $r;
	}
	public function actionCreate(Request $request)
	{
		$r = ['success'=>false];
        // check path
        if(House::where('path','=',$request->input('model.path'))->count()>0){
      		$r['message'] = 'La url ya fue utilizada';
      		return $r;
      	}
		$house = new House;
        $house->fill($request->input('model'));
		if ($house->save()) {
			$r['success'] = true;
		}else{
			$r['message'] = 'Error';
		}
		return $r;
	}
	public function actionEdit($id)
	{
		$model = House::find($id);
		$cities = City::all();
		return view('admin.houses.edit',[
			'model'=>$model,
			'cities'=>$cities,
		]);
	}
	public function actionListing(Request $request)
	{
		$r = [];
        $search = $request->get('search',false);
		$models = House::orderBy('id','desc');
        if ($search) $models->where('name','like',"%$search%");
		$models = $models->get();
		foreach ($models as $model) {
			$r['rows'][]=[
				$model->name,
				$model->destination->name,
				"<img src='{$model->getPrimaryImageUrl()}' width='100'>",
				"<a data-modal-width='90%' data-toggle='modal-dinamic' href='".url('admin/houses/edit',['id'=>$model->id])."' class='btn btn-primary'><i class='fa fa-eye'></i></a>",
			];
		}
		$r['total'] = count($models);
		return response()->json($r);
	}
}