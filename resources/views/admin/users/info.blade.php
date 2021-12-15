<div class="row">
	<div class="form-group col-md-6">
		<label>Nombre</label>
		<input type="text" class="form-control" name="model[name]" value="{{$model->name}}">
	</div>		
	<div class="form-group col-md-6">
		<label>Email</label>
		<input type="text" class="form-control" name="model[email]" value="{{$model->email}}">
	</div>		
	<div class="form-group col-md-4">
		<label>Rol</label>
		<select class="form-control" name="model[role]">
			@foreach ($model::$roleLabels as $key=>$value)
				<option {{$key == $model->role?'selected':''}} value="{{$key}}">{{$value}}</option>
			@endforeach
		</select>
	</div>		
	<div class="form-group col-md-4">
		<label>Afiliado</label>
		<select class="form-control" name="model[affiliateId]">
			@foreach ($affiliates as $af)
				<option {{$af->id == $model->affiliateId?'selected':''}} value="{{$af->id}}">{{$af->name}}</option>
			@endforeach
		</select>
	</div>		
	<div class="form-group col-md-4">
		<label>Estado</label>
		<select class="form-control" name="model[status]">
			@foreach ($model::$statusLabels as $key=>$value)
				<option {{$key==$model->status?'selected':''}} value="{{$key}}">{{$value}}</option>
			@endforeach
		</select>
	</div>		
	<div class="form-group col-md-6">
		<label>Contraseña</label>
		<input type="text" class="form-control" name="password" value="">
	</div>		
</div>