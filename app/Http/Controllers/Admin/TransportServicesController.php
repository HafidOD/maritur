<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\TransportService;
use App\TransportServiceType;
use App\City;
use App\TourProvider;
use App\Reservation;
use App\RoomReservation;
use App\Components\OmnibeesApiComponent;
use Illuminate\Routing\Controller as BaseController;

class TransportServicesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.transport-services.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(City $dest)
    {
        return view('admin.transport-services.new',['dest'=>$dest]);
    }
    public function destination(City $dest)
    {
        $transportServiceTypes = TransportServiceType::all(); 
        return view('admin.transport-services.destination',['dest'=>$dest,'transportServiceTypes'=>$transportServiceTypes]);
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
        if(TransportService::where('name','=',$request->input('model.name'))->where('destinationId','=',$request->input('model.destinationId'))->count()>0){
            $r['message'] = 'Este nombre ya fue utilizado';
            return $r;
        }
        $model = new TransportService;
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
    public function edit(TransportService $transportService)
    {
        return view('admin.transport-services.edit',['model'=>$transportService]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TourProvider  $tourProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,City $dest)
    {
        $r = ['success'=>true];
        $transportServiceTypes = $request->input('transportServiceTypes');
        $onewayPrices = $request->input('onewayPrices');
        $roundtripPrices = $request->input('roundtripPrices');

        foreach ($transportServiceTypes as $i => $tst) {
            $ts = TransportService::firstOrNew(['destinationId' => $dest->id,'transportServiceTypeId'=>$tst]);
            $ts->onewayPrice = $onewayPrices[$i];
            $ts->roundtripPrice = $roundtripPrices[$i];
            $ts->save();
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
        $transportService = TransportService::find($id);
        $success = false;
        $message = '';
        if ($transportService) {
            $transportService->delete();
            $success = true;
        }
        return ['success'=>$success,'message'=>$message,'callbackScript'=>'reloadAjaxTables();'];
    }
	public function destinationsListing(Request $request)
    {
        $r = [];
        $search = $request->get('search',false);
        $destinations = City::orderBy('id','desc');
        if ($search) $destinations->where('name','like',"%$search%");
        $r['total'] = $destinations->count();
        foreach ($destinations->get() as $dest) {
            $r['rows'][]=[
                $dest->name,
                $dest->country?$dest->country->name:'N/A',
                count($dest->transportServices),
                "<a data-modal-width='800px' data-toggle='modal-dinamic' href='".url('admin/transport-services/destination',['destination'=>$dest->id])."' class='btn btn-primary'><i class='fa fa-edit'></i></a>",
            ];
        }
        return response()->json($r);
    }
    public function listing(City $dest)
    {
        $r = [];
        $models = $dest->transportServices;
        $r['rows'] = [];
        foreach ($models as $model) {
            $actions = [];
            $actions[] =  "<a data-modal-width='800px' data-toggle='modal-dinamic' href='".url("admin/transport-services/{$model->id}/edit")."' class='btn btn-primary'><i class='fa fa-eye'></i></a>";
            $actions[] =  "<a href='".url("admin/transport-services/delete/{$model->id}")."' class='btn btn-danger ajax-link show-warning'><i class='fa fa-trash'></i></a>";
            
            $r['rows'][]=[
                $model->name,
                number_format($model->onewayPrice,2),
                number_format($model->roundtripPrice,2),
                implode('', $actions),
            ];
        }
        $r['total'] = count($models);
        return response()->json($r);
    }
}