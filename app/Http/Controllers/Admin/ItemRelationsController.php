<?php

namespace App\Http\Controllers\Admin;

use App\ItemRelation;
use App\ItemList;
use App\Components\OmnibeesApiComponent;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ItemRelationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.item-relations.index');
    }

    public function new(ItemList $itemList)
    {
        $itemRelation = new ItemRelation; 
        $itemRelation->itemListId = $itemList->id;
        return view('admin.item-relations.new',['model'=>$itemRelation]);
    }

    public function store(Request $request)
    {
        $r = ['success'=>false];
        $itemRel = new ItemRelation;
        $itemRel->fromPrice = 0;
        $itemRel->currencyCode = 'USD';
        $itemRel->fill($request->input('model'));
        if ($itemRel->itemList->itemType==1 && $itemRel->referenceModel->stars==0) {
        	$hotel = $itemRel->referenceModel;
        	$hotel->stars = $this->getHotelStars($hotel);
        	$hotel->save();
        }
        if ($itemRel->save()) {
            $r['success'] = true;
        }else{
            $r['message'] = 'Error';
        }
        return $r;
    }

    public function show(ItemRelation $itemRelation)
    {
        //
        echo "asdasd";

    }
    public function getHotelStars($hotel)
    {
    	$infoData = OmnibeesApiComponent::getHotelInfo($hotel->code);
    	return isset($infoData->AffiliationInfo->AwardsType->Awards[0])?$infoData->AffiliationInfo->AwardsType->Awards[0]->Rating:0;
    }
    public function edit(ItemRelation $model)
    {
        return view('admin.item-relations.edit',['model'=>$model]);
    }

    public function update(Request $request, ItemRelation $model)
    {
        $r = ['success'=>false];
        $model->fill($request->input('model'));
        if ($model->itemList->itemType==1 && $model->referenceModel->stars==0) {
        	$hotel = $model->referenceModel;
        	$hotel->stars = $this->getHotelStars($hotel);
        	$hotel->save();
        }
        if ($model->save()) {
            $r['success'] = true;
        }else{
            $r['message'] = 'Error';
        }
        return $r;
    }

    public function delete(Request $request,ItemRelation $model)
    {
        $model->delete();
        $success = true;
        return ['success'=>true,'callbackScript'=>'reloadAjaxTables();'];
    }
    public function listing(Request $request)
    {
        $r = ['rows'=>[]];
        $relations = ItemRelation::where('itemListId',$request->input('item-list'))->get();
        foreach ($relations as $rel) {
            if ($rel->referenceModel) {
                $actions = [];
                 $actions[] =  "<a data-toggle='modal-dinamic' href='".url("admin/item-relations/{$rel->id}/edit")."' class='btn btn-xs btn-primary'><i class='fa fa-eye'></i></a>";
                 $actions[] =  "<a href='".url("admin/item-relations/delete/{$rel->id}")."' class='btn btn-danger ajax-link btn-xs show-warning'><i class='fa fa-trash'></i></a>";
                
                $r['rows'][]=[
                    $rel->referenceModel->name,
                    $rel->fromPrice,
                    $rel->currencyCode,
                    // $rel->orderx,
                    // $rel->itemRelations()->count(),
                    implode('', $actions),
                ];
            }
        }
        $r['total'] = count($relations);
        return response()->json($r);
    }
}
