<?php

namespace App\Http\Controllers\Admin;

use App\ItemList;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ItemListsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.item-lists.index');
    }

    public function create()
    {
        $itemList = new ItemList; 
        return view('admin.item-lists.new',['itemList'=>$itemList]);
    }

    public function store(Request $request)
    {
        $r = ['success'=>false];
        $list = new ItemList;
        $list->fill($request->input('model'));
        $list->status = 1;
        $list->affiliateId = \Auth::user()->affiliateId;

        if ($list->save()) {
            $r['success'] = true;
        }else{
            $r['message'] = 'Error';
        }
        return $r;
    }

    public function show(ItemList $itemList)
    {
        //
        echo "asdasd";

    }

    public function edit(ItemList $itemList)
    {
        return view('admin.item-lists.edit',['itemList'=>$itemList]);
    }

    public function update(Request $request, ItemList $itemList)
    {
        $r = ['success'=>false];
        $itemList->fill($request->input('model'));
        if ($itemList->save()) {
            $r['success'] = true;
        }else{
            $r['message'] = 'Error';
        }
        return $r;
    }

    public function destroy(Request $request,$id)
    {
        $itemList = ItemList::find($id);
        $itemList->delete();
        return ['success'=>true,'message'=>"",'callbackScript'=>'reloadAjaxTables();'];
    }
    public function listing(Request $request)
    {
        $r = [];
        $lists = ItemList::orderBy('orderx','asc')->where('affiliateId',\Auth::user()->affiliateId);
        $lists = $lists->get();
        foreach ($lists as $list) {
            $actions = [];
             $actions[] =  "<a data-modal-width='700px' data-toggle='modal-dinamic' href='".url("admin/item-lists/{$list->id}/edit")."' class='btn btn-xs btn-primary'><i class='fa fa-eye'></i></a>";
             $actions[] =  "<a href='".url("admin/item-lists/delete/{$list->id}")."' class='btn btn-danger ajax-link btn-xs show-warning'><i class='fa fa-trash'></i></a>";
            
            $r['rows'][]=[
                $list->name,
                $list->orderx,
                $list::$itemTypeLabels[$list->itemType],
                $list::$sectionLabels[$list->section],
                $list->itemRelations()->count(),
                implode('', $actions),
            ];
        }
        $r['total'] = count($lists);
        return response()->json($r);
    }
}
