<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Nuevo transporte</h3>
</div>
<div class="modal-body">
<form action="{{url('admin/transport-service-types')}}" method="post" class="validate-form ajax-submit" data-close-modal-on-success='true' data-reload-tables='true'>
	{{ csrf_field() }}
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" class="form-control required" name="model[name]" value="">
	</div>
	<div class="form-group">
		<label>Tipo de cobro</label>
		<select class="form-control required" name="model[priceType]">
			<option value="1">Por persona</option>
			<option value="2">Por vehículo</option>
		</select>
	</div>
	<div class="form-group">
		<label>Máximo de personas</label>
		<select class="form-control required" name="model[maxPax]">
			@for ($i = 1; $i < 20; $i++)
				<option value="{{$i}}">{{$i}}</option>
			@endfor
		</select>
	</div>
	<div class="form-group">
		<label>Descripción</label>
		<textarea class="form-control html-content" name='model[description]'></textarea>
	</div>
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
</form>
</div>
