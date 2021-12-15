<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Affiliate;
use Illuminate\Routing\Controller as BaseController;

class AffiliatesController extends BaseController
{
	public function __construct()
	{
	    $this->middleware('can:users');
	}
	public function index()
	{
    	return view('admin.affiliates.index');
	}
	public function create()
	{
		$model = new Affiliate;
    	return view('admin.affiliates.new',['model'=>$model]);
	}
	public function update(Request $request,Affiliate $affiliate)
	{
        $r = ['success'=>false];
        $model->fill($request->input('model'));
		if ($model->save()) {
			$r['success'] = true;
		}else{
			$r['message'] = 'Error';
		}
		return $r;
	}
	public function store(Request $request)
	{
		$r = ['success'=>false];
		$model = new Affiliate;
        $model->fill($request->input('model'));
		if ($model->save()) {
			$r['success'] = true;
		}else{
			$r['message'] = 'Error';
		}
		return $r;
	}
	public function delete(Affiliate $model)
	{
	    $model->status = Affiliate::STATUS_DELETED;
	    $model->save();
	    return ['success'=>true,'callbackScript'=>'reloadAjaxTables();'];
	}
	public function edit(Affiliate $affiliate)
	{
    	return view('admin.affiliates.edit',['model'=>$affiliate]);
	}
	public function listing(Request $request)
	{
		$r = ['rows'=>[],'total'=>0];
		extract($request->only(['order','offset','limit','search']));
		$query = Affiliate::orderBy('id',$order)->where('status',[Affiliate::STATUS_ACTIVE,Affiliate::STATUS_INACTIVE]);
		$r['total'] = $query->count();
        if ($search) $query->where('name','like',"%$search%");
        $query
		->offset($offset)
		->take($limit);
        $r['rows'] = [];
        $models = $query->get();
		foreach ($models as $model) {
			$actions = [];
			$actions[] = "<a data-toggle='modal-dinamic' href='".url("admin/affiliates/{$model->id}/edit")."' class='btn btn-primary btn-xs'><i class='fa fa-eye'></i></a>";
      		$actions[] = "<a href='".url("admin/affiliates/{$model->id}/delete")."' class='btn btn-xs btn-danger show-warning ajax-link'><i class='fa fa-trash'></i></a>";

			$r['rows'][]=[
				$model->name,
				$model->domain,
				Affiliate::$statusLabels[$model->status],
				implode(' ', $actions),
			];
		}
		
		return response()->json($r);
	}
}