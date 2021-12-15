<div class="form-group">
	<label>Nombre</label>
	<input type="text" name="model[name]" class="form-control required" value="{{$model->name}}">
</div>
<div class="form-group">
	<label>URL</label>
	<input type="text" name="model[path]" class="form-control" value="{{$model->path}}">
</div>
<div class="form-group">
	<label>Destino</label>
	<select class="form-control" name='model[destinationId]'>
		@foreach ($cities as $c)
			<option {{$model->destinationId==$c->id?'selected':''}} value="{{$c->id}}">{{$c->name}}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
	<label>Descripción</label>
	<textarea class="form-control html-content" name='model[description]'>{{$model->description}}</textarea>
</div>
<div class="form-group">
	<label>Descripción corta</label>
	<textarea class="form-control" name='model[shortDescription]'>{{$model->shortDescription}}</textarea>
</div>