<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Variante de Tour</h3>
</div>
<div class="modal-body">
<form 
	action="{{url('admin/tour-prices/update',[$tourPrice->id])}}" 
	method="post"
	class="validate-form ajax-submit"
	data-close-modal-on-success='true'
	data-reload-tables='true'
>
	{{ csrf_field() }}
	@include('templates.tabs',['tabs'=>[
		'Información'=> 'admin.tour-prices.info',
		'Transportación'=> 'admin.tour-prices.transport',
	]])
</form>
</div>
