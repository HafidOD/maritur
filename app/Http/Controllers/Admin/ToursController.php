<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Tour;
use App\TourPrice;
use App\City;
use App\TourProvider;
use App\AffiliateTourPrice;
use App\AffiliateTourPriceTrans;
use App\TourCategory;
use App\Reservation;
use App\RoomReservation;
use App\Components\ToursComponent;
use Illuminate\Routing\Controller as BaseController;

class ToursController extends BaseController
{
	public function actionIndex()
	{
    	return view('admin.tours.index');
	}
	public function actionNew()
	{
		$tourProviders = TourProvider::all();
		$tourCategories = TourCategory::all();
		$cities = City::all();
		return view('admin.tours.new',[
			'tour'=>new Tour,
			'cities'=>$cities,
			'tourProviders'=>$tourProviders,
			'tourCategories'=>$tourCategories,
			'edit'=>true,
		]);
	}
	public function actionGallery(Request $request,$id)
	{
		$tour = Tour::find($id);
    	return view('admin.tours.gallery',['tour'=>$tour]);
	}
	public function actionUpload(Request $request,$id)
	{
		$tour = Tour::find($id);
		$image = $request->file('image');
		dd($image);
	}
	public function actionUpdate(Request $request,$id)
	{
        $r = ['success'=>false];
		$tour = Tour::where(['id'=>$id,'affiliateId'=>\Auth::user()->affiliateId])->first();
		$tour->prices = " ";
        $tour->fill($request->input('model'));
        $tour->categories()->sync($request->input('categories',[]));
		if ($tour->save()) {
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
        if(Tour::where('path','=',$request->input('model.path'))->count()>0){
      		$r['message'] = 'La url ya fue utilizada';
      		return $r;
      	}
		$tour = new Tour;
		$tour->referenceId = -1;
		$tour->prices = " ";
		$tour->affiliateId = \Auth::user()->affiliateId;
        $tour->fill($request->input('model'));
		if ($tour->save()) {
        	$tour->categories()->sync($request->input('categories',[]));
			$r['success'] = true;
		}else{
			$r['message'] = 'Error';
		}
		return $r;
	}
	public function delete(Tour $model)
	{
	    if($model->tourPrices()->has('tourReservations')->count()>0){
	    	foreach ($model->tourPrices as $tp) {
		      $tp->status = 3;
		      $tp->save();
	    	}
	    	$model->status = 3;
	    	$model->save();
	    }else{
	      $model->delete();
	    }
	    return ['success'=>true,'callbackScript'=>'reloadAjaxTables();'];
	}
	public function actionEdit($id)
	{
		$tour = Tour::find($id);
		$tourProviders = TourProvider::all();
		$tourCategories = TourCategory::all();
		$cities = City::all();
		$edit = $tour->affiliateId == \Auth::user()->affiliateId; 
		return view('admin.tours.edit',[
			'tour'=>$tour,
			'cities'=>$cities,
			'tourProviders'=>$tourProviders,
			'tourCategories'=>$tourCategories,
			'edit'=>$edit,
		]);
	}
	public function addDestination(Request $request, Tour $tour)
	{
		return view('admin.tours.add-destination',[
			'tour'=>$tour,
		]);		
	}
	public function deleteDestination(Request $request, Tour $tour,$destinationId)
	{
		$success = false;
		$message="";
		$tour->destinations()->detach($destinationId);
		return ['success'=>true,'callbackScript'=>'reloadAjaxTables();'];
	}
	public function doAddDestination(Request $request, Tour $tour)
	{
		$destinationId = $request->get('destinationId');
		$success = false;
		$message="";
		if($tour->destinations->contains($destinationId)){
			$message = "El destino ya se encuentra agreado a este tour";
		}else{
			$tour->destinations()->attach($destinationId);
			$success = true;
		}
		return ['success'=>$success,'message'=>$message];
	}
	public function destinationsListing(Request $request,Tour $tour)
	{
		$r = [];
        $search = $request->get('search',false);
		$destinations = $tour->destinations()->orderBy('name','asc');
        if ($search) $destinations->where('name','like',"%$search%");
        $destinations = $destinations->get();
        $r['rows'] = [];
		foreach ($destinations as $dest) {
			$actions = [];
      		$actions[] = "<a href='".url('admin/tours/delete-destination',['tour'=>$tour->id,'dest'=>$dest->id])."' class='btn btn-xs btn-danger show-warning ajax-link'><i class='fa fa-trash'></i></a>";

			$r['rows'][]=[
				$dest->name,
				implode(' ', $actions),
			];
		}
		$r['total'] = count($destinations);
		
		return response()->json($r);
	}
	public function deactivateTour(Tour $tour)
	{
		ToursComponent::deactivateTour($tour->id,\Auth::user()->affiliateId);
		return ['success'=>true,'message'=>'Tour <strong>desactivado</strong> con exito','callbackScript'=>'reloadAjaxTables();'];
	}
	public function activateTour(Tour $tour)
	{
		ToursComponent::activateTour($tour->id,\Auth::user()->affiliateId);
		return ['success'=>true,'message'=>'Tour <strong>activado</strong> con exito','callbackScript'=>'reloadAjaxTables();'];
	}
	public function actionListing(Request $request)
	{
		$r = [];
        $search = $request->get('search',false);
		$tours = Tour::orderBy('id','desc');
        if ($search) $tours->where('name','like',"%$search%");
        $tours = $tours->get();
        $r['rows'] = [];
		foreach ($tours as $tour) {
			$actions = [];
			if (\Gate::allows('tours.edit', $tour)) {
				$actions[] = "<a data-modal-width='90%' data-toggle='modal-dinamic' href='".url('admin/tours/edit',['id'=>$tour->id])."' class='btn btn-primary btn-xs'><i class='fa fa-edit'></i></a>";
      			$actions[] = "<a href='".url('admin/tours/delete',['id'=>$tour->id])."' class='btn btn-xs btn-danger show-warning ajax-link'><i class='fa fa-trash'></i></a>";
			}else{
				$actions[] = "<a data-modal-width='70%' data-toggle='modal-dinamic' href='".url('admin/tours/affiliate-prices',['id'=>$tour->id])."' class='btn btn-info btn-xs'><i class='fa fa-usd'></i></a>";
				if (ToursComponent::isActiveForAffiliate($tour->id,\Auth::user()->affiliateId)) {
					$actions[] = "<a href='".url('admin/tours/deactivate-tour',['id'=>$tour->id])."' class='btn btn-danger btn-xs ajax-link'><i class='fa fa-remove'></i></a>";
				}else{
					$actions[] = "<a href='".url('admin/tours/activate-tour',['id'=>$tour->id])."' class='btn btn-success btn-xs ajax-link'><i class='fa fa-check'></i></a>";
				}
			}
      		$actions[] = "<a href='https://".\Auth::user()->affiliate->domain.$tour->getFullPath()."' target='_blank' class='btn btn-xs btn-warning' ><i class='fa fa-eye'></i></a>";

			$r['rows'][]=[
				$tour->name,
				$tour->city->name,
				$tour->tourPrices()->count(),
				$tour->affiliate->name,
				ToursComponent::isActiveForAffiliate($tour->id,\Auth::user()->affiliateId)?$tour::$statusLabels[1]:$tour::$statusLabels[2],
				"<img src='{$tour->getPrimaryImageUrl(150,150)}' width='100'>",
				implode(' ', $actions),
			];
		}
		$r['total'] = count($tours);
		
		return response()->json($r);
	}
	public function affiliatePrices(Tour $tour)
	{
		return view('admin.tours.affiliate-prices',['tour'=>$tour]);
	}
	public function updateAffiliatePrices(Request $request, Tour $tour)
	{
		$tourPricesAdults = $request->input('tourPricesAdults',[]);
		$tourPricesChildren = $request->input('tourPricesChildren',[]);
		$tourPricesTransportAdults = $request->input('tourPricesTransportAdults',[]);
		$tourPricesTransportChildren = $request->input('tourPricesTransportChildren',[]);

		foreach ($tourPricesAdults as $tourPriceId => $adultPrice) {
			$childrenPrice = $tourPricesChildren[$tourPriceId];
			$aux = AffiliateTourPrice::firstOrNew(['tourPriceId'=>$tourPriceId,'affiliateId'=>\Auth::user()->affiliateId]);
			$aux->adultPrice = $adultPrice;
			$aux->childrenPrice = $childrenPrice;
			$aux->save();
		}
		foreach ($tourPricesTransportAdults	 as $tourPriceTransId => $adultPrice) {
			$childrenPrice = $tourPricesTransportChildren[$tourPriceTransId];
			$aux = AffiliateTourPriceTrans::firstOrNew(['tourPriceTransportationId'=>$tourPriceTransId,'affiliateId'=>\Auth::user()->affiliateId]);
			$aux->adultPrice = $adultPrice;
			$aux->childrenPrice = $childrenPrice;
			$aux->save();
		}
		return ['success'=>true];
	}
	public function list($listName)
	{
		$tours = Tour::where('status',[Tour::STATUS_ACTIVE,Tour::STATUS_INACTIVE])->get();
		return view('admin.tours.list',['tours'=>$tours,'listName'=>$listName]);
	}
	public function updateList($listName,Request $request)
	{
		$keys = $request->input('tourPrices',[]);
		$allTps = TourPrice::all();
		foreach ($allTps as $tp) {
			if(in_array($tp->id, $keys)) $tp->addToList($listName)->save();
			else $tp->removeFromList($listName)->save();
		}
		return response()->json(['success'=>true]);
	}
	public function search(Request $request)
	{
		$term = $request->input('q');
		$models = Tour::where('name','like',"%$term%");
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