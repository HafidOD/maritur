<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Servicio de transporte en {{$dest->name}}</h3>
</div>
<div class="modal-body">
<form action="{{url('admin/transport-services/update',['id'=>$dest->id])}}" method="post" class="validate-form ajax-submit" data-reload-tables='true'>
	{{ csrf_field() }}
	<div class="text-right">
		<a href='/transfers/{{ $dest->path }}' target="_blank" class="btn btn-warning" ><i class="fa fa-eye"></i> Ver</a>
	</div>
	<br>
	@include('templates.tabs',['tabs'=>[
		'Precios'=> 'admin.transport-services.prices',
	]])
</form>
</div>
