<div class="form-group">
	<label>Nombre</label>
	<input type="text" name="model[name]" class="form-control required" value="{{$tour->name}}">
</div>
<div class="form-group">
	<label>URL</label>
	<input type="text" name="model[path]" class="form-control" value="{{$tour->path}}">
</div>
<div class="form-group">
	<label>Proveedor</label>
	<select class="form-control" name='model[tourProviderId]'>
		@foreach ($tourProviders as $tp)
			<option {{$tour->tourProviderId==$tp->id?'select':''}} value="{{$tp->id}}">{{$tp->name}}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
	<label>Destino</label>
	<select class="form-control" name='model[cityId]'>
		@foreach ($cities as $c)
			<option {{$tour->cityId==$c->id?'selected':''}} value="{{$c->id}}">{{$c->name}}</option>
		@endforeach
	</select>
</div>
<div class="row">
	<div class="form-group col-sm-6">
		<label>Se considera niño desde:</label>
		<input type="text" name="model[childrenMinAge]" class="form-control number required" value="{{$tour->childrenMinAge}}">
	</div>
	<div class="form-group col-sm-6">
		<label>hasta:</label>
		<input type="text" name="model[childrenMaxAge]" class="form-control number required" value="{{$tour->childrenMaxAge}}">
	</div>
</div>
<div class="form-group">
	<label>Descripción corta</label>
	<textarea class="form-control html-content" name='model[shortDescription]'>{{$tour->shortDescription}}</textarea>
</div>