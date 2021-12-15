<?php

namespace App\Http\Controllers\Admin;

use App\TourProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TourProvidersController extends Controller
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
        $tourProvider = new TourProvider; 
        return view('admin.tour-providers.new',['tourProvider'=>$tourProvider]);
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
        if(TourProvider::where('name','=',$request->input('model.name'))->count()>0){
            $r['message'] = 'Este nombre ya fue utilizado';
            return $r;
        }
        $tour = new TourProvider;
        $tour->status = 1;
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
    public function edit(TourProvider $tourProvider)
    {
        return view('admin.tour-providers.edit',['tourProvider'=>$tourProvider]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TourProvider  $tourProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TourProvider $tourProvider)
    {
        $r = ['success'=>false];
        $tourProvider->fill($request->input('model'));
        if ($tourProvider->save()) {
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
        $tourProvider = TourProvider::find($id);
        $success = false;
        $message = '';
        if ($tourProvider) {
            if (count($tourProvider->tours)>0) {
                $message = 'Existen tours relacionados a este proveedor, elimine los tours';
            }else{
                $tourProvider->delete();
                $success = true;
            }
        }
        return ['success'=>$success,'message'=>$message,'callbackScript'=>'reloadAjaxTables();'];
    }
    public function listing()
    {
        $r = [];
        $tours = TourProvider::orderBy('id','desc')->get();
        foreach ($tours as $tour) {
            $actions = [];
             $actions[] =  "<a data-modal-width='800px' data-toggle='modal-dinamic' href='".url("admin/tour-providers/{$tour->id}/edit")."' class='btn btn-xs btn-primary'><i class='fa fa-eye'></i></a>";
             $actions[] =  "<a href='".url("admin/tour-providers/delete/{$tour->id}")."' class='btn btn-xs btn-danger ajax-link show-warning'><i class='fa fa-trash'></i></a>";
            
            $r['rows'][]=[
                $tour->name,
                implode(' ', $actions),
            ];
        }
        $r['total'] = count($tours);
        return response()->json($r);
    }
}
