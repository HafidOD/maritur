<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Edit</h3>
</div>
<div class="modal-body">
	@include('templates.tabs',['tabs'=>[
		'Información'=> 'admin.item-lists.info',
		'Items'=> 'admin.item-lists.items',
	]])
</div>