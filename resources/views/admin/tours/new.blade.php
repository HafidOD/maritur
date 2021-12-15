<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>New Tour</h3>
</div>
<div class="modal-body">
<form action="{{url('admin/tours/create')}}" method="post" class="validate-form ajax-submit" data-close-modal-on-success='true' data-reload-tables='true'>
	{{ csrf_field() }}
	<div class="text-right">
		<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
	</div>
	<br>
	@include('templates.tabs',['tabs'=>[
		'Información'=> 'admin.tours.info',
		'Contenido'=> 'admin.tours.info2',
		'Categorias'=> 'admin.tours.categories',
	]])
</form>
</div>
