<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Nuevo destino</h3>
</div>
<div class="modal-body">
<form action="{{url('admin/destinations')}}" method="post" class="validate-form ajax-submit" data-close-modal-on-success='true' data-reload-tables='true'>
	{{ csrf_field() }}
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" class="form-control required" name="model[name]" value="{{$model->name}}">
	</div>
	<div class="form-group">
		<label>path</label>
		<input type="text" class="form-control required" name="model[path]" value="{{$model->path}}">
	</div>
	<div class="form-group">
		<label>País</label>
		<select class="form-control" name='model[countryCode]'>
			@foreach ($countries as $c)
				<option {{$model->countryCode==$c->code?'selected':''}} value="{{$c->code}}">{{$c->name}}</option>
			@endforeach
		</select>
	</div>
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
</form>
</div>
