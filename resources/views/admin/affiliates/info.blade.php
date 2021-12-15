<div class="row">
	<div class="form-group col-md-6">
		<label>Nombre</label>
		<input type="text" class="form-control" name="model[name]" value="{{$model->name}}">
	</div>		
	<div class="form-group col-md-6">
		<label>Estado</label>
		<select class="form-control" name="model[status]">
			@foreach ($model::$statusLabels as $key=>$value)
				<option {{$key==$model->status?'selected':''}} value="{{$key}}">{{$value}}</option>
			@endforeach
		</select>
	</div>		
</div>
<div class="form-group">
	<label>Dominio</label>
	<input type="text" class="form-control" name="model[domain]" value="{{$model->domain}}">
</div>