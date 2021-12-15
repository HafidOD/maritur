<form action="{{url("admin/item-lists/{$itemList->id}")}}" method="post" class="validate-form ajax-submit" data-close-modal-on-success='true' data-reload-tables='true'>
	{{ csrf_field() }}
	{{ method_field('PUT') }}
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" class="form-control required" name="model[name]" value="{{$itemList->name}}">
	</div>
	<div class="form-group">
		<label>Tipo</label>
		<select name="model[itemType]" class="form-control">
			<option {{$itemList->itemType==1?'selected':''}} value="1">Hoteles</option>
			<option {{$itemList->itemType==2?'selected':''}} value="2">Tours</option>
			<option {{$itemList->itemType==3?'selected':''}} value="3">Destinos</option>
		</select>
	</div>
	<div class="form-group">
		<label>Sección</label>
		<select name="model[section]" class="form-control">
			<option {{$itemList->section==1?'selected':''}} value="1">Home</option>
			<option {{$itemList->section==2?'selected':''}} value="2">Tours</option>
			<option {{$itemList->section==3?'selected':''}} value="3">Offers</option>
		</select>
	</div>
	<div class="form-group">
		<label>Orden</label>
		<select name="model[orderx]" class="form-control">
			@for ($i = 1; $i <= 10; $i++)
				<option {{$itemList->orderx==$i?'selected':''}} value="{{$i}}">{{$i}}</option>
			@endfor
		</select>
	</div>
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
</form>