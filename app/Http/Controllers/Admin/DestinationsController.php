<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Country;
use App\Components\DestinationsComponent;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DestinationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        return view('admin.destinations.index',['countries'=>$countries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $model = new City; 
        return view('admin.destinations.new',['model'=>$model,'countries'=>$countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $r = ['success'=>false];
        // check path
        if(City::where('name','=',$request->input('model.name'))->count()>0){
            $r['message'] = 'Este nombre ya fue utilizado';
            return $r;
        }
        if(City::where('path','=',$request->input('model.path'))->count()>0){
            $r['message'] = 'La url ya fue utilizada';
            return $r;
        }
        $city = new City;
        $city->code = -1;
        $city->hasHotels = 0;
        $city->fill($request->input('model'));
        if ($city->save()) {
            $r['success'] = true;
        }else{
            $r['message'] = 'Error';
        }
        return $r;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $destination)
    {
        $desc = DestinationsComponent::getDescriptionByAffiliate($destination);
        return view('admin.destinations.info',['dest'=>$destination,'desc'=>$desc]);
    }
    public function setInfo(Request $request, City $destination)
    {
        DestinationsComponent::setDescriptionByAffiliate($destination,$request->input('description'));
        return ['success'=>true];
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        //
    }
    public function listing(Request $request)
    {
        $r = [];
        $search = $request->get('search',false);
        $destinations = City::orderBy('id','desc');
        if ($search) $destinations->where('name','like',"%$search%");
        $r['total'] = $destinations->count();
        foreach ($destinations->get() as $dest) {
            $actions = [];
            if(\SC::$affiliateId==1) $actions[] = "<a data-modal-width='800px' data-toggle='modal-dinamic' href='".url('admin/destinations/gallery',[$dest->id])."' class='btn btn-sm btn-primary'><i class='fa fa-image'></i></a>";
            $actions[] = "<a data-modal-width='800px' data-toggle='modal-dinamic' href='".url('admin/destinations',[$dest->id])."' class='btn btn-sm btn-warning'><i class='fa fa-info'></i></a>";
            $r['rows'][]=[
                $dest->name,
                $dest->country?$dest->country->name:'N/A',
                "<img src='{$dest->getPrimaryImageUrl()}' width='50'>",
                implode(' ', $actions),
            ];
        }
        return response()->json($r);
    }
    public function actionGallery(City $destination)
    {
        return view('admin.destinations.gallery',['dest'=>$destination]);
    }
    public function search(Request $request)
    {
        $term = $request->input('q');
        $models = City::where('name','like',"%$term%");
        $models = $models->take(10)->get();
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
