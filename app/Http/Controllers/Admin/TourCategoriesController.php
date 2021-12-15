<?php

namespace App\Http\Controllers\Admin;

use App\TourCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TourCategoriesController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tourCategory = new TourCategory; 
        return view('admin.tour-categories.new',['tourCategory'=>$tourCategory]);
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
        if(TourCategory::where('name','=',$request->input('model.name'))->count()>0){
            $r['message'] = 'Este nombre ya fue utilizado';
            return $r;
        }
        $tour = new TourCategory;
        $tour->fill($request->input('model'));
        if ($tour->save()) {
            $r['success'] = true;
        }else{
            $r['message'] = 'Error';
        }
        return $r;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TourCategory  $tourCategory
     * @return \Illuminate\Http\Response
     */
    public function show(TourCategory $tourCategory)
    {
        //
        echo "asdasd";

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TourCategory  $tourCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(TourCategory $tourCategory)
    {
        return view('admin.tour-categories.edit',['tourCategory'=>$tourCategory]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TourCategory  $tourCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TourCategory $tourCategory)
    {
        $r = ['success'=>false];
        $tourCategory->fill($request->input('model'));
        if ($tourCategory->save()) {
            $r['success'] = true;
        }else{
            $r['message'] = 'Error';
        }
        return $r;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TourCategory  $tourCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $tourCategory = TourCategory::find($id);
        $message = '';
        $tourCategory->delete();
        return ['success'=>true,'message'=>$message,'callbackScript'=>'reloadAjaxTables();'];
    }
    public function listing()
    {
        $r = [];
        $tours = TourCategory::orderBy('id','desc')->get();
        foreach ($tours as $tour) {
            $actions = [];
             $actions[] =  "<a data-modal-width='800px' data-toggle='modal-dinamic' href='".url("admin/tour-categories/{$tour->id}/edit")."' class='btn btn-xs btn-primary'><i class='fa fa-eye'></i></a>";
             $actions[] =  "<a href='".url("admin/tour-categories/delete/{$tour->id}")."' class='btn btn-xs btn-danger ajax-link show-warning'><i class='fa fa-trash'></i></a>";
            
            $r['rows'][]=[
                $tour->name,
                $tour->faIconClass,
                implode(' ', $actions),
            ];
        }
        $r['total'] = count($tours);
        return response()->json($r);
    }
}
