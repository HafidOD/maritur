<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Tour</h3>
</div>
<div class="modal-body">
	<form action="{{url('admin/tours/update',['id'=>$tour->id])}}" method="post" class="validate-form ajax-submit" data-reload-tables='true'>
		{{ csrf_field() }}
		<div class="text-right">
			<a href='{{ $tour->getFullPath() }}' target="_blank" class="btn btn-warning" ><i class="fa fa-eye"></i> Ver</a>
			<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
			<a href='{{ url('admin/tours/gallery',['id'=>$tour->id]) }}' type="submit" class="btn btn-info" data-toggle='modal-dinamic'><i class="fa fa-file-image-o"></i> Galeria</a>
		</div>
		<br>
		@include('templates.tabs',['tabs'=>[
			'Información'=> 'admin.tours.info',
			'Variantes'=> 'admin.tours.variants',
			'Contenido'=> 'admin.tours.info2',
			'Categorias'=> 'admin.tours.categories',
			'Destinos'=> 'admin.tours.destinations',
		]])
	</form>
</div>
