<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Edit</h3>
</div>
<div class="modal-body">
<form action="{{url("admin/item-relations/update/{$model->id}")}}" method="post" class="validate-form ajax-submit" data-close-modal-on-success='true' data-reload-tables='true'>
	{{ csrf_field() }}
	<input type="hidden" name="model[itemListId]" value="{{$model->itemListId}}">
	<div class="form-group">
		<label>Lista</label>
		<input type="text" class="form-control " disabled value="{{$model->itemList->name}} | {{$model->itemList::$itemTypeLabels[$model->itemList->itemType]}}">
	</div>
	<div class="form-group">
		<label>{{$model->itemList::$itemTypeLabels[$model->itemList->itemType]}}</label>
		<input type="text" data-ajax='/admin/hotels/search' class="form-control autocomplete-ajax" value="{{$model->referenceId>0?$model->referenceModel->name:''}}">
		<input type="hidden" name="model[referenceId]" value="<?= $model->referenceId ?>" class='required'>
	</div>
	<div class="form-group">
		<label>Precio</label>
		<input type="text" class="form-control required" name="model[fromPrice]" value="{{$model->fromPrice}}">
	</div>
	<div class="form-group">
		<label>Moneda</label>
		<select class="form-control" name="model[currencyCode]">
			<option value="USD">USD</option>
			<option value="MXN">MXN</option>
		</select>
	</div>
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
</form>
</div>
