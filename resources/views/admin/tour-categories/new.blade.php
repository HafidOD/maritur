<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Nuevo proveedor de tours</h3>
</div>
<div class="modal-body">
<form action="{{url('admin/tour-categories')}}" method="post" class="validate-form ajax-submit" data-close-modal-on-success='true' data-reload-tables='true'>
	{{ csrf_field() }}
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" class="form-control required" name="model[name]" value="{{$tourCategory->name}}">
	</div>
	<div class="form-group">
		<label>Clase Icono</label>
		<input type="text" class="form-control required" name="model[faIconClass]" value="{{$tourCategory->faIconClass}}">
	</div>
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
</form>
</div>
