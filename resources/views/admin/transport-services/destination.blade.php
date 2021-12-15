<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Servicio de transporte en {{$dest->name}}</h3>
</div>
<div class="modal-body">
	@include('templates.tabs',['tabs'=>[
		'Precios'=> 'admin.transport-services.prices',
	]])
</div>
