<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Hotel;
use App\City;
use Illuminate\Routing\Controller as BaseController;

class HotelsController extends BaseController
{
	public function search(Request $request)
	{
		$term = $request->input('q');
		$models = Hotel::where('name','like',"%$term%");
		$models = $models->get();
		$data = [];
		foreach ($models as $model) {
			$data[] = [
				'id'=>$model->id,
				'label'=>$model->name,
			];
		}
		return $data;
	}
}