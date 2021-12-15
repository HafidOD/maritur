<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Tour;
use App\TourPrice;
use App\City;
use App\TourProvider;
use App\TourReservation;
use App\Reservation;
use App\RoomReservation;
use App\Components\OmnibeesApiComponent;
use Illuminate\Routing\Controller as BaseController;

class TourPricesController extends BaseController
{
  public function new(Tour $tour)
  {
    $tp = new TourPrice;
    $tp->tourId = $tour->id;
    return view('admin.tour-prices.new',[
      'tourPrice'=>$tp,
    ]);
  }
  public function update(Request $request,TourPrice $model)
  {
    $r = ['success'=>false];
    $model->fill($request->input('model'));
    $model->weekDays = implode('', $request->input('model.weekDays'));
    if ($model->save()) {
      $r['success'] = true;
    }else{
      $r['message'] = 'Error';
    }
    return $r;
  }
  public function store(Request $request)
  {
    $tp = new TourPrice;
    $tp->fill($request->input('model'));
    $tp->weekDays = implode('', $request->input('model.weekDays'));
    if ($tp->save()) {
      $r['success'] = true;
    }else{
      $r['message'] = 'Error';
    }
    return $r;
  }
  public function edit(TourPrice $tourPrice)
  {
    return view('admin.tour-prices.edit',[
      'tourPrice'=>$tourPrice,
    ]);
  }
  public function delete(TourPrice $model)
  {
    // $model->delete();
    if(TourReservation::where('tourPriceId',$model->id)->count()>0){
      $model->status = 3;
      $model->save();
    }else{
      $model->delete();
    }
    return ['success'=>true,'callbackScript'=>'reloadAjaxTables();'];
  }
  public function listing(Request $request, Tour $tour)
  {
    $r = [];
    $tourPrices = $tour->tourPrices()->orderBy('id','desc')->get();
    $r['rows'] = [];
    foreach ($tourPrices as $tp) {
      $actions = [];
      $actions[] = "<a data-modal-width='700px' data-toggle='modal-dinamic' href='".url('admin/tour-prices/edit',['id'=>$tp->id])."' class='btn btn-primary btn-xs'><i class='fa fa-eye'></i></a>";
      $actions[] = "<a href='".url('admin/tour-prices/delete',['id'=>$tp->id])."' class='btn btn-xs btn-danger show-warning ajax-link'><i class='fa fa-trash'></i></a>";

      $r['rows'][]=[
        $tp->name,
        number_format($tp->adultPrice,2),
        number_format($tp->childrenPrice,2),
        implode(' ', $actions),
      ];
    }
    $r['total'] = count($tourPrices);
    return response()->json($r);
  }
}