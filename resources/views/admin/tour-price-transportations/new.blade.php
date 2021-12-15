<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Nuevo costo de transportación</h3>
</div>
<div class="modal-body">
<form 
	action="{{url('admin/tour-price-transportations')}}" 
	method="post"
	class="validate-form ajax-submit"
	data-close-modal-on-success='true'
	data-reload-tables='true'
>
	{{ csrf_field() }}
	@include('admin.tour-price-transportations.info')
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
</form>
</div>
