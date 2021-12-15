<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\TransportServiceType;
use App\City;
use App\TourProvider;
use App\Reservation;
use App\RoomReservation;
use App\Components\OmnibeesApiComponent;
use Illuminate\Routing\Controller as BaseController;

class TransportServiceTypesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.transport-service-types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.transport-service-types.new');
    }
    public function destination(City $dest)
    {
        return view('admin.transport-service-types.destination',['dest'=>$dest]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $r = ['success'=>false];
        // check path
        if(TransportServiceType::where('name','=',$request->input('model.name'))->count()>0){
            $r['message'] = 'Este nombre ya fue utilizado';
            return $r;
        }
        $model = new TransportServiceType;
        $model->fill($request->input('model'));
        if ($model->save()) {
            $r['success'] = true;
        }else{
            $r['message'] = 'Error';
        }
        return $r;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TourProvider  $tourProvider
     * @return \Illuminate\Http\Response
     */
    public function show(TourProvider $tourProvider)
    {
        //
        echo "asdasd";

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TourProvider  $tourProvider
     * @return \Illuminate\Http\Response
     */
    public function edit(TransportServiceType $transportServiceType)
    {
        return view('admin.transport-service-types.edit',['model'=>$transportServiceType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TourProvider  $tourProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransportServiceType $transportServiceType)
    {
        $r = ['success'=>false];
        $transportServiceType->fill($request->input('model'));
        if ($transportServiceType->save()) {
            $r['success'] = true;
        }else{
            $r['message'] = 'Error';
        }
        return $r;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TourProvider  $tourProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $transportServiceType = TransportServiceType::find($id);
        $success = false;
        $message = '';
        if ($transportServiceType) {
            $transportServiceType->delete();
            $success = true;
        }
        return ['success'=>$success,'message'=>$message,'callbackScript'=>'reloadAjaxTables();'];
    }
    public function actionGallery(Request $request,$id)
    {
        $model = TransportServiceType::find($id);
        return view('admin.transport-service-types.gallery',['model'=>$model]);
    }
    public function listing()
    {
        $r = [];
        $models = TransportServiceType::all();
        $r['rows'] = [];
        foreach ($models as $model) {
            $actions = [];
            $actions[] =  "<a data-modal-width='800px' data-toggle='modal-dinamic' href='".url("admin/transport-service-types/{$model->id}/edit")."' class='btn btn-primary'><i class='fa fa-eye'></i></a>";
            $actions[] =  "<a href='".url("admin/transport-service-types/delete/{$model->id}")."' class='btn btn-danger ajax-link show-warning'><i class='fa fa-trash'></i></a>";
            
            $r['rows'][]=[
                $model->name,
                $model->getEnumLabel('priceType'),
                $model->maxPax,
                '',
                implode('', $actions),
            ];
        }
        $r['total'] = count($models);
        return response()->json($r);
    }
}