<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Tour;
use App\TourPrice;
use App\TourPriceTransportation;
use App\City;
use App\TourProvider;
use App\Reservation;
use App\RoomReservation;
use App\Components\OmnibeesApiComponent;
use Illuminate\Routing\Controller as BaseController;

class AffiliatesTourPricesController extends BaseController
{
  public function new(TourPrice $tourPrice)
  {
    $tpt = new TourPriceTransportation;
    $tpt->tourPriceId = $tourPrice->id;
    return view('admin.tour-price-transportations.new',[
      'destinations'=>City::orderBy('name','asc')->get(),
      'model'=>$tpt,
    ]);
  }
  public function update(Request $request,TourPriceTransportation $model)
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
    $tp = new TourPriceTransportation;
    $tp->fill($request->input('model'));
    if ($tp->save()) {
      $r['success'] = true;
    }else{
      $r['message'] = 'Error';
    }
    return $r;
  }
  public function delete(TourPriceTransportation $model)
  {
    $model->delete();
    return ['success'=>true,'callbackScript'=>'reloadAjaxTables();'];
  }
  public function edit(TourPriceTransportation $model)
  {
    return view('admin.tour-price-transportations.edit',[
      'model'=>$model,
      'destinations'=>City::orderBy('name','asc')->get(),
    ]);
  }
  public function listing(Request $request, TourPrice $tourPrice)
  {
    $r = [];
    $tPrices = $tourPrice->tourPriceTransportations()->get();
    $r['rows'] = [];
    foreach ($tPrices as $model) {
      $actions = [];
      $actions[] = "<a data-toggle='modal-dinamic' href='".url('admin/tour-price-transportations/edit',['id'=>$model->id])."' class='btn btn-xs btn-primary'><i class='fa fa-eye'></i></a>";
      $actions[] = "<a href='".url('admin/tour-price-transportations/delete',['id'=>$model->id])."' class='btn btn-xs btn-danger show-warning ajax-link'><i class='fa fa-trash'></i></a>";

      $r['rows'][]=[
        $model->destination->name,
        number_format($model->adultPrice,2),
        number_format($model->childrenPrice,2),
        implode(' ', $actions),
      ];
    }
    $r['total'] = count($tPrices);
    return response()->json($r);
  }
}