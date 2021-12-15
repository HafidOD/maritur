<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Nuevo precio de transporte para {{$dest->name}}</h3>
</div>
<div class="modal-body">
<form action="{{url('admin/transport-services')}}" method="post" class="validate-form ajax-submit" data-close-modal-on-success='true' data-reload-tables='true'>
	{{ csrf_field() }}
	<input type="hidden" name="model[destinationId]" value="{{$dest->id}}">
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" class="form-control required" name="model[name]" value="">
	</div>
	<div class="form-group">
		<label>Precio Oneway</label>
		<div class="input-group">	
			<span class="input-group-addon">$</span>
			<input type="text" class="form-control required number" name="model[onewayPrice]" value="">
		</div>
	</div>
	<div class="form-group">
		<label>Precio Roundtrip</label>
		<div class="input-group">	
			<span class="input-group-addon">$</span>
			<input type="text" class="form-control required number" name="model[roundtripPrice]" value="">
		</div>
	</div>
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
</form>
</div>
