<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Affiliate;
use Illuminate\Routing\Controller as BaseController;

class UsersController extends BaseController
{
	public function __construct()
    {
        $this->middleware('can:users');
    }
	public function index()
	{
    	return view('admin.users.index');
	}
	public function create()
	{
		$model = new User;
		$affiliates = Affiliate::where(['status'=>[Affiliate::STATUS_ACTIVE]])->get();
    	return view('admin.users.new',['model'=>$model,'affiliates'=>$affiliates]);
	}
	public function update(Request $request,User $user)
	{
        $r = ['success'=>false];
        $user->fill($request->input('model'));
        $password = $request->input('password');
        if ($password) {
        	$user->password = \Hash::make($password);
        }
		if ($user->save()) {
			$r['success'] = true;
		}else{
			$r['message'] = 'Error';
		}
		return $r;
	}
	public function store(Request $request)
	{
		$r = ['success'=>false];
		$model = new User;
        $model->fill($request->input('model'));
        $model->password = \Hash::make($request->input('password'));
		if ($model->save()) {
			$r['success'] = true;
		}else{
			$r['message'] = 'Error';
		}
		return $r;
	}
	public function delete(User $model)
	{
	    $model->status = User::STATUS_DELETED;
	    $model->save();
	    return ['success'=>true,'callbackScript'=>'reloadAjaxTables();'];
	}
	public function edit(User $user)
	{
		$affiliates = Affiliate::where(['status'=>[Affiliate::STATUS_ACTIVE]])->get();
    	return view('admin.users.edit',['model'=>$user,'affiliates'=>$affiliates]);
	}
	public function listing(Request $request)
	{
		$r = ['rows'=>[],'total'=>0];
		extract($request->only(['order','offset','limit','search']));
		$query = User::orderBy('id',$order)->where('status',[User::STATUS_ACTIVE,User::STATUS_INACTIVE]);
		$r['total'] = $query->count();
        if ($search) $query->where('name','like',"%$search%");
        $query
		->offset($offset)
		->take($limit);
        $r['rows'] = [];
        $models = $query->get();
		foreach ($models as $model) {
			$actions = [];
			$actions[] = "<a data-toggle='modal-dinamic' href='".url("admin/users/{$model->id}/edit")."' class='btn btn-primary btn-xs'><i class='fa fa-eye'></i></a>";
      		$actions[] = "<a href='".url("admin/users/{$model->id}/delete")."' class='btn btn-xs btn-danger show-warning ajax-link'><i class='fa fa-trash'></i></a>";

			$r['rows'][]=[
				$model->name,
				$model->email,
				$model->affiliate?$model->affiliate->name:'N/A',
				User::$roleLabels[$model->role],
				User::$statusLabels[$model->status],
				implode(' ', $actions),
			];
		}
		
		return response()->json($r);
	}
}