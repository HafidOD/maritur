<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Nueva variante de Tour</h3>
</div>
<div class="modal-body">
<form 
	action="{{url('admin/tour-prices')}}" 
	method="post"
	class="validate-form ajax-submit"
	data-close-modal-on-success='true'
	data-reload-tables='true'
>
	{{ csrf_field() }}
	@include('admin.tour-prices.info')
</form>
</div>
