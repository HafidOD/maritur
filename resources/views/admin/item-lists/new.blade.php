<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Nueva lista</h3>
</div>
<div class="modal-body">
<form action="{{url('admin/item-lists')}}" method="post" class="validate-form ajax-submit" data-close-modal-on-success='true' data-reload-tables='true'>
	{{ csrf_field() }}
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" class="form-control required" name="model[name]" value="{{$itemList->name}}">
	</div>
	<div class="form-group">
		<label>Tipo</label>
		<select name="model[itemType]" class="form-control">
			<option value="1">Hoteles</option>
			<option value="2">Tours</option>
			<option value="3">Destinos</option>
		</select>
	</div>
	<div class="form-group">
		<label>Sección</label>
		<select name="model[section]" class="form-control">
			<option value="1">Home</option>
			<option value="2">Tours</option>
			<option value="3">Offers</option>
		</select>
	</div>
	<div class="form-group">
		<label>Orden</label>
		<select name="model[orderx]" class="form-control">
			@for ($i = 1; $i <= 10; $i++)
				<option value="{{$i}}">{{$i}}</option>
			@endfor
		</select>
	</div>
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
</form>
</div>
